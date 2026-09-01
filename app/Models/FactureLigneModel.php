<?php

namespace App\Models;

class FactureLigneModel extends TenantModel
{
    protected $table            = 'facture_lignes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'tenant_id',
        'facture_id',
        'designation',
        'description',
        'quantite',
        'prix_unitaire',
        'montant',
        'ordre',
    ];
}