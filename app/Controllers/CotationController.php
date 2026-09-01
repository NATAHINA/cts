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
use App\Models\ReservationModel;
use App\Models\ReservationLigneModel;
use \App\Models\RestaurantModel;

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
            'restaurant'   => RestaurantModel::class,
            'vol'       => VolModel::class,
            'excursion' => ExcursionModel::class,
            'transfert' => TransfertModel::class,
            'croisiere' => CroisiereModel::class,
            'forfait'   => ForfaitModel::class,
            'circuit'   => CircuitModel::class,
        ];
    }



    public function index(){
    $model = new CotationModel();

    $items = $model
        ->select('
            cotations.*,
            clients.nom AS client_nom,
            clients.prenom AS client_prenom,
            clients.entreprise AS client_entreprise
        ')
        ->join(
            'clients',
            'clients.id = cotations.client_id',
            'left'
        )
        ->orderBy('cotations.created_at', 'DESC')
        ->findAll();

    return view('cotations/index', [
        'title' => 'Cotations',
        'items' => $items,
    ]);
}


    public function create(){
        $clientModel = new ClientModel();
        $destinationModel = new DestinationModel();

        return view('cotations/form', [
            'title'        => 'Nouvelle cotation',
            'cotation'     => null,
            'clients'      => $clientModel->orderBy('nom', 'ASC')->findAll(),
            'destinations' => $destinationModel->orderBy('nom', 'ASC')->findAll(),
        ]);
    }

    public function store()
{
    $model = new CotationModel();

    $tenantId = (int) session('tenant_id');

    if ($tenantId <= 0) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Agence non identifiée.');
    }

    $numero = $model->prochaineReference($tenantId);

    $id = $model->insert([
        'tenant_id'             => $tenantId,
        'numero'                => $numero,

        'client_id'             => $this->request->getPost('client_id') ?: null,
        'demande_id'            => $this->request->getPost('demande_id') ?: null,
        'destination_id'        => $this->request->getPost('destination_id') ?: null,

        'date_depart'           => $this->request->getPost('date_depart') ?: null,
        'date_retour'           => $this->request->getPost('date_retour') ?: null,

        'nb_adultes'            => (int) ($this->request->getPost('nb_adultes') ?: 0),
        'nb_enfants'            => (int) ($this->request->getPost('nb_enfants') ?: 0),
        'nb_bebes'              => (int) ($this->request->getPost('nb_bebes') ?: 0),

        'devise'                => $this->request->getPost('devise') ?: 'EUR',
        'taux_change'           => (float) ($this->request->getPost('taux_change') ?: 1),

        'cout_total'            => 0,
        'marge_montant'         => 0,
        'marge_pourcentage'     => (float) ($this->request->getPost('marge_pourcentage') ?: 0),

        'reduction_montant'     => 0,
        'reduction_pourcentage' => (float) ($this->request->getPost('reduction_pourcentage') ?: 0),

        'taxe_montant'          => 0,
        'prix_total'            => 0,
        'prix_par_personne'     => 0,

        'statut' => $this->request->getPost('statut') ?: 'brouillon',

        'date_validite'         => $this->request->getPost('date_validite') ?: null,

        'notes_client'          => $this->request->getPost('notes_client'),
        'notes_interne'         => $this->request->getPost('notes_interne'),

        'created_by'            => session('user_id') ?: null,
    ]);

    if (! $id) {
        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $model->errors());
    }

    return redirect()
        ->to(site_url('cotations/' . $id))
        ->with(
            'success',
            'Cotation ' . $numero . ' créée avec succès.'
        );
}

    public function show($id)
{
    $model = new CotationModel();
    $reservationModel = new ReservationModel();
    $deviseModel = new \App\Models\DeviseModel();

    $tenantId    = (int) session('tenant_id');

    $cotation = $model
        ->select('
            cotations.*,
            clients.nom AS client_nom,
            clients.prenom AS client_prenom,
            clients.entreprise AS client_entreprise,
            clients.email AS client_email,
            clients.telephone AS client_telephone,
            clients.adresse AS client_adresse
        ')
        ->join(
            'clients',
            'clients.id = cotations.client_id',
            'left'
        )
        ->where('cotations.id', $id)
        ->first();

    if (! $cotation) {
        return redirect()
            ->to(site_url('cotations'))
            ->with('error', 'Cotation introuvable.');
    }
    

    $ligneModel = new CotationLigneModel();

    $catalogue = [];

    foreach ($this->serviceModels() as $type => $modelClass) {

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

            $catalogue[$type] = array_map(
                static function ($row) {
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
                },
                $rows
            );
        } catch (\Throwable $e) {
            $catalogue[$type] = [];
            log_message('error', 'Catalogue cotation ' . $type . ' : ' . $e->getMessage());
        }
    }

    $reservationExistante = $reservationModel
        ->where('cotation_id', $id)
        ->first();

    $deviseCotation = $cotation['devise'] ?? 'MGA';

    $devises = $deviseModel
        ->where('tenant_id', $tenantId)
        ->where('actif', 1)
        ->orderBy('is_default', 'DESC')
        ->findAll();

    $totauxParDevise = [];

    foreach ($devises as $d) {
        $code = $d['code'];

        $totauxParDevise[$code] = [
            'symbole'    => $d['symbole'] ?? $code,
            'cout_total' => $deviseModel->convertir(
                (float)($cotation['cout_total'] ?? 0),
                $deviseCotation,
                $code,
                $tenantId
            ),
            'prix_total' => $deviseModel->convertir(
                (float)($cotation['prix_total'] ?? 0),
                $deviseCotation,
                $code,
                $tenantId
            ),
            'marge' => $deviseModel->convertir(
                (float)($cotation['marge_montant'] ?? 0),
                $deviseCotation,
                $code,
                $tenantId
            ),
        ];
    }

    return view('cotations/show', [
        'title'                 => 'Cotation ' . $cotation['numero'],
        'cotation'              => $cotation,
        'lignes'                => $ligneModel
            ->where('cotation_id', $id)
            ->orderBy('ordre', 'ASC')
            ->findAll(),
        'catalogue'             => $catalogue,
        'reservationExistante'  => $reservationExistante,
        'totauxParDevise' => $totauxParDevise,
        'deviseCotation'  => $deviseCotation,
    ]);
}

    public function print($id){
        $model      = new CotationModel();
        $ligneModel = new CotationLigneModel();
        $deviseModel = new \App\Models\DeviseModel();

        $tenantId    = (int) session('tenant_id');

        $cotation = $model
            ->select('
                cotations.*,
                clients.nom AS client_nom,
                clients.prenom AS client_prenom,
                clients.entreprise AS client_entreprise,
                clients.email AS client_email,
                clients.telephone AS client_telephone,
                clients.adresse AS client_adresse
            ')
            ->join('clients', 'clients.id = cotations.client_id', 'left')
            ->where('cotations.id', $id)
            ->first();

        if (! $cotation) {
            return redirect()
                ->to(site_url('cotations'))
                ->with('error', 'Cotation introuvable.');
        }

        $deviseCotation = $cotation['devise'] ?? 'MGA';

        $devises = $deviseModel
            ->where('tenant_id', $tenantId)
            ->where('actif', 1)
            ->orderBy('is_default', 'DESC')
            ->findAll();

        $totauxParDevise = [];

        foreach ($devises as $d) {
            $code = $d['code'];

            $totauxParDevise[$code] = [
                'symbole'    => $d['symbole'] ?? $code,
                'cout_total' => $deviseModel->convertir(
                    (float)($cotation['cout_total'] ?? 0),
                    $deviseCotation,
                    $code,
                    $tenantId
                ),
                'prix_total' => $deviseModel->convertir(
                    (float)($cotation['prix_total'] ?? 0),
                    $deviseCotation,
                    $code,
                    $tenantId
                ),
                'marge' => $deviseModel->convertir(
                    (float)($cotation['marge_montant'] ?? 0),
                    $deviseCotation,
                    $code,
                    $tenantId
                ),
            ];
        }

        return view('cotations/print', [
            'title'    => 'Cotation ' . $cotation['numero'],
            'cotation' => $cotation,

            'lignes' => $ligneModel
                ->where('cotation_id', $id)
                ->orderBy('ordre', 'ASC')
                ->findAll(),
            'totauxParDevise' => $totauxParDevise,
            'deviseCotation'  => $deviseCotation,
        ]);
    }

        public function edit($id)
    {
        $model = new CotationModel();

        $cotation = $model->find($id);

        if (! $cotation) {
            return redirect()
                ->to('/cotations')
                ->with('error', 'Cotation introuvable.');
        }

        $clientModel = new ClientModel();
        $destinationModel = new DestinationModel();

        return view('cotations/form', [
            'title'        => 'Modifier — ' . $cotation['numero'],
            'cotation'     => $cotation,
            'clients'      => $clientModel->orderBy('nom', 'ASC')->findAll(),
            'destinations' => $destinationModel->orderBy('nom', 'ASC')->findAll(),
        ]);
    }

    public function update($id){
        $model = new CotationModel();

        $cotation = $model->find($id);

        if (! $cotation) {
            return redirect()
                ->to(site_url('cotations'))
                ->with('error', 'Cotation introuvable.');
        }

        $data = [
            'client_id'             => $this->request->getPost('client_id') ?: null,
            'destination_id'        => $this->request->getPost('destination_id') ?: null,

            'date_depart'           => $this->request->getPost('date_depart') ?: null,
            'date_retour'           => $this->request->getPost('date_retour') ?: null,

            'nb_adultes'            => (int) ($this->request->getPost('nb_adultes') ?: 0),
            'nb_enfants'            => (int) ($this->request->getPost('nb_enfants') ?: 0),
            'nb_bebes'              => (int) ($this->request->getPost('nb_bebes') ?: 0),

            'devise'                => $this->request->getPost('devise') ?: 'EUR',
            'taux_change'           => (float) ($this->request->getPost('taux_change') ?: 1),
            
            'statut'                => $this->request->getPost('statut') ?: 'brouillon',

            'marge_pourcentage'     => (float) ($this->request->getPost('marge_pourcentage') ?: 0),

            'reduction_pourcentage' => (float) ($this->request->getPost('reduction_pourcentage') ?: 0),

            'taxe_montant'          => (float) ($this->request->getPost('taxe_montant') ?: 0),

            'date_validite'         => $this->request->getPost('date_validite') ?: null,

            'notes_client'          => $this->request->getPost('notes_client'),
            'notes_interne'         => $this->request->getPost('notes_interne'),
        ];

        if (! $model->update($id, $data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        // Recalcul après modification des paramètres commerciaux
        $model->recalculerMontants((int) $id);

        return redirect()
            ->to(site_url('cotations/' . $id))
            ->with('success', 'Cotation mise à jour.');
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
    $model = new CotationModel();

    $cotation = $model->find($cotationId);

    if (! $cotation) {
        return redirect()
            ->to('/cotations')
            ->with('error', 'Cotation introuvable.');
    }

    $ligneModel = new CotationLigneModel();


    $quantite = (float) (
        $this->request->getPost('quantite') ?: 1
    );


    $coutUnitaire = (float) (
        $this->request->getPost('cout_unitaire') ?: 0
    );


    // $margePourcentage = (float) (
    //     $this->request->getPost('marge_pourcentage') ?: 0
    // );

    $margePourcentage = (float) (
        $this->request->getPost('marge_pourcentage')
        ?: ($cotation['marge_pourcentage'] ?? 0)
    );


    /*
     * Calcul du coût total
     */
    $coutTotal = $quantite * $coutUnitaire;


    /*
     * Calcul de la marge
     */
    $margeMontant =
        $coutTotal * $margePourcentage / 100;


    /*
     * Prix unitaire de vente
     */
    $prixUnitaire =
        $coutUnitaire
        +
        ($coutUnitaire * $margePourcentage / 100);


    /*
     * Prix total de la ligne
     */
    $prixTotal =
        $quantite * $prixUnitaire;


    $ordre = (int) $ligneModel
        ->where('cotation_id', $cotationId)
        ->countAllResults() + 1;


    $ligneModel->insert([

        'cotation_id'       => $cotationId,
        'type_prestation'   => $this->request->getPost('type_prestation'),
        'prestation_id'     =>
            $this->request->getPost('prestation_id') ?: null,
        'fournisseur_id'    =>
            $this->request->getPost('fournisseur_id') ?: null,
        'designation'       =>
            $this->request->getPost('designation'),
        'description'       =>
            $this->request->getPost('description'),
        'quantite'          => $quantite,
        'cout_unitaire'     => $coutUnitaire,
        'cout_total'        => $coutTotal,
        'marge_pourcentage' => $margePourcentage,
        'marge_montant'     => $margeMontant,
        'prix_unitaire'     => $prixUnitaire,
        'prix_total'        => $prixTotal,
        'devise'            => $cotation['devise'],
        'ordre'             => $ordre,
    ]);


    /*
     * Recalculer la cotation entière
     */
    $model->recalculerMontants((int) $cotationId);


    return redirect()
        ->to('/cotations/' . $cotationId)
        ->with('success', 'Prestation ajoutée avec succès.');
}

    public function deleteLigne($cotationId, $ligneId)
    {
        $model = new CotationModel();

        $cotation = $model->find($cotationId);

        if (! $cotation) {
            return redirect()
                ->to('/cotations')
                ->with('error', 'Cotation introuvable.');
        }

        $ligneModel = new CotationLigneModel();

        $ligne = $ligneModel
            ->where('cotation_id', $cotationId)
            ->where('id', $ligneId)
            ->first();

        if (! $ligne) {
            return redirect()
                ->to('/cotations/' . $cotationId)
                ->with('error', 'Prestation introuvable.');
        }

        $ligneModel->delete($ligneId);


        // Recalcul après suppression
        $model->recalculerMontants((int) $cotationId);


        return redirect()
            ->to('/cotations/' . $cotationId)
            ->with('success', 'Prestation retirée.');
    }

    public function updateLigne($cotationId, $ligneId)
{
    $cotationModel = new CotationModel();
    $ligneModel    = new CotationLigneModel();

    // Vérifier la cotation
    $cotation = $cotationModel->find($cotationId);

    if (! $cotation) {
        return redirect()
            ->to('/cotations')
            ->with('error', 'Cotation introuvable.');
    }

    // Vérifier que la ligne appartient bien à la cotation
    $ligne = $ligneModel
        ->where('cotation_id', $cotationId)
        ->where('id', $ligneId)
        ->first();

    if (! $ligne) {
        return redirect()
            ->to('/cotations/' . $cotationId)
            ->with('error', 'Prestation introuvable.');
    }

    $quantite = (float) (
        $this->request->getPost('quantite') ?: 1
    );

    $coutUnitaire = (float) (
        $this->request->getPost('cout_unitaire') ?: 0
    );

    $margePourcentage = (float) (
        $this->request->getPost('marge_pourcentage') ?: 0
    );

    // Calcul coût total
    $coutTotal = $quantite * $coutUnitaire;

    // Calcul marge
    $margeMontant =
        $coutTotal * $margePourcentage / 100;

    // Calcul prix total
    $prixTotal =
        $coutTotal + $margeMontant;

    // Prix unitaire de vente
    $prixUnitaire = $quantite > 0
        ? $prixTotal / $quantite
        : 0;

    $ligneModel->update($ligneId, [

        'type_prestation' =>
            $this->request->getPost('type_prestation'),

        'prestation_id' =>
            $this->request->getPost('prestation_id') ?: null,

        'fournisseur_id' =>
            $this->request->getPost('fournisseur_id') ?: null,

        'designation' =>
            $this->request->getPost('designation'),

        'description' =>
            $this->request->getPost('description') ?: null,

        'quantite' =>
            $quantite,

        'cout_unitaire' =>
            $coutUnitaire,

        'cout_total' =>
            $coutTotal,

        'marge_pourcentage' =>
            $margePourcentage,

        'marge_montant' =>
            $margeMontant,

        'prix_unitaire' =>
            $prixUnitaire,

        'prix_total' =>
            $prixTotal,

        'devise' =>
            $this->request->getPost('devise')
            ?: $cotation['devise'],

    ]);

    // Recalcul de la cotation entière
    $cotationModel->recalculerMontants(
        (int) $cotationId
    );

    return redirect()
        ->to('/cotations/' . $cotationId)
        ->with(
            'success',
            'Prestation modifiée avec succès.'
        );
}

public function recalculer($id)
{
    $model = new CotationModel();

    $cotation = $model->find($id);

    if (! $cotation) {
        return redirect()
            ->to('/cotations')
            ->with('error', 'Cotation introuvable.');
    }

    $model->recalculerMontants((int) $id);

    return redirect()
        ->to('/cotations/' . $id)
        ->with(
            'success',
            'Les montants ont été recalculés.'
        );
}

public function duplicate($id)
{
    $cotationModel = new CotationModel();
    $ligneModel    = new CotationLigneModel();

    $ancienne = $cotationModel->find($id);

    if (! $ancienne) {
        return redirect()
            ->to('/cotations')
            ->with('error', 'Cotation introuvable.');
    }

    $tenantId = (int) session('tenant_id');

    $nouveauNumero = $cotationModel->prochaineReference(
        $tenantId
    );

    // Préparer la nouvelle cotation
    $nouvelle = $ancienne;

    unset(
        $nouvelle['id'],
        $nouvelle['created_at'],
        $nouvelle['updated_at']
    );

    $nouvelle['tenant_id'] = $tenantId;
    $nouvelle['numero']    = $nouveauNumero;
    $nouvelle['statut']    = 'brouillon';

    $nouvelleId = $cotationModel->insert($nouvelle);

    // Copier les lignes
    $lignes = $ligneModel
        ->where('cotation_id', $id)
        ->findAll();

    foreach ($lignes as $ligne) {

        unset(
            $ligne['id'],
            $ligne['created_at'],
            $ligne['updated_at']
        );

        $ligne['tenant_id']   = $tenantId;
        $ligne['cotation_id'] = $nouvelleId;

        $ligneModel->insert($ligne);
    }

    $cotationModel->recalculerMontants(
        (int) $nouvelleId
    );

    return redirect()
        ->to('/cotations/' . $nouvelleId)
        ->with(
            'success',
            'La cotation a été dupliquée avec succès.'
        );
}

public function duplicateLigne($cotationId, $ligneId)
{
    $cotationModel = new CotationModel();
    $ligneModel    = new CotationLigneModel();

    // Vérifier la cotation
    $cotation = $cotationModel->find($cotationId);

    if (! $cotation) {
        return redirect()
            ->to('/cotations')
            ->with('error', 'Cotation introuvable.');
    }

    // Vérifier que la ligne appartient à cette cotation
    $ligne = $ligneModel
        ->where('id', $ligneId)
        ->where('cotation_id', $cotationId)
        ->first();

    if (! $ligne) {
        return redirect()
            ->to('/cotations/' . $cotationId)
            ->with('error', 'Prestation introuvable.');
    }

    // Déterminer le prochain ordre
    $dernierOrdre = $ligneModel
        ->where('cotation_id', $cotationId)
        ->selectMax('ordre', 'max_ordre')
        ->first();

    $nouvelOrdre = ((int) ($dernierOrdre['max_ordre'] ?? 0)) + 1;

    // Créer une copie de la prestation
    $nouvelleLigne = [
        'tenant_id'          => $ligne['tenant_id'] ?? session('tenant_id'),
        'cotation_id'        => $cotationId,

        'type_prestation'    => $ligne['type_prestation'],
        'prestation_id'      => $ligne['prestation_id'] ?? null,
        'fournisseur_id'     => $ligne['fournisseur_id'] ?? null,

        'designation'        => $ligne['designation'],
        'description'        => $ligne['description'] ?? null,

        'quantite'           => $ligne['quantite'],
        'cout_unitaire'      => $ligne['cout_unitaire'],
        'cout_total'         => $ligne['cout_total'],

        'marge_pourcentage'  => $ligne['marge_pourcentage'] ?? 0,
        'marge_montant'      => $ligne['marge_montant'] ?? 0,

        'prix_unitaire'      => $ligne['prix_unitaire'],
        'prix_total'         => $ligne['prix_total'],

        'devise'             => $ligne['devise'] ?? $cotation['devise'] ?? null,

        'ordre'              => $nouvelOrdre,
    ];

    $ligneModel->insert($nouvelleLigne);

    // Recalculer les montants de la cotation
    $cotationModel->recalculerMontants((int) $cotationId);

    return redirect()
        ->to('/cotations/' . $cotationId)
        ->with(
            'success',
            'Prestation dupliquée avec succès.'
        );
}

public function convertToReservation($id)
{
    $cotationModel = new CotationModel();
    $ligneModel    = new CotationLigneModel();

    $reservationModel = new ReservationModel();
    $reservationLigneModel = new ReservationLigneModel();

    $tenantId = (int) session('tenant_id');

    $cotation = $cotationModel->find($id);

    if (! $cotation) {
        return redirect()
            ->to('/cotations')
            ->with(
                'error',
                'Cotation introuvable.'
            );
    }


    if (($cotation['statut'] ?? '') !== 'acceptee') {
        return redirect()
            ->to('/cotations/' . $id)
            ->with(
                'error',
                'Seule une cotation acceptée peut être convertie en réservation.'
            );
    }

    $reservationExistante = $reservationModel
        ->where('cotation_id', $id)
        ->first();

    if ($reservationExistante) {
        return redirect()
            ->to('/reservations/' . $reservationExistante['id'])
            ->with(
                'error',
                'Cette cotation a déjà été convertie en réservation.'
            );
    }

    $lignes = $ligneModel
        ->where('cotation_id', $id)
        ->orderBy('ordre', 'ASC')
        ->findAll();

    if (empty($lignes)) {
        return redirect()
            ->to('/cotations/' . $id)
            ->with(
                'error',
                'Impossible de créer la réservation : aucune prestation n\'a été ajoutée.'
            );
    }


    $db = \Config\Database::connect();

    $db->transStart();


    $numeroReservation = $reservationModel
        ->prochainNumero($tenantId);

    $reservationId = $reservationModel->insert([

        'tenant_id'       => $tenantId,
        'numero'          => $numeroReservation,
        'cotation_id'     => $cotation['id'],
        'client_id'       => $cotation['client_id'],
        'destination_id'  => $cotation['destination_id'] ?? null,
        'date_depart'     => $cotation['date_depart'] ?? null,
        'date_retour'     => $cotation['date_retour'] ?? null,
        'nb_adultes'      => $cotation['nb_adultes'] ?? 0,
        'nb_enfants'      => $cotation['nb_enfants'] ?? 0,
        'nb_bebes'        => $cotation['nb_bebes'] ?? 0,
        'devise'          => $cotation['devise'] ?? null,
        'montant_total'   => $cotation['prix_total'] ?? 0,
        'statut'          => 'confirmee',
        'notes_client'    => $cotation['notes_client'] ?? null,
        'notes_interne'   => $cotation['notes_interne'] ?? null,
        'created_by'      => session('user_id'),

    ], true);


    foreach ($lignes as $ligne) {

        $reservationLigneModel->insert([

            'tenant_id'        => $tenantId,
            'reservation_id'   => $reservationId,
            'type_prestation'  => $ligne['type_prestation'],
            'prestation_id'    => $ligne['prestation_id'] ?? null,
            'fournisseur_id'   => $ligne['fournisseur_id'] ?? null,
            'designation'      => $ligne['designation'],
            'description'      => $ligne['description'] ?? null,
            'quantite'         => $ligne['quantite'],
            'cout_unitaire'    => $ligne['cout_unitaire'] ?? 0,
            'cout_total'       => $ligne['cout_total'] ?? 0,
            'prix_unitaire'    => $ligne['prix_unitaire'] ?? 0,
            'prix_total'       => $ligne['prix_total'] ?? 0,
            'devise'           => $ligne['devise'] ?? $cotation['devise'],
            'ordre'            => $ligne['ordre'],

        ]);

    }

    $db->transComplete();


    if ($db->transStatus() === false) {

        return redirect()
            ->to('/cotations/' . $id)
            ->with(
                'error',
                'Une erreur est survenue lors de la création de la réservation.'
            );

    }

    return redirect()
        ->to('/reservations/' . $reservationId)
        ->with(
            'success',
            'La cotation a été convertie en réservation avec succès.'
        );
}

}
