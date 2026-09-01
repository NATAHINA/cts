<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PlanningDepartModel;
use App\Models\DestinationModel;

class PlanningDepartController extends BaseController
{
    protected $planningModel;
    protected $destinationModel;

    public function __construct()
    {
        $this->planningModel = new PlanningDepartModel();
        $this->destinationModel = new DestinationModel();
    }


    /**
     * Liste
     */
    public function index()
{
    $tenantId = (int) session('tenant_id');

    if ($tenantId <= 0) {
        return redirect()
            ->back()
            ->with(
                'error',
                'Agence non identifiée.'
            );
    }

    $plannings = $this->planningModel
        ->getAllWithDestination($tenantId);


    foreach ($plannings as &$planning) {

        /*
         * Mettre d'abord à jour le statut
         * en fonction des réservations.
         */
        $this->planningModel
            ->updateAutomaticStatus(
                (int) $planning['id'],
                $tenantId
            );


        /*
         * Puis recalculer les statistiques.
         */
        $stats = $this->planningModel
            ->getStatistics(
                (int) $planning['id'],
                $tenantId
            );


        $planning = array_merge(
            $planning,
            $stats
        );
    }

    unset($planning);


    return view(
        'plannings_depart/index',
        [
            'title' => 'Planning des départs',
            'plannings' => $plannings,
        ]
    );
}


    /**
     * Nouveau
     */
    public function new()
    {
        $tenantId = (int) session('tenant_id');

        if ($tenantId <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Agence non identifiée.');
        }

        return view('plannings_depart/form', [
            'title' => 'Nouveau départ',
            'planning' => null,
            'destinations' => $this->destinationModel
                ->orderBy('nom', 'ASC')
                ->findAll(),
        ]);
    }


    /**
     * Création
     */
    public function create()
    {
        $tenantId = (int) session('tenant_id');

        if ($tenantId <= 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Agence non identifiée.');
        }

        $data = [
            'tenant_id'      => $tenantId,

            'destination_id' => $this->request
                ->getPost('destination_id'),

            'date_depart'    => $this->request
                ->getPost('date_depart'),

            'date_retour'    => $this->request
                ->getPost('date_retour') ?: null,

            'heure_depart'   => $this->request
                ->getPost('heure_depart') ?: null,

            'heure_retour'   => $this->request
                ->getPost('heure_retour') ?: null,

            'capacite'       => (int) (
                $this->request->getPost('capacite') ?: 0
            ),

            'prix'           => $this->request
                ->getPost('prix') ?: null,

            'devise'         => $this->request
                ->getPost('devise') ?: 'MGA',

            'statut'         => $this->request
                ->getPost('statut') ?: 'planifie',

            'notes'          => $this->request
                ->getPost('notes'),
        ];

        if (!$this->planningModel->insert($data)) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->planningModel->errors()
                );
        }

        return redirect()
            ->to(site_url('plannings-depart'))
            ->with(
                'success',
                'Le départ a été créé avec succès.'
            );
    }


    /**
     * Détail
     */
    public function show($id)
    {
        $tenantId = (int) session('tenant_id');

        $planning = $this->planningModel
            ->getWithDestination(
                (int) $id,
                $tenantId
            );

        if (!$planning) {
            return redirect()
                ->to(site_url('plannings-depart'))
                ->with(
                    'error',
                    'Planning introuvable.'
                );
        }

        $stats = $this->planningModel->getStatistics(
            (int) $id,
            $tenantId
        );

        return view('plannings_depart/show', [
            'title' => 'Détail du départ',

            'planning' => array_merge(
                $planning,
                $stats
            ),
        ]);
    }


    /**
     * Modification
     */
    public function edit($id)
    {
        $tenantId = (int) session('tenant_id');

        $planning = $this->planningModel
            ->where('id', $id)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$planning) {
            return redirect()
                ->to(site_url('plannings-depart'))
                ->with(
                    'error',
                    'Planning introuvable.'
                );
        }

        return view('plannings_depart/form', [
            'title' => 'Modifier le départ',
            'planning' => $planning,
            'destinations' => $this->destinationModel
                ->orderBy('nom', 'ASC')
                ->findAll(),
        ]);
    }


    /**
     * Mise à jour
     */
    public function update($id)
    {
        $tenantId = (int) session('tenant_id');

        $planning = $this->planningModel
            ->where('id', $id)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$planning) {
            return redirect()
                ->to(site_url('plannings-depart'))
                ->with(
                    'error',
                    'Planning introuvable.'
                );
        }

        $capacite = (int) (
            $this->request->getPost('capacite') ?: 0
        );

        $placesReservees =
            $this->planningModel->getPlacesReservees(
                (int) $id,
                $tenantId
            );

        if ($capacite < $placesReservees) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'La capacité ne peut pas être inférieure aux places déjà réservées.'
                );
        }

        $data = [
            'destination_id' => $this->request
                ->getPost('destination_id'),

            'date_depart' => $this->request
                ->getPost('date_depart'),

            'date_retour' => $this->request
                ->getPost('date_retour') ?: null,

            'heure_depart' => $this->request
                ->getPost('heure_depart') ?: null,

            'heure_retour' => $this->request
                ->getPost('heure_retour') ?: null,

            'capacite' => $capacite,

            'prix' => $this->request
                ->getPost('prix') ?: null,

            'devise' => $this->request
                ->getPost('devise') ?: 'MGA',

            'statut' => $this->request
                ->getPost('statut') ?: 'planifie',

            'notes' => $this->request
                ->getPost('notes'),
        ];

        if (!$this->planningModel->update($id, $data)) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->planningModel->errors()
                );
        }

        $this->planningModel->updateAutomaticStatus(
            (int) $id,
            $tenantId
        );

        return redirect()
            ->to(site_url('plannings-depart'))
            ->with(
                'success',
                'Le planning a été modifié.'
            );
    }


    /**
     * Suppression
     */
    public function delete($id)
    {
        $tenantId = (int) session('tenant_id');

        $planning = $this->planningModel
            ->where('id', $id)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$planning) {
            return redirect()
                ->to(site_url('plannings-depart'))
                ->with(
                    'error',
                    'Planning introuvable.'
                );
        }

        /*
         * On évite de supprimer un planning
         * déjà utilisé par des réservations.
         */
        $db = db_connect();

        $reservationCount = $db
            ->table('reservations')
            ->where('planning_id', $id)
            ->countAllResults();

        if ($reservationCount > 0) {

            return redirect()
                ->to(site_url('plannings-depart'))
                ->with(
                    'error',
                    'Impossible de supprimer ce départ car il possède déjà des réservations.'
                );
        }

        $this->planningModel->delete($id);

        return redirect()
            ->to(site_url('plannings-depart'))
            ->with(
                'success',
                'Le planning a été supprimé.'
            );
    }


    /**
     * API AJAX :
     * récupérer les départs disponibles
     */
    public function available(){
        $tenantId         = (int) session('tenant_id');
        $destinationId    = (int) $this->request->getGet('destination_id');
        $planningActuelId = (int) $this->request->getGet('planning_id');

        if ($destinationId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'data'    => [],
                'message' => 'Destination invalide.'
            ]);
        }

        // Récupère les départs disponibles
        $plannings = $this->planningModel
            ->where('tenant_id', $tenantId)
            ->where('destination_id', $destinationId)
            ->whereIn('statut', ['planifie', 'ouvert', 'complet']) // adapte
            ->orderBy('date_depart', 'ASC')
            ->orderBy('heure_depart', 'ASC')
            ->findAll();

        // === FORCE l'inclusion du planning actuel ===
        if ($planningActuelId > 0) {

            $existe = false;
            foreach ($plannings as $p) {
                if ((int) $p['id'] === $planningActuelId) {
                    $existe = true;
                    break;
                }
            }

            if (!$existe) {
                $planningActuel = $this->planningModel
                    ->where('id', $planningActuelId)
                    ->where('tenant_id', $tenantId)
                    ->where('destination_id', $destinationId)
                    ->first();

                if ($planningActuel) {
                    // On l'ajoute en premier
                    array_unshift($plannings, $planningActuel);
                }
            }
        }

        // Formatage de la réponse
        $data = [];

        foreach ($plannings as $planning) {
            $data[] = [
                'id'                 => (int) $planning['id'],
                'date_depart'        => $planning['date_depart'],
                'date_retour'        => $planning['date_retour'],
                'heure_depart'       => $planning['heure_depart'],
                'heure_retour'       => $planning['heure_retour'],
                'places_disponibles' => $this->planningModel->getPlacesDisponibles(
                    (int) $planning['id'],
                    $tenantId
                ),
                'prix'               => (float) ($planning['prix'] ?? 0),
                'devise'             => $planning['devise'] ?? 'MGA',
                'statut'             => $planning['statut'] ?? '-',
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $data
        ]);
    }
}