<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DemandeModel;
use App\Models\ClientModel;
use App\Models\CotationModel;
use \App\Models\DemandeLigneModel;
use \App\Models\DestinationModel;

class DemandeController extends BaseController
{
    protected $demandeModel;
    protected $clientModel;
    protected $ligneModel;
    protected $destinationModel;

    public function __construct()
    {
        $this->demandeModel     = new DemandeModel();
        $this->clientModel      = new ClientModel();
        $this->ligneModel       = new DemandeLigneModel();
        $this->destinationModel = new DestinationModel();
    }

    protected function tenantId()
    {
        return (int) session('tenant_id');
    }

    public function index()
    {
        $demandes = $this->demandeModel
            ->select('demandes.*, clients.nom AS client_nom, clients.prenom AS client_prenom, destinations.nom as destination_nom')
            ->join('clients', 'clients.id = demandes.client_id', 'left')
            ->join('destinations', 'destinations.id = demandes.destination_id', 'left')
            ->where('demandes.tenant_id', $this->tenantId())
            ->orderBy('demandes.id', 'DESC')
            ->findAll();

        return view('demandes/index', [
            'title' => 'Demandes clients',
            'demandes' => $demandes
        ]);
    }

    public function create(){
        return view('demandes/form', [
            'title'        => 'Nouvelle demande',
            'demande'      => null,
            'clients'      => $this->clientModel
                ->where('tenant_id', $this->tenantId())
                ->where('statut', 'actif')
                ->orderBy('nom')
                ->findAll(),
            'destinations' => $this->destinationModel
                ->orderBy('nom')
                ->findAll(),
        ]);
    }

    public function store(){
        $data = $this->request->getPost();

        $data['tenant_id']  = $this->tenantId();
        $data['numero']     = $this->demandeModel->generateNumero($this->tenantId());
        $data['created_by'] = session('user_id');

        // destination_id
        $data['destination_id'] = $this->request->getPost('destination_id') ?: null;

        $id = $this->demandeModel->insert($data);

        if (! $id) {
            return redirect()->back()->withInput()->with('errors', $this->demandeModel->errors());
        }

        return redirect()
            ->to(site_url('demandes/' . $id))
            ->with('success', 'Demande créée avec succès.');
    }

    public function show($id){
        $tenantId = $this->tenantId();

        $demande = $this->demandeModel
            ->select('
                demandes.*,
                clients.nom AS client_nom,
                clients.prenom AS client_prenom,
                clients.email AS client_email,
                clients.telephone AS client_telephone,
                clients.entreprise AS client_entreprise,
                destinations.nom AS destination_nom
            ')
            ->join('clients', 'clients.id = demandes.client_id AND clients.tenant_id = demandes.tenant_id', 'left')
            ->join('destinations', 'destinations.id = demandes.destination_id', 'left')
            ->where('demandes.id', $id)
            ->where('demandes.tenant_id', $tenantId)
            ->first();

        if (! $demande) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $lignes = $this->ligneModel
            ->where('demande_id', $id)
            ->orderBy('ordre', 'ASC')
            ->findAll();

        // --- Totaux des lignes ---
        $totalCout  = 0.0;
        $totalPrix  = 0.0;
        $totalMarge = 0.0;

        foreach ($lignes as $l) {
            $totalCout  += (float) ($l['cout_total'] ?? 0);
            $totalPrix  += (float) ($l['prix_total'] ?? 0);
            $totalMarge += (float) ($l['marge_montant'] ?? 0);
        }

        $margePourcentage = $totalCout > 0
            ? ($totalMarge / $totalCout) * 100
            : 0;

        // Prix affiché = forfait si renseigné, sinon somme des lignes
        $prixAffiche = !empty($demande['prix_forfait'])
            ? (float) $demande['prix_forfait']
            : $totalPrix;

        return view('demandes/show', [
            'title'            => 'Détails de la demande',
            'demande'          => $demande,
            'lignes'           => $lignes,
            'catalogue'        => $this->buildCatalogue(),
            'totalCout'        => $totalCout,
            'totalPrix'        => $totalPrix,
            'totalMarge'       => $totalMarge,
            'margePourcentage' => $margePourcentage,
            'prixAffiche'      => $prixAffiche,
        ]);
    }

    private function buildCatalogue(): array{
        $serviceModels = [
            'hotel'      => \App\Models\HotelModel::class,
            'vol'        => \App\Models\VolModel::class,
            'excursion'  => \App\Models\ExcursionModel::class,
            'transfert'  => \App\Models\TransfertModel::class,
            'restaurant' => \App\Models\RestaurantModel::class,
            'croisiere'  => \App\Models\CroisiereModel::class,
            'forfait'    => \App\Models\ForfaitModel::class,
            'circuit'    => \App\Models\CircuitModel::class,
        ];

        $catalogue = [];

        foreach ($serviceModels as $type => $modelClass) {

            if (! class_exists($modelClass)) {
                $catalogue[$type] = [];
                continue;
            }

            try {
                $m = new $modelClass();
                $fields = $m->db->getFieldNames($m->getTable());

                if (in_array('statut', $fields, true)) {
                    $m->where('statut', 'actif');
                } elseif (in_array('actif', $fields, true)) {
                    $m->where('actif', 1);
                }

                $rows = $m->findAll();

                $catalogue[$type] = array_map(static function ($row) {
                    $label = $row['nom']
                        ?? $row['designation']
                        ?? trim(($row['compagnie'] ?? '') . ' ' . ($row['num_vol'] ?? ''))
                        ?? ('#' . ($row['id'] ?? ''));

                    $prix = $row['prix']
                        ?? $row['prix_nuit']
                        ?? $row['prix_adulte']
                        ?? $row['prix_moyen']
                        ?? $row['prix_menu']
                        ?? 0;

                    return [
                        'id'    => $row['id'],
                        'label' => $label,
                        'prix'  => (float) $prix,
                    ];
                }, $rows);

            } catch (\Throwable $e) {
                $catalogue[$type] = [];
                log_message('error', 'Catalogue ' . $type . ' : ' . $e->getMessage());
            }
        }

        return $catalogue;
    }

    public function edit($id){
        $demande = $this->demandeModel
            ->where('tenant_id', $this->tenantId())
            ->find($id);

        if (! $demande) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('demandes/form', [
            'title'        => 'Modifier la demande',
            'demande'      => $demande,
            'clients'      => $this->clientModel
                ->where('tenant_id', $this->tenantId())
                ->findAll(),
            'destinations' => $this->destinationModel->orderBy('nom')->findAll(),
        ]);
    }

    public function update($id){
        $demande = $this->demandeModel
            ->where('tenant_id', $this->tenantId())
            ->find($id);

        if (! $demande) {
            return redirect()->to(site_url('demandes'))->with('error', 'Demande introuvable.');
        }

        $data = $this->request->getPost();
        $data['destination_id'] = $this->request->getPost('destination_id') ?: null;

        $this->demandeModel->update($id, $data);

        return redirect()
            ->to(site_url('demandes/' . $id))
            ->with('success', 'Demande modifiée.');
    }

    public function delete($id){
        $demande = $this->demandeModel
            ->where('tenant_id', $this->tenantId())
            ->find($id);

        if (! $demande) {
            return redirect()->to(site_url('demandes'))
                ->with('error', 'Demande introuvable.');
        }

        $statut = $demande['statut'] ?? '';

        if (! in_array($statut, ['nouvelle', 'abandonnée', 'abandonnee'], true)) {
            return redirect()
                ->to(site_url('demandes/' . $id))
                ->with('error', 'Seules les demandes nouvelles ou abandonnées peuvent être supprimées.');
        }

        $this->ligneModel->where('demande_id', $id)->delete();
        $this->demandeModel->delete($id);

        return redirect()
            ->to(site_url('demandes'))
            ->with('success', 'Demande supprimée.');
    }

    public function convertir($id){
        $tenantId = $this->tenantId();

        $demande = $this->demandeModel
            ->select('
                demandes.*,
                clients.nom AS client_nom,
                clients.prenom AS client_prenom,
                clients.entreprise AS client_entreprise,
                clients.email AS client_email,
                clients.telephone AS client_telephone,
                destinations.nom AS destination_nom
            ')
            ->join('clients', 'clients.id = demandes.client_id AND clients.tenant_id = demandes.tenant_id', 'left')
            ->join('destinations', 'destinations.id = demandes.destination_id', 'left')
            ->where('demandes.tenant_id', $tenantId)
            ->where('demandes.id', $id)
            ->first();

        if (! $demande) {
            return redirect()->to(site_url('demandes'))->with('error', 'Demande introuvable.');
        }

        if (($demande['statut'] ?? '') === 'convertie') {
            return redirect()
                ->to(site_url('demandes/' . $id))
                ->with('error', 'Cette demande a déjà été convertie en cotation.');
        }

        $lignes = $this->ligneModel
            ->where('demande_id', $id)
            ->orderBy('ordre', 'ASC')
            ->findAll();

        $totalCout = $totalPrix = $totalMarge = 0.0;
        foreach ($lignes as $l) {
            $totalCout  += (float) ($l['cout_total'] ?? 0);
            $totalPrix  += (float) ($l['prix_total'] ?? 0);
            $totalMarge += (float) ($l['marge_montant'] ?? 0);
        }

        return view('demandes/convertir', [
            'title'      => 'Convertir la demande en cotation',
            'demande'    => $demande,
            'lignes'     => $lignes,
            'totalCout'  => $totalCout,
            'totalPrix'  => $totalPrix,
            'totalMarge' => $totalMarge,
        ]);
    }


    public function storeConversion($id){
        $tenantId = $this->tenantId();

        $demande = $this->demandeModel
            ->where('tenant_id', $tenantId)
            ->find($id);

        if (! $demande) {
            return redirect()->to(site_url('demandes'))->with('error', 'Demande introuvable.');
        }

        if (($demande['statut'] ?? '') === 'convertie') {
            return redirect()
                ->to(site_url('demandes/' . $id))
                ->with('error', 'Cette demande a déjà été convertie.');
        }

        $cotationModel = new \App\Models\CotationModel();
        $cotationLigneModel = new \App\Models\CotationLigneModel();

        $numero = $cotationModel->prochaineReference($tenantId);

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Créer la cotation
        $cotationId = $cotationModel->insert([
            'tenant_id'             => $tenantId,
            'numero'                => $numero,
            'client_id'             => $demande['client_id'],
            'demande_id'            => $demande['id'],
            'destination_id'        => $demande['destination_id'] ?? null,

            'date_depart'           => $demande['date_depart'] ?? null,
            'date_retour'           => $demande['date_retour'] ?? null,
            'nb_adultes'            => (int) ($demande['nb_adultes'] ?? 1),
            'nb_enfants'            => (int) ($demande['nb_enfants'] ?? 0),
            'nb_bebes'              => (int) ($demande['nb_bebes'] ?? 0),

            'devise'                => $demande['devise'] ?? 'MGA',
            'taux_change'           => 1,

            'cout_total'            => 0,
            'marge_montant'         => 0,
            'marge_pourcentage'     => 0,
            'reduction_montant'     => 0,
            'reduction_pourcentage' => 0,
            'taxe_montant'          => 0,
            'prix_total'            => 0,
            'prix_par_personne'     => 0,

            'statut'                => 'brouillon',
            'notes_client'          => $demande['notes_client'] ?? null,
            'notes_interne' => trim(
                ($demande['notes_interne'] ?? '')
                . (
                    ($demande['mode_tarif'] ?? '') === 'forfait' && !empty($demande['prix_forfait'])
                        ? "\n[Forfait demandé : " . number_format((float)$demande['prix_forfait'], 2, ',', ' ') . ' ' . ($demande['devise'] ?? '') . ']'
                        : ''
                )
            ),
            'created_by'            => session('user_id'),
        ]);

        // 2. Copier les lignes
        $lignes = $this->ligneModel
            ->where('demande_id', $id)
            ->orderBy('ordre', 'ASC')
            ->findAll();

        foreach ($lignes as $ligne) {
            $cotationLigneModel->insert([
                'tenant_id'         => $tenantId,
                'cotation_id'       => $cotationId,
                'type_prestation'   => $ligne['type_prestation'],
                'prestation_id'     => $ligne['prestation_id'] ?? null,
                'fournisseur_id'    => $ligne['fournisseur_id'] ?? null,
                'designation'       => $ligne['designation'],
                'description'       => $ligne['description'] ?? null,
                'quantite'          => $ligne['quantite'],
                'cout_unitaire'     => $ligne['cout_unitaire'],
                'cout_total'        => $ligne['cout_total'],
                'marge_pourcentage' => $ligne['marge_pourcentage'],
                'marge_montant'     => $ligne['marge_montant'],
                'prix_unitaire'     => $ligne['prix_unitaire'],
                'prix_total'        => $ligne['prix_total'],
                'devise'            => $ligne['devise'] ?? $demande['devise'],
                'ordre'             => $ligne['ordre'],
            ]);
        }

        // 3. Recalculer les montants de la cotation
        $cotationModel->recalculerMontants((int) $cotationId);

        // 4. Marquer la demande comme convertie
        $this->demandeModel->update($id, ['statut' => 'convertie']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()
                ->to(site_url('demandes/' . $id))
                ->with('error', 'Erreur lors de la conversion.');
        }

        return redirect()
            ->to(site_url('cotations/' . $cotationId))
            ->with('success', 'Demande ' . $demande['numero'] . ' convertie en cotation ' . $numero . '.');
    }



    public function addLigne($demandeId){
        $demande = $this->demandeModel
            ->where('tenant_id', $this->tenantId())
            ->find($demandeId);

        if (! $demande) {
            return redirect()->to(site_url('demandes'))->with('error', 'Demande introuvable.');
        }

        $quantite         = (float) ($this->request->getPost('quantite') ?: 1);
        $coutUnitaire     = (float) ($this->request->getPost('cout_unitaire') ?: 0);
        $margePourcentage = (float) ($this->request->getPost('marge_pourcentage') ?: 0);

        $coutTotal    = $quantite * $coutUnitaire;
        $margeMontant = $coutTotal * $margePourcentage / 100;
        $prixUnitaire = $coutUnitaire + ($coutUnitaire * $margePourcentage / 100);
        $prixTotal    = $quantite * $prixUnitaire;

        $ordre = (int) $this->ligneModel
            ->where('demande_id', $demandeId)
            ->countAllResults() + 1;

        $this->ligneModel->insert([
            'tenant_id'         => $this->tenantId(),
            'demande_id'        => $demandeId,
            'type_prestation'   => $this->request->getPost('type_prestation'),
            'prestation_id'     => $this->request->getPost('prestation_id') ?: null,
            'fournisseur_id'    => $this->request->getPost('fournisseur_id') ?: null,
            'designation'       => $this->request->getPost('designation'),
            'description'       => $this->request->getPost('description'),
            'quantite'          => $quantite,
            'cout_unitaire'     => $coutUnitaire,
            'cout_total'        => $coutTotal,
            'marge_pourcentage' => $margePourcentage,
            'marge_montant'     => $margeMontant,
            'prix_unitaire'     => $prixUnitaire,
            'prix_total'        => $prixTotal,
            'devise'            => $demande['devise'] ?? 'MGA',
            'ordre'             => $ordre,
        ]);

        return redirect()
            ->to(site_url('demandes/' . $demandeId))
            ->with('success', 'Prestation ajoutée.');
    }

    public function deleteLigne($demandeId, $ligneId){
        $demande = $this->demandeModel
            ->where('tenant_id', $this->tenantId())
            ->find($demandeId);

        if (! $demande) {
            return redirect()->to(site_url('demandes'))->with('error', 'Demande introuvable.');
        }

        $this->ligneModel
            ->where('demande_id', $demandeId)
            ->where('id', $ligneId)
            ->delete();

        return redirect()
            ->to(site_url('demandes/' . $demandeId))
            ->with('success', 'Prestation supprimée.');
    }

    public function updateTarif($id){
        $demande = $this->demandeModel
            ->where('tenant_id', $this->tenantId())
            ->find($id);

        if (! $demande) {
            return redirect()->to(site_url('demandes'))->with('error', 'Demande introuvable.');
        }

        if (in_array($demande['statut'] ?? '', ['convertie', 'annulee', 'refusee'], true)) {
            return redirect()
                ->to(site_url('demandes/' . $id))
                ->with('error', 'Impossible de modifier le tarif d’une demande convertie ou annulée.');
        }

        $mode = $this->request->getPost('mode_tarif') === 'forfait' ? 'forfait' : 'lignes';
        $prixForfait = $this->request->getPost('prix_forfait');

        $this->demandeModel->update($id, [
            'mode_tarif'   => $mode,
            'prix_forfait' => ($mode === 'forfait' && $prixForfait !== '')
                ? (float) $prixForfait
                : null,
        ]);

        return redirect()
            ->to(site_url('demandes/' . $id))
            ->with('success', 'Tarification mise à jour.');
    }
}