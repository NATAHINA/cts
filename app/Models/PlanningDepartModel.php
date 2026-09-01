<?php

namespace App\Models;

use CodeIgniter\Model;

class PlanningDepartModel extends Model
{
    protected $table = 'plannings_depart';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $useTimestamps = true;

    protected $allowedFields = [
        'tenant_id',
        'destination_id',
        'date_depart',
        'date_retour',
        'heure_depart',
        'heure_retour',
        'capacite',
        'places_vendues', // IMPORTANT
        'prix',
        'devise',
        'statut',
        'notes',
    ];

    protected $validationRules = [
        'tenant_id'      => 'required|integer',
        'destination_id' => 'required|integer',
        'date_depart'    => 'required|valid_date',
        'date_retour'    => 'permit_empty|valid_date',
        'heure_depart'   => 'permit_empty',
        'heure_retour'   => 'permit_empty',
        'capacite'       => 'required|integer|greater_than_equal_to[1]',
        'places_vendues' => 'permit_empty|integer',
        'prix'           => 'permit_empty|decimal',
        'devise'         => 'required|max_length[10]',
        'statut'         => 'required|in_list[planifie,ouvert,complet,termine,annule]',
        'notes'          => 'permit_empty',
    ];

    protected $validationMessages = [
        'destination_id' => [
            'required' => 'La destination est obligatoire.',
        ],

        'date_depart' => [
            'required' => 'La date de départ est obligatoire.',
        ],

        'capacite' => [
            'required'              => 'La capacité est obligatoire.',
            'greater_than_equal_to' => 'La capacité doit être supérieure à zéro.',
        ],
    ];


    /**
     * Liste des départs
     */
    public function getAllWithDestination(int $tenantId): array
    {
        return $this
            ->select('
                plannings_depart.*,
                destinations.nom AS destination_nom
            ')
            ->join(
                'destinations',
                'destinations.id = plannings_depart.destination_id',
                'left'
            )
            ->where(
                'plannings_depart.tenant_id',
                $tenantId
            )
            ->orderBy(
                'plannings_depart.date_depart',
                'ASC'
            )
            ->orderBy(
                'plannings_depart.heure_depart',
                'ASC'
            )
            ->findAll();
    }


    /**
     * Un planning
     */
    public function getWithDestination(
        int $id,
        int $tenantId
    ): ?array {
        return $this
            ->select('
                plannings_depart.*,
                destinations.nom AS destination_nom
            ')
            ->join(
                'destinations',
                'destinations.id = plannings_depart.destination_id',
                'left'
            )
            ->where(
                'plannings_depart.id',
                $id
            )
            ->where(
                'plannings_depart.tenant_id',
                $tenantId
            )
            ->first();
    }


    /**
     * Plannings disponibles
     */
    public function getAvailableByDestination(int $destinationId, int $tenantId): array
{
    return $this
        ->where('tenant_id', $tenantId)
        ->where('destination_id', $destinationId)
        ->whereIn('statut', ['planifie', 'ouvert'])
        ->where('date_depart >=', date('Y-m-d')) 
        ->orderBy('date_depart', 'ASC')
        ->orderBy('heure_depart', 'ASC')
        ->findAll();
}


    /**
     * Nombre de voyageurs
     */
    public function calculateReservationPeople(
        array $reservation
    ): int {
        return
            (int) ($reservation['nb_adultes'] ?? 0)
            +
            (int) ($reservation['nb_enfants'] ?? 0)
            +
            (int) ($reservation['nb_bebes'] ?? 0);
    }


    /**
     * Nombre de places vendues
     *
     * Ici : seules les réservations confirmées
     * sont considérées comme vendues.
     */
    public function getPlacesVendues(
        int $planningId,
        int $tenantId
    ): int {
        $db = db_connect();

        $result = $db
            ->table('reservations')
            ->select('
                COALESCE(
                    SUM(
                        COALESCE(nb_adultes, 0)
                        +
                        COALESCE(nb_enfants, 0)
                        +
                        COALESCE(nb_bebes, 0)
                    ),
                    0
                ) AS total
            ')
            ->where(
                'planning_id',
                $planningId
            )
            ->where(
                'tenant_id',
                $tenantId
            )
            ->whereIn(
                'statut',
                [
                    'confirmée',
                    'confirmee'
                ]
            )
            ->get()
            ->getRowArray();

        return (int) ($result['total'] ?? 0);
    }


    /**
     * Nombre de places réservées
     *
     * Confirmées + en attente.
     */
    public function getPlacesReservees(
        int $planningId,
        int $tenantId
    ): int {
        $db = db_connect();

        $result = $db
            ->table('reservations')
            ->select('
                COALESCE(
                    SUM(
                        COALESCE(nb_adultes, 0)
                        +
                        COALESCE(nb_enfants, 0)
                        +
                        COALESCE(nb_bebes, 0)
                    ),
                    0
                ) AS total
            ')
            ->where(
                'planning_id',
                $planningId
            )
            ->where(
                'tenant_id',
                $tenantId
            )
            ->whereIn(
                'statut',
                [
                    'confirmée',
                    'confirmee',
                    'en_attente'
                ]
            )
            ->get()
            ->getRowArray();

        return (int) ($result['total'] ?? 0);
    }


    /**
     * Places disponibles
     */
    public function getPlacesDisponibles(
        int $planningId,
        int $tenantId
    ): int {
        $planning = $this
            ->where(
                'id',
                $planningId
            )
            ->where(
                'tenant_id',
                $tenantId
            )
            ->first();

        if (!$planning) {
            return 0;
        }

        $reservees = $this->getPlacesReservees(
            $planningId,
            $tenantId
        );

        return max(
            0,
            (int) $planning['capacite'] - $reservees
        );
    }


    /**
     * Taux de remplissage
     */
    public function getTauxRemplissage(
        int $planningId,
        int $tenantId
    ): float {
        $planning = $this
            ->where(
                'id',
                $planningId
            )
            ->where(
                'tenant_id',
                $tenantId
            )
            ->first();

        if (
            !$planning ||
            (int) $planning['capacite'] <= 0
        ) {
            return 0;
        }

        $reservees = $this->getPlacesReservees(
            $planningId,
            $tenantId
        );

        return round(
            (
                $reservees /
                (int) $planning['capacite']
            ) * 100,
            2
        );
    }


    /**
     * Vérification des places
     *
     * Pour une modification, on peut exclure
     * la réservation actuelle.
     */
    public function hasEnoughPlaces(
        int $planningId,
        int $tenantId,
        int $nbVoyageurs,
        ?int $reservationId = null
    ): bool {

        $planning = $this
            ->where(
                'id',
                $planningId
            )
            ->where(
                'tenant_id',
                $tenantId
            )
            ->first();

        if (!$planning) {
            return false;
        }

        $reservees = $this->getPlacesReservees(
            $planningId,
            $tenantId
        );

        /*
         * En modification, retirer les voyageurs
         * de la réservation actuelle.
         */
        if ($reservationId) {

            $reservation = db_connect()
                ->table('reservations')
                ->where(
                    'id',
                    $reservationId
                )
                ->where(
                    'tenant_id',
                    $tenantId
                )
                ->get()
                ->getRowArray();

            if ($reservation) {

                $ancienNombre =
                    (int) ($reservation['nb_adultes'] ?? 0)
                    +
                    (int) ($reservation['nb_enfants'] ?? 0)
                    +
                    (int) ($reservation['nb_bebes'] ?? 0);

                /*
                 * Si la réservation appartient déjà
                 * à ce planning, on la retire du calcul.
                 */
                if (
                    (int) $reservation['planning_id']
                    === $planningId
                ) {
                    $reservees -= $ancienNombre;
                }
            }
        }

        return (
            (int) $planning['capacite']
            - $reservees
        ) >= $nbVoyageurs;
    }


    /**
     * Recalcul du nombre de places vendues
     */
    public function recalculerPlacesVendues(
        int $planningId,
        int $tenantId
    ): bool {

        $vendus = $this->getPlacesVendues(
            $planningId,
            $tenantId
        );

        return $this
            ->where(
                'id',
                $planningId
            )
            ->where(
                'tenant_id',
                $tenantId
            )
            ->set(
                'places_vendues',
                $vendus
            )
            ->update();
    }


    /**
     * Mise à jour automatique du statut
     */
    public function updateAutomaticStatus(
        int $planningId,
        int $tenantId
    ): bool {

        $planning = $this
            ->where(
                'id',
                $planningId
            )
            ->where(
                'tenant_id',
                $tenantId
            )
            ->first();

        if (!$planning) {
            return false;
        }

        if (
            in_array(
                $planning['statut'],
                [
                    'annule',
                    'termine'
                ],
                true
            )
        ) {
            return true;
        }

        $placesReservees =
            $this->getPlacesReservees(
                $planningId,
                $tenantId
            );

        $capacite =
            (int) $planning['capacite'];

        if ($placesReservees >= $capacite) {

            $statut = 'complet';

        } elseif ($placesReservees > 0) {

            $statut = 'ouvert';

        } else {

            $statut = 'planifie';
        }

        return $this->update(
            $planningId,
            [
                'statut' => $statut
            ]
        );
    }


    /**
     * Statistiques
     */
    public function getStatistics(
        int $planningId,
        int $tenantId
    ): array {

        $planning = $this
            ->where(
                'id',
                $planningId
            )
            ->where(
                'tenant_id',
                $tenantId
            )
            ->first();

        if (!$planning) {
            return [];
        }

        $vendues =
            $this->getPlacesVendues(
                $planningId,
                $tenantId
            );

        $reservees =
            $this->getPlacesReservees(
                $planningId,
                $tenantId
            );

        $disponibles = max(
            0,
            (int) $planning['capacite']
            - $reservees
        );

        $taux =
            (int) $planning['capacite'] > 0
                ? round(
                    (
                        $reservees /
                        (int) $planning['capacite']
                    ) * 100,
                    2
                )
                : 0;

        return [
            'capacite'           =>
                (int) $planning['capacite'],

            'places_vendues'     =>
                $vendues,

            'places_reservees'   =>
                $reservees,

            'places_disponibles' =>
                $disponibles,

            'taux_remplissage'   =>
                $taux,
        ];
    }

    /**
 * Plannings disponibles + planning actuel
 *
 * Utilisé lors de la modification d'une réservation.
 */
    public function getAvailableByDestinationForEdit(
        int $destinationId,
        int $tenantId,
        int $planningActuelId
    ): array
    {
        // 1. Récupère d'abord les départs "normaux" disponibles
        $plannings = $this->getAvailableByDestination($destinationId, $tenantId);

        // 2. Vérifie si le planning actuel est déjà dans la liste
        $existe = false;
        foreach ($plannings as $p) {
            if ((int) $p['id'] === $planningActuelId) {
                $existe = true;
                break;
            }
        }

        // 3. S'il n'existe pas → on le récupère et on le force
        if (!$existe && $planningActuelId > 0) {

            $planningActuel = $this
                ->where('id', $planningActuelId)
                ->where('tenant_id', $tenantId)
                ->where('destination_id', $destinationId)
                ->first();

            if ($planningActuel) {
                // On l'ajoute au début de la liste
                array_unshift($plannings, $planningActuel);
            }
        }

        return $plannings;
    }
}