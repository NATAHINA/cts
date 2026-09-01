<?php

namespace App\Models;

class HotelModel extends TenantModel
{
    protected $table            = 'hotels';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'tenant_id',
        'destination_id',
        'fournisseur_id',
        'nom',
        'categorie',
        'adresse',
        'description',
        'prix_nuit',
        'prix_adulte', 
        'prix_enfant', 
        'prix_groupe',
        'disponibilite',
        'devise_id',
        'statut',
    ];
}
