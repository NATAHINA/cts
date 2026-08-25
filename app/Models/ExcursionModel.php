<?php

namespace App\Models;

class ExcursionModel extends TenantModel
{
    protected $table            = 'excursions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'tenant_id',
        'destination_id',
        'nom',
        'description',
        'duree_heures',
        'prix',
        'prix_adulte', 'prix_enfant', 'prix_groupe',
        'fournisseur', 'disponibilite',
        'devise_id',
        'statut',
    ];
}
