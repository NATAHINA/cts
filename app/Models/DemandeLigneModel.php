<?php

namespace App\Models;

class DemandeLigneModel extends TenantModel
{
    protected $table            = 'demande_lignes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'tenant_id',
        'demande_id',
        'type_prestation',
        'prestation_id',
        'fournisseur_id',
        'designation',
        'description',
        'quantite',
        'cout_unitaire',
        'cout_total',
        'marge_pourcentage',
        'marge_montant',
        'prix_unitaire',
        'prix_total',
        'devise',
        'ordre',
    ];
}