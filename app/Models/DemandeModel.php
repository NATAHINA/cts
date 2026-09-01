<?php

namespace App\Models;

use CodeIgniter\Model;

class DemandeModel extends Model
{
    protected $table            = 'demandes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'tenant_id',
        'client_id',
        'destination_id',
        'numero',
        'date_demande',
        'destination',
        'date_depart',
        'date_retour',
        'nb_adultes',
        'nb_enfants',
        'nb_bebes',
        'budget',
        'mode_tarif',
        'prix_forfait',
        'devise',
        'source',
        'statut',
        'notes_client',
        'notes_interne',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function generateNumero(int $tenantId): string
    {
        $year = date('Y');

        $last = $this
            ->where('tenant_id', $tenantId)
            ->like('numero', "DEM-{$year}-", 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $next = 1;

        if ($last && !empty($last['numero'])) {
            $parts = explode('-', $last['numero']);
            $lastNumber = (int) end($parts);

            $next = $lastNumber + 1;
        }

        $formatted = $next < 100000
            ? str_pad((string) $next, 5, '0', STR_PAD_LEFT)
            : (string) $next;

        return "DEM-{$year}-{$formatted}";
    }
}