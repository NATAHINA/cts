<?php

namespace App\Models;

class PaiementModel extends TenantModel
{
    protected $table            = 'paiements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'tenant_id',
        'numero',
        'facture_id',
        'client_id',
        'date_paiement',
        'montant',
        'devise',
        'mode_paiement',
        'reference_externe',
        'notes',
        'statut',
        'created_by',
    ];

    public function prochaineReference(int $tenantId): string
    {
        $annee = date('Y');

        $count = $this
            ->where('tenant_id', $tenantId)
            ->like('numero', 'PAY-' . $annee . '-', 'after')
            ->countAllResults();

        return sprintf('PAY-%s-%05d', $annee, $count + 1);
    }
}