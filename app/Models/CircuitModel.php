<?php

namespace App\Models;

class CircuitModel extends TenantModel
{
    protected $table            = 'circuits';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'tenant_id',
        'nom',
        'description',
        'duree_jours',
        'prix',
        'prix_adulte', 'prix_enfant', 'prix_groupe',
        'fournisseur_id', 'disponibilite',
        'devise_id',
        'statut',
    ];
}
