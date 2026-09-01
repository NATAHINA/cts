<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\FactureModel;
use App\Models\FactureLigneModel;
use App\Models\PaiementModel;
use App\Models\ReservationModel;

class FactureController extends BaseController
{
    protected function tenantId(): int
    {
        return (int) session('tenant_id');
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

    public function index()
    {
        $model = new FactureModel();

        $items = $model
            ->select('
                factures.*,
                clients.nom AS client_nom,
                clients.prenom AS client_prenom,
                clients.entreprise AS client_entreprise
            ')
            ->join('clients', 'clients.id = factures.client_id', 'left')
            ->where('factures.tenant_id', $this->tenantId())
            ->orderBy('factures.id', 'DESC')
            ->findAll();

        return view('factures/index', [
            'title' => 'Factures',
            'items' => $items,
        ]);
    }

    public function create()
    {
        $clientModel = new ClientModel();
        $reservationModel = new ReservationModel();

        return view('factures/form', [
            'title'        => 'Nouvelle facture',
            'facture'      => null,
            'clients'      => $clientModel
                ->where('tenant_id', $this->tenantId())
                ->orderBy('nom')
                ->findAll(),
            'reservations' => $reservationModel
                ->where('tenant_id', $this->tenantId())
                ->orderBy('id', 'DESC')
                ->findAll(),
        ]);
    }

    public function store()
    {
        $model = new FactureModel();
        $tenantId = $this->tenantId();

        $numero = $model->prochaineReference($tenantId);

        $id = $model->insert([
            'tenant_id'      => $tenantId,
            'numero'         => $numero,
            'client_id'      => $this->request->getPost('client_id') ?: null,
            'reservation_id' => $this->request->getPost('reservation_id') ?: null,
            'cotation_id'    => $this->request->getPost('cotation_id') ?: null,
            'date_facture'   => $this->request->getPost('date_facture') ?: date('Y-m-d'),
            'date_echeance'  => $this->request->getPost('date_echeance') ?: null,
            'devise'         => $this->request->getPost('devise') ?: 'MGA',
            'statut'         => $this->request->getPost('statut') ?: 'brouillon',
            'notes_client'   => $this->request->getPost('notes_client'),
            'notes_interne'  => $this->request->getPost('notes_interne'),
            'created_by'     => session('user_id'),
            'montant_ht'     => 0,
            'montant_tva'    => 0,
            'montant_ttc'    => 0,
            'montant_paye'   => 0,
            'montant_restant'=> 0,
        ]);

        if (! $id) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()
            ->to(site_url('factures/' . $id))
            ->with('success', 'Facture ' . $numero . ' créée.');
    }

    public function show($id)
    {
        $model = new FactureModel();
        $ligneModel = new FactureLigneModel();
        $paiementModel = new PaiementModel();
        $deviseModel   = new \App\Models\DeviseModel();
        $tenantId      = $this->tenantId();

        $facture = $model
            ->select('
                factures.*,
                clients.nom AS client_nom,
                clients.prenom AS client_prenom,
                clients.entreprise AS client_entreprise,
                clients.email AS client_email,
                clients.telephone AS client_telephone
            ')
            ->join('clients', 'clients.id = factures.client_id', 'left')
            ->where('factures.id', $id)
            ->where('factures.tenant_id', $this->tenantId())
            ->first();

        if (! $facture) {
            return redirect()->to(site_url('factures'))->with('error', 'Facture introuvable.');
        }

        $catalogue = $this->buildCatalogue();

        $deviseFacture = $facture['devise'] ?? 'MGA';

        $devises = $deviseModel
            ->where('tenant_id', $tenantId)
            ->orderBy('is_default', 'DESC')
            ->findAll();

        $totauxParDevise = [];

        foreach ($devises as $d) {
            $code = $d['code'];

            $totauxParDevise[$code] = [
                'symbole'          => $d['symbole'] ?? $code,
                'montant_ht'       => $deviseModel->convertir((float)($facture['montant_ht'] ?? 0), $deviseFacture, $code, $tenantId),
                'montant_tva'      => $deviseModel->convertir((float)($facture['montant_tva'] ?? 0), $deviseFacture, $code, $tenantId),
                'montant_ttc'      => $deviseModel->convertir((float)($facture['montant_ttc'] ?? 0), $deviseFacture, $code, $tenantId),
                'montant_paye'     => $deviseModel->convertir((float)($facture['montant_paye'] ?? 0), $deviseFacture, $code, $tenantId),
                'montant_restant'  => $deviseModel->convertir((float)($facture['montant_restant'] ?? 0), $deviseFacture, $code, $tenantId),
            ];
        }

        return view('factures/show', [
            'title'     => 'Facture ' . $facture['numero'],
            'facture'   => $facture,
            'lignes'    => $ligneModel
                ->where('facture_id', $id)
                ->orderBy('ordre', 'ASC')
                ->findAll(),
            'paiements' => $paiementModel
                ->where('facture_id', $id)
                ->where('statut', 'valide')
                ->orderBy('date_paiement', 'DESC')
                ->findAll(),
            'catalogue' => $catalogue,
            'totauxParDevise' => $totauxParDevise,
            'deviseFacture'   => $deviseFacture,
        ]);
    }

    public function edit($id)
    {
        $model = new FactureModel();
        $facture = $model
            ->where('tenant_id', $this->tenantId())
            ->find($id);

        if (! $facture) {
            return redirect()->to(site_url('factures'))->with('error', 'Facture introuvable.');
        }

        $clientModel = new ClientModel();
        $reservationModel = new ReservationModel();

        return view('factures/form', [
            'title'        => 'Modifier ' . $facture['numero'],
            'facture'      => $facture,
            'clients'      => $clientModel->where('tenant_id', $this->tenantId())->orderBy('nom')->findAll(),
            'reservations' => $reservationModel->where('tenant_id', $this->tenantId())->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function update($id)
    {
        $model = new FactureModel();
        $facture = $model->where('tenant_id', $this->tenantId())->find($id);

        if (! $facture) {
            return redirect()->to(site_url('factures'))->with('error', 'Facture introuvable.');
        }

        $model->update($id, [
            'client_id'      => $this->request->getPost('client_id') ?: null,
            'reservation_id' => $this->request->getPost('reservation_id') ?: null,
            'date_facture'   => $this->request->getPost('date_facture') ?: $facture['date_facture'],
            'date_echeance'  => $this->request->getPost('date_echeance') ?: null,
            'devise'         => $this->request->getPost('devise') ?: 'MGA',
            'statut'         => $this->request->getPost('statut') ?: $facture['statut'],
            'notes_client'   => $this->request->getPost('notes_client'),
            'notes_interne'  => $this->request->getPost('notes_interne'),
        ]);

        $model->recalculerMontants((int) $id);

        return redirect()
            ->to(site_url('factures/' . $id))
            ->with('success', 'Facture mise à jour.');
    }

    public function delete($id)
    {
        $model = new FactureModel();
        $model->where('tenant_id', $this->tenantId())->delete($id);

        return redirect()->to(site_url('factures'))->with('success', 'Facture supprimée.');
    }

    // ---- Lignes ----

    public function addLigne($factureId)
    {
        $model = new FactureModel();
        $facture = $model->where('tenant_id', $this->tenantId())->find($factureId);

        if (! $facture) {
            return redirect()->to(site_url('factures'))->with('error', 'Facture introuvable.');
        }

        $qte  = (float) ($this->request->getPost('quantite') ?: 1);
        $prix = (float) ($this->request->getPost('prix_unitaire') ?: 0);

        $ligneModel = new FactureLigneModel();
        $ordre = (int) $ligneModel->where('facture_id', $factureId)->countAllResults() + 1;

        $ligneModel->insert([
            'tenant_id'     => $this->tenantId(),
            'facture_id'    => $factureId,
            'designation'   => $this->request->getPost('designation'),
            'description'   => $this->request->getPost('description'),
            'quantite'      => $qte,
            'prix_unitaire' => $prix,
            'montant'       => $qte * $prix,
            'ordre'         => $ordre,
        ]);

        $model->recalculerMontants((int) $factureId);

        return redirect()
            ->to(site_url('factures/' . $factureId))
            ->with('success', 'Ligne ajoutée.');
    }

    public function deleteLigne($factureId, $ligneId)
    {
        $model = new FactureModel();
        $facture = $model->where('tenant_id', $this->tenantId())->find($factureId);

        if (! $facture) {
            return redirect()->to(site_url('factures'))->with('error', 'Facture introuvable.');
        }

        $ligneModel = new FactureLigneModel();
        $ligneModel
            ->where('facture_id', $factureId)
            ->where('id', $ligneId)
            ->delete();

        $model->recalculerMontants((int) $factureId);

        return redirect()
            ->to(site_url('factures/' . $factureId))
            ->with('success', 'Ligne supprimée.');
    }

    /**
     * Créer une facture depuis une réservation
     */
    public function fromReservation($reservationId){
        $tenantId = $this->tenantId();

        $reservationModel      = new ReservationModel();
        $factureModel          = new FactureModel();
        $ligneModel            = new FactureLigneModel();
        $reservationLigneModel = new \App\Models\ReservationLigneModel();

        $reservation = $reservationModel
            ->where('tenant_id', $tenantId)
            ->find($reservationId);

        if (! $reservation) {
            return redirect()
                ->to(site_url('reservations'))
                ->with('error', 'Réservation introuvable.');
        }

        // 1. Doublon AVANT la transaction
        $existante = $factureModel
            ->where('tenant_id', $tenantId)
            ->where('reservation_id', $reservationId)
            ->whereNotIn('statut', ['annulee'])
            ->first();

        if ($existante) {
            return redirect()
                ->to(site_url('factures/' . $existante['id']))
                ->with('error', 'Une facture existe déjà pour cette réservation.');
        }

        // 2. Optionnel : refuser si réservation annulée
        if (in_array($reservation['statut'] ?? '', ['annulee', 'annulée'], true)) {
            return redirect()
                ->to(site_url('reservations/' . $reservationId))
                ->with('error', 'Impossible de facturer une réservation annulée.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $numero = $factureModel->prochaineReference($tenantId);

        $factureId = $factureModel->insert([
            'tenant_id'      => $tenantId,
            'numero'         => $numero,
            'client_id'      => $reservation['client_id'],
            'reservation_id' => $reservation['id'],
            'cotation_id'    => $reservation['cotation_id'] ?? null,
            'date_facture'   => date('Y-m-d'),
            'date_echeance'  => date('Y-m-d', strtotime('+15 days')),
            'devise'         => $reservation['devise'] ?? 'MGA',
            'statut'         => 'emise',
            'notes_client'   => $reservation['notes_client'] ?? null,
            'created_by'     => session('user_id'),
            'montant_ht'     => 0,
            'montant_tva'    => 0,
            'montant_ttc'    => 0,
            'montant_paye'   => 0,
            'montant_restant'=> 0,
        ]);

        if (! $factureId) {
            $db->transRollback();
            return redirect()
                ->to(site_url('reservations/' . $reservationId))
                ->with('error', 'Impossible de créer la facture.')
                ->with('errors', $factureModel->errors());
        }

        $lignes = $reservationLigneModel
            ->where('reservation_id', $reservationId)
            ->orderBy('ordre', 'ASC')
            ->findAll();

        $ordre = 1;
        foreach ($lignes as $l) {
            $ligneModel->insert([
                'tenant_id'     => $tenantId,
                'facture_id'    => $factureId,
                'designation'   => $l['designation'] ?? 'Prestation',
                'description'   => $l['description'] ?? null,
                'quantite'      => $l['quantite'] ?? 1,
                'prix_unitaire' => $l['prix_unitaire'] ?? 0,
                'montant'       => $l['prix_total'] ?? (($l['quantite'] ?? 1) * ($l['prix_unitaire'] ?? 0)),
                'ordre'         => $ordre++,
            ]);
        }

        $factureModel->recalculerMontants((int) $factureId);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()
                ->to(site_url('reservations/' . $reservationId))
                ->with('error', 'Erreur lors de la création de la facture.');
        }

        return redirect()
            ->to(site_url('factures/' . $factureId))
            ->with('success', 'Facture ' . $numero . ' créée depuis la réservation.');
    }

    public function print($id)
    {
        $model = new FactureModel();
        $ligneModel = new FactureLigneModel();
        $deviseModel = new \App\Models\DeviseModel();

        $tenantId    = (int) session('tenant_id');

        $facture = $model
            ->select('
                factures.*,
                clients.nom AS client_nom,
                clients.prenom AS client_prenom,
                clients.entreprise AS client_entreprise,
                clients.email AS client_email,
                clients.telephone AS client_telephone,
                clients.adresse AS client_adresse
            ')
            ->join('clients', 'clients.id = factures.client_id', 'left')
            ->where('factures.id', $id)
            ->where('factures.tenant_id', $this->tenantId())
            ->first();

        if (! $facture) {
            return redirect()->to(site_url('factures'))->with('error', 'Facture introuvable.');
        }

        $deviseFacture = $facture['devise'] ?? 'MGA';

        $devises = $deviseModel
            ->where('tenant_id', $tenantId)
            ->where('actif', 1)
            ->orderBy('is_default', 'DESC')
            ->findAll();

        $totauxParDevise = [];

        foreach ($devises as $d) {
            $code = $d['code'];

            $totauxParDevise[$code] = [
                'symbole'     => $d['symbole'] ?? $code,
                'montant_ttc' => $deviseModel->convertir(
                    (float)($facture['montant_ttc'] ?? 0),
                    $deviseFacture,
                    $code,
                    $tenantId
                ),
            ];
        }

        return view('factures/print', [
            'title'   => 'Facture ' . $facture['numero'],
            'facture' => $facture,
            'lignes'  => $ligneModel->where('facture_id', $id)->orderBy('ordre')->findAll(),
            'totauxParDevise' => $totauxParDevise,
        ]);
    }
}