<?php

namespace App\Models;

class ReservationModel extends TenantModel
{
    protected $table            = 'reservations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'tenant_id',
        'numero',
        'planning_id',
        'cotation_id',
        'client_id',
        'destination_id',
        'date_depart',
        'date_retour',
        'nb_adultes',
        'nb_enfants',
        'nb_bebes',
        'devise',
        'montant_total',
        'statut',
        'notes_client',
        'notes_interne',
        'created_by',
    ];

    /**
     * Génère le prochain numéro de réservation.
     * Exemple : RES-2026-00001
     */
    public function prochainNumero(int $tenantId): string
    {
        $annee = date('Y');

        $count = $this
            ->where('tenant_id', $tenantId)
            ->like('numero', 'RES-' . $annee . '-', 'after')
            ->countAllResults();

        return sprintf('RES-%s-%05d', $annee, $count + 1);
    }

    /**
     * Recalcule le montant total depuis les lignes.
     */
    public function recalculerMontant(int $reservationId): void
    {
        $ligneModel = new ReservationLigneModel();

        $resultat = $ligneModel
            ->selectSum('prix_total', 'total')
            ->where('reservation_id', $reservationId)
            ->first();

        $montantTotal = (float) ($resultat['total'] ?? 0);

        $this->update($reservationId, [
            'montant_total' => $montantTotal,
        ]);
    }
}