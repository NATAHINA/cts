<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReservationModel;
use App\Models\ReservationLigneModel;
use App\Models\ClientModel;
use App\Models\CotationModel;
use App\Models\DestinationModel;

class ReservationController extends BaseController
{
    protected $model;
    protected $ligneModel;
    protected $clientModel;
    protected $cotationModel;
    protected $destinationModel;

    public function __construct()
    {
        $this->model            = new ReservationModel();
        $this->ligneModel       = new ReservationLigneModel();
        $this->clientModel      = new ClientModel();
        $this->cotationModel    = new CotationModel();
        $this->destinationModel = new DestinationModel();
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
     * Liste des réservations.
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
            ->join('clients', 'clients.id = reservations.client_id', 'left')
            ->join('destinations', 'destinations.id = reservations.destination_id', 'left')
            ->where('reservations.tenant_id', $this->tenantId())
            ->orderBy('reservations.id', 'DESC')
            ->findAll();

        return view('reservations/index', [
            'title'        => 'Réservations',
            'reservations' => $reservations,
        ]);
    }

    /**
     * Formulaire de création.
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
        ]);
    }

    /**
     * Enregistre une nouvelle réservation + ses lignes.
     */
    public function store()
    {
        $planningModel = new \App\Models\PlanningDepartModel();

        $tenantId = $this->tenantId();

        $clientId   = (int) $this->request->getPost('client_id');
        $cotationId = $this->request->getPost('cotation_id');

        $planningId = (int) (
            $this->request->getPost('planning_id') ?: 0
        );

        // Vérification client
        $client = $this->clientModel
            ->where('id', $clientId)
            ->where('tenant_id', $tenantId)
            ->first();
        
        if (! $client) {
            return redirect()->back()->withInput()
                ->with('error', 'Le client sélectionné est introuvable.');
        }

        $planning = $planningModel
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

        if (in_array($planning['statut'], ['annule', 'termine'])) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Ce départ n\'est plus disponible pour une réservation.'
                );
        }

        // Vérification cotation
        if (! empty($cotationId)) {
            $cotation = $this->cotationModel
                ->where('id', $cotationId)
                ->where('tenant_id', $tenantId)
                ->first();

            if (! $cotation) {
                return redirect()->back()->withInput()
                    ->with('error', 'La cotation sélectionnée est introuvable.');
            }
        }

        $nbAdultes = (int) (
            $this->request->getPost('nb_adultes') ?: 0
        );

        $nbEnfants = (int) (
            $this->request->getPost('nb_enfants') ?: 0
        );

        $nbBebes = (int) (
            $this->request->getPost('nb_bebes') ?: 0
        );

        $nbVoyageurs =
            $nbAdultes +
            $nbEnfants +
            $nbBebes;
        
        if ($nbVoyageurs <= 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Veuillez saisir au moins un voyageur.'
                );
        }


        if (!$planningModel->hasEnoughPlaces(
            $planningId,
            $tenantId,
            $nbVoyageurs
        )) {

            $placesDisponibles =
                $planningModel->getPlacesDisponibles(
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

        $data = [
            'tenant_id'      => $tenantId,
            'numero'         => $this->model->prochainNumero($tenantId),
            'cotation_id'    => ! empty($cotationId) ? (int) $cotationId : null,
            'client_id'      => $clientId,
            'planning_id' => $planningId,
            'destination_id' => $this->request->getPost('destination_id') ?: null,
            'date_depart' => $planning['date_depart'] ?? null,
            'heure_depart' => $planning['heure_depart'] ?? null,
            'date_retour' => $planning['date_retour'] ?? null,
            'heure_retour' => $planning['heure_retour'] ?? null,
            'nb_adultes'     => $nbAdultes,
            'nb_enfants'     => $nbEnfants,
            'nb_bebes'       => $nbBebes,
            'devise'         => $this->request->getPost('devise') ?: 'MGA',
            'montant_total'  => 0, // sera recalculé
            'statut'         => $this->request->getPost('statut') ?: 'en_attente',
            'notes_client'   => $this->request->getPost('notes_client') ?: null,
            'notes_interne'  => $this->request->getPost('notes_interne') ?: null,
            'created_by'     => session('user_id') ?: null,
        ];

        $id = $this->model->insert($data, true);

        if (! $id) {
            return redirect()->back()->withInput()
                ->with('error', 'Impossible de créer la réservation.');
        }

        // Enregistrement des lignes
        $this->saveLignes($id, $tenantId);

        // Recalcul du montant total
        $this->model->recalculerMontant($id);

        return redirect()
            ->to(site_url('reservations/' . $id))
            ->with('success', 'Réservation créée avec succès.');
    }

    /**
     * Affichage d'une réservation + ses lignes.
     */
    public function show($id){
        $reservation = $this->model
            ->select('
                reservations.*,
                clients.nom AS client_nom,
                clients.prenom AS client_prenom,
                destinations.nom AS destination_nom,
                cotations.numero AS cotation_numero
            ')
            ->join('clients', 'clients.id = reservations.client_id', 'left')
            ->join('destinations', 'destinations.id = reservations.destination_id', 'left')
            ->join('cotations', 'cotations.id = reservations.cotation_id', 'left')
            ->where('reservations.id', $id)
            ->where('reservations.tenant_id', $this->tenantId())
            ->first();

        if (! $reservation) {
            return redirect()->to('/reservations')
                ->with('error', 'Réservation introuvable.');
        }

        $lignes = $this->ligneModel->getByReservation((int) $id, $this->tenantId());

        // Facture liée (s'il y en a une)
        $factureModel = new \App\Models\FactureModel();
        $factureExistante = $factureModel
            ->where('tenant_id', $this->tenantId())
            ->where('reservation_id', $id)
            ->whereNotIn('statut', ['annulee'])
            ->orderBy('id', 'DESC')
            ->first();

        return view('reservations/show', [
            'title'            => 'Réservation ' . $reservation['numero'],
            'reservation'      => $reservation,
            'lignes'           => $lignes,
            'factureExistante' => $factureExistante,
        ]);
    }

    /**
     * Formulaire de modification.
     */
    public function edit($id)
    {
        $tenantId    = $this->tenantId();
        $reservation = $this->getReservation((int) $id);

        if (! $reservation) {
            return redirect()->to(site_url('reservations'))
                ->with('error', 'Réservation introuvable.');
        }

        $lignes = $this->ligneModel->getByReservation((int) $id, $tenantId);

        return view('reservations/form', [
            'title'        => 'Modifier la réservation',
            'reservation'  => $reservation,
            'clients'      => $this->clientModel
                ->where('tenant_id', $tenantId)
                ->orderBy('nom', 'ASC')
                ->findAll(),
            'cotations'    => $this->cotationModel
                ->where('tenant_id', $tenantId)
                ->orderBy('id', 'DESC')
                ->findAll(),
            'destinations' => $this->destinationModel
                ->where('tenant_id', $tenantId)
                ->orderBy('nom', 'ASC')
                ->findAll(),
            'lignes'       => $lignes,
        ]);
    }

    /**
     * Met à jour une réservation + ses lignes.
     */
    public function update($id){
        $tenantId = $this->tenantId();

        $reservation = $this->getReservation((int) $id);

        if (!$reservation) {

            return redirect()
                ->to(site_url('reservations'))
                ->with(
                    'error',
                    'Réservation introuvable.'
                );
        }

        $planningModel =
            new \App\Models\PlanningDepartModel();
        $clientId =
            (int) $this->request->getPost('client_id');
        $cotationId =
            $this->request->getPost('cotation_id');
        $planningId =
            (int) (
                $this->request->getPost('planning_id')
                ?: 0
            );

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


        /*
        * PLANNING
        */

        $planning = $planningModel
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


        /*
        * NOMBRE DE VOYAGEURS
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


        /*
        * Places déjà utilisées par cette réservation
        */

        $anciensVoyageurs =
            (int) ($reservation['nb_adultes'] ?? 0)
            +
            (int) ($reservation['nb_enfants'] ?? 0)
            +
            (int) ($reservation['nb_bebes'] ?? 0);


        $placesDisponibles =
            $planningModel->getPlacesDisponibles(
                $planningId,
                $tenantId
            );


        /*
        * Si on conserve le même planning,
        * on restitue les places de l'ancienne réservation.
        */

        if (
            (int) $reservation['planning_id']
            === $planningId
        ) {
            $placesDisponibles += $anciensVoyageurs;
        }


        if ($nbVoyageurs > $placesDisponibles) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Ce départ ne dispose pas de suffisamment de places.'
                );
        }


        /*
        * COTATION
        */

        if (!empty($cotationId)) {

            $cotation = $this->cotationModel
                ->where('id', $cotationId)
                ->where('tenant_id', $tenantId)
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


        /*
        * DONNÉES
        */

        $data = [

            'client_id' => $clientId,

            'cotation_id' =>
                !empty($cotationId)
                    ? (int) $cotationId
                    : null,

            'planning_id' => $planningId,

            'destination_id' =>
                $planning['destination_id'] ?? null,

            'date_depart' =>
                $planning['date_depart'] ?? null,

            'heure_depart' =>
                $planning['heure_depart'] ?? null,

            'date_retour' =>
                $planning['date_retour'] ?? null,

            'heure_retour' =>
                $planning['heure_retour'] ?? null,

            'nb_adultes' => $nbAdultes,

            'nb_enfants' => $nbEnfants,

            'nb_bebes' => $nbBebes,

            'devise' =>
                $planning['devise']
                ?? $this->request->getPost('devise')
                ?? 'MGA',

            'statut' =>
                $this->request->getPost('statut')
                ?: 'en_attente',

            'notes_client' =>
                $this->request->getPost('notes_client')
                ?: null,

            'notes_interne' =>
                $this->request->getPost('notes_interne')
                ?: null,
        ];


        if (!$this->model->update(
            $id,
            $data
        )) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->model->errors()
                );
        }


        /*
        * Remplacement des lignes
        */

        $this->ligneModel
            ->deleteByReservation(
                (int) $id
            );

        $this->saveLignes(
            (int) $id,
            $tenantId
        );


        $this->model->recalculerMontant(
            (int) $id
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
     * Supprime une réservation + ses lignes.
     */
    public function delete($id)
    {
        $reservation = $this->getReservation((int) $id);

        if (! $reservation) {
            return redirect()->to(site_url('reservations'))
                ->with('error', 'Réservation introuvable.');
        }

        $this->ligneModel->deleteByReservation((int) $id);
        $this->model->delete($id);

        return redirect()
            ->to(site_url('reservations'))
            ->with('success', 'Réservation supprimée avec succès.');
    }

    /**
     * Enregistre les lignes reçues depuis le formulaire.
     * Format attendu :
     * lignes[0][designation], lignes[0][quantite], lignes[0][prix_unitaire], etc.
     */
    private function saveLignes(int $reservationId, int $tenantId): void
    {
        $lignes = $this->request->getPost('lignes') ?? [];

        if (! is_array($lignes)) {
            return;
        }

        $ordre = 1;

        foreach ($lignes as $ligne) {
            $designation = trim($ligne['designation'] ?? '');
            if ($designation === '') {
                continue;
            }

            $quantite      = max(1, (int) ($ligne['quantite'] ?? 1));
            $prixUnitaire  = (float) ($ligne['prix_unitaire'] ?? 0);
            $prixTotal     = $quantite * $prixUnitaire;
            $coutUnitaire  = (float) ($ligne['cout_unitaire'] ?? 0);
            $coutTotal     = $quantite * $coutUnitaire;

            $this->ligneModel->insert([
                'tenant_id'       => $tenantId,
                'reservation_id'  => $reservationId,
                'type_prestation' => $ligne['type_prestation'] ?? 'autre',
                'prestation_id'   => ! empty($ligne['prestation_id']) ? (int) $ligne['prestation_id'] : null,
                'fournisseur_id'  => ! empty($ligne['fournisseur_id']) ? (int) $ligne['fournisseur_id'] : null,
                'designation'     => $designation,
                'description'     => $ligne['description'] ?? null,
                'quantite'        => $quantite,
                'cout_unitaire'   => $coutUnitaire,
                'cout_total'      => $coutTotal,
                'prix_unitaire'   => $prixUnitaire,
                'prix_total'      => $prixTotal,
                'devise'          => $ligne['devise'] ?? 'MGA',
                'ordre'           => $ordre++,
            ]);
        }
    }


    public function planning($id)
{
    $tenantId = $this->tenantId();

    if ($tenantId <= 0) {

        return $this->response
            ->setJSON([
                'success' => false,
                'message' => 'Agence non identifiée.',
            ]);
    }

    $planningModel =
        new \App\Models\PlanningDepartModel();

    $planning = $planningModel
        ->getWithDestination(
            (int) $id,
            $tenantId
        );

    if (!$planning) {

        return $this->response
            ->setJSON([
                'success' => false,
                'message' => 'Planning introuvable.',
            ]);
    }


    $stats =
        $planningModel->getStatistics(
            (int) $id,
            $tenantId
        );


    return $this->response
        ->setJSON([
            'success' => true,

            'data' => array_merge(
                $planning,
                $stats
            ),
        ]);
}
}