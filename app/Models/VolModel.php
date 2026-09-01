<?php

namespace App\Models;

class VolModel extends TenantModel
{
    protected $table            = 'vols';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'tenant_id',
        'compagnie',
        'num_vol',
        'aeroport_depart',
        'aeroport_arrivee',
        'date_depart',
        'date_arrivee',
        'prix',
        'prix_adulte', 'prix_enfant', 'prix_groupe',
        'id_fournisseur', 
        'disponibilite',
        'devise_id',
        'statut',
    ];
}
