<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReservationModel;
use App\Models\ReservationLigneModel;
use App\Models\ClientModel;
use App\Models\CotationModel;
use App\Models\DestinationModel;
use App\Models\PlanningDepartModel;

class ReservationController extends BaseController
{
    protected $model;
    protected $ligneModel;
    protected $clientModel;
    protected $cotationModel;
    protected $destinationModel;
    protected $planningModel;

    public function __construct()
    {
        $this->model            = new ReservationModel();
        $this->ligneModel       = new ReservationLigneModel();
        $this->clientModel      = new ClientModel();
        $this->cotationModel    = new CotationModel();
        $this->destinationModel = new DestinationModel();
        $this->planningModel    = new PlanningDepartModel();
    }

    private function tenantId(): int
    {
        return (int) session('tenant_id');
    }

    private function getReservation(int $id): ?array
    {
        return $this->model
            ->where('id', $id)
            ->where('tenant_id', $this->tenantId())
            ->first();
    }

    /**
     * Liste
     */
    public function index()
    {
        $reservations = $this->model
            ->select('
                reservations.*,
                clients.nom AS client_nom,
                clients.prenom AS client_prenom,
                destinations.nom AS destination_nom
            ')
            ->join(
                'clients',
                'clients.id = reservations.client_id',
                'left'
            )
            ->join(
                'destinations',
                'destinations.id = reservations.destination_id',
                'left'
            )
            ->where(
                'reservations.tenant_id',
                $this->tenantId()
            )
            ->orderBy('reservations.id', 'DESC')
            ->findAll();

        return view('reservations/index', [
            'title'        => 'Réservations',
            'reservations' => $reservations,
        ]);
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        $tenantId = $this->tenantId();

        return view('reservations/form', [
            'title'        => 'Nouvelle réservation',
            'clients'      => $this->clientModel
                ->where('tenant_id', $tenantId)
                ->orderBy('nom', 'ASC')
                ->findAll(),

            'cotations'    => $this->cotationModel
                ->where('tenant_id', $tenantId)
                ->whereIn('statut', ['acceptée', 'acceptee'])
                ->orderBy('id', 'DESC')
                ->findAll(),

            'destinations' => $this->destinationModel
                ->where('tenant_id', $tenantId)
                ->orderBy('nom', 'ASC')
                ->findAll(),

            'lignes'       => [],
            'catalogues'    => $this->catalogues(),
        ]);
    }

    /**
     * Création
     */
    public function store()
    {
        
        $tenantId = $this->tenantId();
        $clientId = (int) $this->request->getPost('client_id');
        $cotationId = $this->request->getPost('cotation_id');
        $planningId = (int) (
            $this->request->getPost('planning_id') ?: 0
        );

        /**
         * CLIENT
         */
        $client = $this->clientModel
            ->where('id', $clientId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$client) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Le client sélectionné est introuvable.'
                );
        }

        /**
         * PLANNING
         */
        $planning = $this->planningModel
            ->where('id', $planningId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$planning) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Le planning de départ sélectionné est introuvable.'
                );
        }

        /**
         * COTATION
         */
        if (!empty($cotationId)) {

            $cotation = $this->cotationModel
                ->where('id', (int) $cotationId)
                ->where('tenant_id', $tenantId)
                ->first();

            if (!$cotation) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'La cotation sélectionnée est introuvable.'
                    );
            }
        }

        /**
         * VOYAGEURS
         */
        $nbAdultes = max(
            0,
            (int) $this->request->getPost('nb_adultes')
        );

        $nbEnfants = max(
            0,
            (int) $this->request->getPost('nb_enfants')
        );

        $nbBebes = max(
            0,
            (int) $this->request->getPost('nb_bebes')
        );

        $nbVoyageurs =
            $nbAdultes +
            $nbEnfants +
            $nbBebes;

        /**
         * PLACES DISPONIBLES
         */
        if (!$this->planningModel->hasEnoughPlaces(
            $planningId,
            $tenantId,
            $nbVoyageurs
        )) {

            $placesDisponibles =
                $this->planningModel->getPlacesDisponibles(
                    $planningId,
                    $tenantId
                );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Ce départ ne dispose plus que de '
                    . $placesDisponibles
                    . ' place(s) disponible(s).'
                );
        }

        /**
         * RESERVATION
         */
        $data = [
            'tenant_id'      => $tenantId,
            'numero'         =>
                $this->model->prochainNumero($tenantId),
            'planning_id'    => $planningId,
            'cotation_id'    =>
                !empty($cotationId)
                    ? (int) $cotationId
                    : null,
            'client_id'      => $clientId,
            'destination_id' =>
                $this->request->getPost('destination_id')
                    ?: null,
            'date_depart' =>
                $planning['date_depart'] ?? null,
            'date_retour' =>
                $planning['date_retour'] ?? null,
            'nb_adultes'     => $nbAdultes,
            'nb_enfants'     => $nbEnfants,
            'nb_bebes'       => $nbBebes,
            'devise'         =>
                $this->request->getPost('devise')
                    ?: 'MGA',
            'montant_total'  => 0,
            'statut'         =>
                $this->request->getPost('statut')
                    ?: 'en_attente',
            'notes_client'   =>
                $this->request->getPost('notes_client')
                    ?: null,
            'notes_interne'  =>
                $this->request->getPost('notes_interne')
                    ?: null,
            'created_by'     =>
                session('user_id')
                    ?: null,
        ];

        $id = $this->model->insert(
            $data,
            true
        );

        if (!$id) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible de créer la réservation.'
                );
        }

        /**
         * LIGNES
         */
        $this->saveLignes(
            $id,
            $tenantId
        );

        /**
         * TOTAL
         */
        $this->model->recalculerMontant($id);

        $this->planningModel->recalculerPlacesVendues(
            $planningId,
            $tenantId
        );

        $this->planningModel->updateAutomaticStatus(
            $planningId,
            $tenantId
        );

        return redirect()
            ->to(site_url('reservations/' . $id))
            ->with(
                'success',
                'Réservation créée avec succès.'
            );
    }

    /**
     * Affichage
     */
    public function show($id)
    {
        $deviseModel = new \App\Models\DeviseModel();
        $tenantId    = $this->tenantId();

        $reservation = $this->model
            ->select('
                reservations.*,
                clients.nom AS client_nom,
                clients.prenom AS client_prenom,
                destinations.nom AS destination_nom,
                cotations.numero AS cotation_numero
            ')
            ->join(
                'clients',
                'clients.id = reservations.client_id',
                'left'
            )
            ->join(
                'destinations',
                'destinations.id = reservations.destination_id',
                'left'
            )
            ->join(
                'cotations',
                'cotations.id = reservations.cotation_id',
                'left'
            )
            ->where(
                'reservations.id',
                $id
            )
            ->where(
                'reservations.tenant_id',
                $this->tenantId()
            )
            ->first();

        if (!$reservation) {
            return redirect()
                ->to(site_url('reservations'))
                ->with(
                    'error',
                    'Réservation introuvable.'
                );
        }

        $lignes = $this->ligneModel
            ->getByReservation(
                (int) $id,
                $this->tenantId()
            );

        $factureModel =
            new \App\Models\FactureModel();

        $factureExistante = $factureModel
            ->where(
                'tenant_id',
                $this->tenantId()
            )
            ->where(
                'reservation_id',
                $id
            )
            ->whereNotIn(
                'statut',
                ['annulee']
            )
            ->orderBy('id', 'DESC')
            ->first();
            
        $deviseReservation = $reservation['devise'] ?? 'MGA';

        $devises = $deviseModel
            ->where('tenant_id', $tenantId)
            ->orderBy('is_default', 'DESC')
            ->findAll();

        $totauxParDevise = [];

        foreach ($devises as $d) {
            $code = $d['code'];

            $totauxParDevise[$code] = [
                'symbole'       => $d['symbole'] ?? $code,
                'montant_total' => $deviseModel->convertir(
                    (float)($reservation['montant_total'] ?? 0),
                    $deviseReservation,
                    $code,
                    $tenantId
                ),
            ];
        }

        return view('reservations/show', [
            'title' =>
                'Réservation ' .
                $reservation['numero'],
            'reservation' =>
                $reservation,
            'lignes' =>
                $lignes,
            'factureExistante' =>
                $factureExistante,
            'totauxParDevise'    => $totauxParDevise,
            'deviseReservation'  => $deviseReservation,
        ]);
    }

    /**
     * Edition
     */
    public function edit($id)
    {
        $tenantId = $this->tenantId();

        $reservation =
            $this->getReservation(
                (int) $id
            );

        if (!$reservation) {
            return redirect()
                ->to(site_url('reservations'))
                ->with(
                    'error',
                    'Réservation introuvable.'
                );
        }

        $lignes =
            $this->ligneModel
                ->getByReservation(
                    (int) $id,
                    $tenantId
                );

        return view('reservations/form', [
            'title' =>
                'Modifier la réservation',

            'reservation' =>
                $reservation,

            'clients' =>
                $this->clientModel
                    ->where(
                        'tenant_id',
                        $tenantId
                    )
                    ->orderBy(
                        'nom',
                        'ASC'
                    )
                    ->findAll(),

            'cotations' =>
                $this->cotationModel
                    ->where(
                        'tenant_id',
                        $tenantId
                    )
                    ->orderBy(
                        'id',
                        'DESC'
                    )
                    ->findAll(),

            'destinations' =>
                $this->destinationModel
                    ->where(
                        'tenant_id',
                        $tenantId
                    )
                    ->orderBy(
                        'nom',
                        'ASC'
                    )
                    ->findAll(),

            'lignes' =>
                $lignes,
            'catalogues'    => $this->catalogues(),
        ]);
    }

    /**
     * Mise à jour
     */
    public function update($id)
    {
        $tenantId =
            $this->tenantId();

        $reservation =
            $this->getReservation(
                (int) $id
            );

        if (!$reservation) {
            return redirect()
                ->to(site_url('reservations'))
                ->with(
                    'error',
                    'Réservation introuvable.'
                );
        }

        $clientId =
            (int) $this->request
                ->getPost('client_id');

        $ancienPlanningId  = (int) ($reservation['planning_id'] ?? 0);

        $cotationId =
            $this->request
                ->getPost('cotation_id');

        $planningId =
            (int) $this->request->getPost('planning_id');
        /**
         * CLIENT
         */
        $client = $this->clientModel
            ->where('id', $clientId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$client) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Le client sélectionné est invalide.'
                );
        }

        /**
         * PLANNING
         */
        $planning = $this->planningModel
            ->where('id', $planningId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$planning) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Le planning sélectionné est invalide.'
                );
        }
        

        /**
         * COTATION
         */
        if (!empty($cotationId)) {

            $cotation =
                $this->cotationModel
                    ->where(
                        'id',
                        (int) $cotationId
                    )
                    ->where(
                        'tenant_id',
                        $tenantId
                    )
                    ->first();

            if (!$cotation) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'La cotation sélectionnée est invalide.'
                    );
            }
        }

        /**
         * VOYAGEURS
         */
        $nbAdultes =
            max(
                0,
                (int) $this->request
                    ->getPost('nb_adultes')
            );
        $nbEnfants =
            max(
                0,
                (int) $this->request
                    ->getPost('nb_enfants')
            );
        $nbBebes =
            max(
                0,
                (int) $this->request
                    ->getPost('nb_bebes')
            );
        $nbVoyageurs =
            $nbAdultes +
            $nbEnfants +
            $nbBebes;

        if (!$this->planningModel->hasEnoughPlaces(
            $planningId,
            $tenantId,
            $nbVoyageurs
        )) {
            $placesDisponibles =
                $this->planningModel
                    ->getPlacesDisponibles(
                        $planningId,
                        $tenantId
                    );
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Il ne reste que '
                    . $placesDisponibles
                    . ' place(s) disponible(s).'
                );
        }

        /**
         * UPDATE
         */
        $data = [
            'planning_id' =>
                $planningId,
            'client_id' =>
                $clientId,
            'cotation_id' =>
                !empty($cotationId)
                    ? (int) $cotationId
                    : null,
            'destination_id' =>
                $this->request
                    ->getPost('destination_id')
                    ?: null,
            'date_depart' => $planning['date_depart'] ?? null,
            'date_retour' => $planning['date_retour'] ?? null,
            'nb_adultes' =>
                $nbAdultes,
            'nb_enfants' =>
                $nbEnfants,
            'nb_bebes' =>
                $nbBebes,
            'devise' =>
                $this->request
                    ->getPost('devise')
                    ?: 'MGA',

            'statut' =>
                $this->request
                    ->getPost('statut')
                    ?: 'en_attente',

            'notes_client' =>
                $this->request
                    ->getPost('notes_client')
                    ?: null,

            'notes_interne' =>
                $this->request
                    ->getPost('notes_interne')
                    ?: null,
        ];

        $this->model->update(
            $id,
            $data
        );

        /**
         * LIGNES
         */
        $this->ligneModel
            ->deleteByReservation(
                (int) $id
            );

        $this->saveLignes(
            (int) $id,
            $tenantId
        );

        /**
         * TOTAL
         */
        $this->model
            ->recalculerMontant(
                (int) $id
            );
        
        if ($ancienPlanningId > 0) {
            $this->planningModel
                ->recalculerPlacesVendues(
                    $ancienPlanningId,
                    $tenantId
                );
        }

        if (
            $planningId > 0 &&
            $planningId !== $ancienPlanningId
        ) {

            $this->planningModel
                ->recalculerPlacesVendues(
                    $planningId,
                    $tenantId
                );
        }

        $this->planningModel->updateAutomaticStatus(
            $planningId,
            $tenantId
        );

        return redirect()
            ->to(
                site_url(
                    'reservations/' . $id
                )
            )
            ->with(
                'success',
                'Réservation mise à jour avec succès.'
            );
    }

    /**
     * Suppression
     */
    public function delete($id)
    {
        $reservation =
            $this->getReservation(
                (int) $id
            );

        if (!$reservation) {
            return redirect()
                ->to(
                    site_url('reservations')
                )
                ->with(
                    'error',
                    'Réservation introuvable.'
                );
        }

        $this->ligneModel
            ->deleteByReservation(
                (int) $id
            );

        $this->model
            ->delete($id);

        return redirect()
            ->to(
                site_url('reservations')
            )
            ->with(
                'success',
                'Réservation supprimée avec succès.'
            );
    }

    /**
     * =========================================================
     * API : détails d'un planning
     * =========================================================
     */
    public function planning($planningId)
    {
        $tenantId =
            $this->tenantId();

        $planning =
            $this->planningModel
                ->where(
                    'id',
                    (int) $planningId
                )
                ->where(
                    'tenant_id',
                    $tenantId
                )
                ->first();

        if (!$planning) {
            return $this->response
                ->setJSON([
                    'success' => false,
                    'message' =>
                        'Planning introuvable.'
                ]);
        }

        /**
         * IMPORTANT :
         *
         * Cette méthode doit retourner les prestations
         * associées au planning.
         *
         * Si ton PlanningDepartModel possède déjà une méthode
         * getPrestations(), utilise-la ici.
         */
        $prestations = [];

        if (method_exists(
            $this->planningModel,
            'getPrestations'
        )) {
            $prestations =
                $this->planningModel
                    ->getPrestations(
                        (int) $planningId,
                        $tenantId
                    );
        }

        return $this->response
            ->setJSON([
                'success' => true,

                'data' => [
                    'id' =>
                        $planning['id'],

                    'date_depart' =>
                        $planning['date_depart']
                            ?? null,

                    'heure_depart' =>
                        $planning['heure_depart']
                            ?? null,

                    'date_retour' =>
                        $planning['date_retour']
                            ?? null,

                    'heure_retour' =>
                        $planning['heure_retour']
                            ?? null,

                    'places_disponibles' =>
                        $this->planningModel
                            ->getPlacesDisponibles(
                                (int) $planningId,
                                $tenantId
                            ),

                    'prix' =>
                        $planning['prix']
                            ?? 0,

                    'devise' =>
                        $planning['devise']
                            ?? 'MGA',

                    'statut' =>
                        $planning['statut']
                            ?? '-',

                    'prestations' =>
                        $prestations,
                ],
            ]);
    }

    /**
     * =========================================================
     * Enregistrement des lignes
     * =========================================================
     */
    private function saveLignes(
        int $reservationId,
        int $tenantId
    ): void {

        $lignes =
            $this->request
                ->getPost('lignes')
                ?? [];

        if (!is_array($lignes)) {
            return;
        }

        $ordre = 1;

        foreach ($lignes as $ligne) {

            if (!is_array($ligne)) {
                continue;
            }

            $designation =
                trim(
                    $ligne['designation']
                        ?? ''
                );

            if ($designation === '') {
                continue;
            }

            $quantite =
                max(
                    1,
                    (int) (
                        $ligne['quantite']
                            ?? 1
                    )
                );

            $prixUnitaire =
                (float) (
                    $ligne['prix_unitaire']
                        ?? 0
                );

            $coutUnitaire =
                (float) (
                    $ligne['cout_unitaire']
                        ?? 0
                );

            $prixTotal =
                $quantite *
                $prixUnitaire;

            $coutTotal =
                $quantite *
                $coutUnitaire;

            $this->ligneModel
                ->insert([
                    'tenant_id' =>
                        $tenantId,

                    'reservation_id' =>
                        $reservationId,

                    'type_prestation' =>
                        $ligne['type_prestation']
                            ?? 'autre',

                    'prestation_id' =>
                        !empty(
                            $ligne['prestation_id']
                                ?? null
                        )
                            ? (int)
                                $ligne['prestation_id']
                            : null,

                    'fournisseur_id' =>
                        !empty(
                            $ligne['fournisseur_id']
                                ?? null
                        )
                            ? (int)
                                $ligne['fournisseur_id']
                            : null,

                    'designation' =>
                        $designation,

                    'description' =>
                        $ligne['description']
                            ?? null,

                    'quantite' =>
                        $quantite,

                    'cout_unitaire' =>
                        $coutUnitaire,

                    'cout_total' =>
                        $coutTotal,

                    'prix_unitaire' =>
                        $prixUnitaire,

                    'prix_total' =>
                        $prixTotal,

                    'devise' =>
                        $ligne['devise']
                            ?? 'MGA',

                    'ordre' =>
                        $ordre++,
                ]);
        }
    }

    private function catalogues(): array{
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
}