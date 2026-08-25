<?php

namespace App\Models;

class TransfertModel extends TenantModel
{
    protected $table            = 'transferts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'tenant_id',
        'destination_id',
        'nom',
        'type',
        'vehicule',
        'capacite',
        'prix',
        'prix_adulte', 'prix_enfant', 'prix_groupe',
        'fournisseur', 'disponibilite',
        'devise_id',
        'statut',
    ];
}
