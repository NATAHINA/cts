<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\CotationLigneModel;
use App\Models\CotationModel;
use App\Models\CroisiereModel;
use App\Models\DestinationModel;
use App\Models\DeviseModel;
use App\Models\ExcursionModel;
use App\Models\ForfaitModel;
use App\Models\HotelModel;
use App\Models\TransfertModel;
use App\Models\VolModel;
use App\Models\CircuitModel;

class CotationController extends BaseController
{
    /**
     * Modèle CI4 associé à chaque type de service pouvant composer
     * une ligne de cotation.
     */
    private function serviceModels(): array
    {
        return [
            'hotel'     => HotelModel::class,
            'vol'       => VolModel::class,
            'excursion' => ExcursionModel::class,
            'transfert' => TransfertModel::class,
            'croisiere' => CroisiereModel::class,
            'forfait'   => ForfaitModel::class,
            'circuit'   => CircuitModel::class,
        ];
    }

    public function index()
    {
        $model = new CotationModel();

        $data = [
            'title' => 'Cotations',
            'items' => $model
                ->select('cotations.*, clients.nom as client_nom')
                ->join('clients', 'clients.id = cotations.client_id', 'left')
                ->orderBy('cotations.created_at', 'DESC')
                ->findAll(),
        ];

        return view('cotations/index', $data);
    }

    public function create()
    {
        $clientModel = new ClientModel();
        $deviseModel = new DeviseModel();

        return view('cotations/create', [
            'title'   => 'Nouvelle cotation',
            'clients' => $clientModel->orderBy('nom', 'ASC')->findAll(),
            'devises' => $deviseModel->orderBy('code', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $model = new CotationModel();

        $reference = $model->prochaineReference((int) session('tenant_id'));

        $id = $model->insert([
            'tenant_id'     => session('tenant_id'),
            'client_id'     => $this->request->getPost('client_id'),
            'user_id'       => session('user_id'),
            'reference'     => $reference,
            'statut'        => 'brouillon',
            'date_validite' => $this->request->getPost('date_validite') ?: null,
            'devise_id'     => $this->request->getPost('devise_id'),
            'notes'         => $this->request->getPost('notes'),
        ]);

        return redirect()->to('/cotations/' . $id)->with('success', 'Cotation créée. Vous pouvez maintenant y ajouter des prestations.');
    }

    public function show($id)
    {
        $model      = new CotationModel();
        $ligneModel = new CotationLigneModel();
        $cotation   = $model->find($id);

        if (! $cotation) {
            return redirect()->to('/cotations')->with('error', 'Cotation introuvable.');
        }

        $clientModel = new ClientModel();

        // Catalogue de chaque type de service, encodé pour le JS du formulaire d'ajout de ligne
        $catalogue = [];
        foreach ($this->serviceModels() as $type => $modelClass) {
            $m = new $modelClass();
            $rows = $m->where('statut', 'actif')->findAll();
            $catalogue[$type] = array_map(static function ($row) use ($type) {
                $label = $row['nom'] ?? ($row['compagnie'] . ' ' . ($row['num_vol'] ?? ''));
                $prix  = $row['prix'] ?? $row['prix_nuit'] ?? 0;
                return ['id' => $row['id'], 'label' => $label, 'prix' => $prix];
            }, $rows);
        }

        return view('cotations/show', [
            'title'     => 'Cotation ' . $cotation['reference'],
            'cotation'  => $cotation,
            'client'    => $clientModel->find($cotation['client_id']),
            'lignes'    => $ligneModel->where('cotation_id', $id)->orderBy('ordre', 'ASC')->findAll(),
            'catalogue' => $catalogue,
        ]);
    }

    public function print($id)
    {
        $model      = new CotationModel();
        $ligneModel = new CotationLigneModel();
        $cotation   = $model->find($id);

        if (! $cotation) {
            return redirect()->to('/cotations')->with('error', 'Cotation introuvable.');
        }

        $clientModel = new ClientModel();
        $deviseModel = new DeviseModel();

        return view('cotations/print', [
            'title'    => 'Cotation ' . $cotation['reference'],
            'cotation' => $cotation,
            'client'   => $clientModel->find($cotation['client_id']),
            'devise'   => $deviseModel->find($cotation['devise_id']),
            'lignes'   => $ligneModel->where('cotation_id', $id)->orderBy('ordre', 'ASC')->findAll(),
        ]);
    }

    public function edit($id)
    {
        $model    = new CotationModel();
        $cotation = $model->find($id);

        if (! $cotation) {
            return redirect()->to('/cotations')->with('error', 'Cotation introuvable.');
        }

        $clientModel = new ClientModel();
        $deviseModel = new DeviseModel();

        return view('cotations/edit', [
            'title'    => 'Modifier — ' . $cotation['reference'],
            'cotation' => $cotation,
            'clients'  => $clientModel->orderBy('nom', 'ASC')->findAll(),
            'devises'  => $deviseModel->orderBy('code', 'ASC')->findAll(),
        ]);
    }

    public function update($id)
    {
        $model = new CotationModel();

        $model->update($id, [
            'client_id'     => $this->request->getPost('client_id'),
            'date_validite' => $this->request->getPost('date_validite') ?: null,
            'devise_id'     => $this->request->getPost('devise_id'),
            'notes'         => $this->request->getPost('notes'),
        ]);

        return redirect()->to('/cotations/' . $id)->with('success', 'Cotation mise à jour.');
    }

    public function delete($id)
    {
        $model = new CotationModel();
        $model->delete($id);

        return redirect()->to('/cotations')->with('success', 'Cotation supprimée.');
    }

    public function changeStatut($id)
    {
        $model  = new CotationModel();
        $statut = $this->request->getPost('statut');

        if (in_array($statut, ['brouillon', 'envoyee', 'acceptee', 'refusee', 'expiree'], true)) {
            $model->update($id, ['statut' => $statut]);
        }

        return redirect()->to('/cotations/' . $id)->with('success', 'Statut mis à jour.');
    }

    // -------------------------------------------------------------
    // Lignes de cotation
    // -------------------------------------------------------------

    public function addLigne($cotationId)
    {
        $model    = new CotationModel();
        $cotation = $model->find($cotationId); // tenant-scopé : 404 implicite si pas le bon tenant

        if (! $cotation) {
            return redirect()->to('/cotations')->with('error', 'Cotation introuvable.');
        }

        $ligneModel = new CotationLigneModel();

        $quantite = (float) $this->request->getPost('quantite') ?: 1;
        $prixU    = (float) $this->request->getPost('prix_unitaire');

        $ligneModel->insert([
            'cotation_id'   => $cotationId,
            'type_service'  => $this->request->getPost('type_service'),
            'service_id'    => $this->request->getPost('service_id') ?: null,
            'libelle'       => $this->request->getPost('libelle'),
            'date_debut'    => $this->request->getPost('date_debut') ?: null,
            'date_fin'      => $this->request->getPost('date_fin') ?: null,
            'quantite'      => $quantite,
            'prix_unitaire' => $prixU,
            'montant'       => $quantite * $prixU,
            'ordre'         => (int) $ligneModel->where('cotation_id', $cotationId)->countAllResults() + 1,
        ]);

        $model->recalculerMontant((int) $cotationId);

        return redirect()->to('/cotations/' . $cotationId)->with('success', 'Prestation ajoutée.');
    }

    public function deleteLigne($cotationId, $ligneId)
    {
        $model    = new CotationModel();
        $cotation = $model->find($cotationId);

        if (! $cotation) {
            return redirect()->to('/cotations')->with('error', 'Cotation introuvable.');
        }

        $ligneModel = new CotationLigneModel();
        $ligneModel->where('cotation_id', $cotationId)->where('id', $ligneId)->delete();

        $model->recalculerMontant((int) $cotationId);

        return redirect()->to('/cotations/' . $cotationId)->with('success', 'Prestation retirée.');
    }
}
