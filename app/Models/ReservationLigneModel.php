<?php

namespace App\Models;

class ReservationLigneModel extends TenantModel
{
    protected $table            = 'reservation_lignes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'tenant_id',
        'reservation_id',
        'type_prestation', 
        'prestation_id',
        'fournisseur_id',
        'designation',
        'description',
        'quantite',
        'cout_unitaire',
        'cout_total',
        'prix_unitaire',
        'prix_total',
        'devise',
        'ordre',
    ];

    protected $validationRules = [
        'reservation_id' => 'required|integer',
        'designation'    => 'required|min_length[2]',
        'quantite'       => 'required|integer|greater_than[0]',
        'prix_unitaire'  => 'permit_empty|decimal',
        'prix_total'     => 'permit_empty|decimal',
    ];

    /**
     * Récupère toutes les lignes d'une réservation (ordonnées).
     */
    public function getByReservation(int $reservationId, int $tenantId): array
    {
        return $this
            ->where('reservation_id', $reservationId)
            ->where('tenant_id', $tenantId)
            ->orderBy('ordre', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Supprime toutes les lignes d'une réservation.
     */
    public function deleteByReservation(int $reservationId): bool
    {
        return $this
            ->where('reservation_id', $reservationId)
            ->delete();
    }
}