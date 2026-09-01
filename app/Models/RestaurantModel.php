<?php

namespace App\Models;

class RestaurantModel extends TenantModel
{
    protected $table            = 'restaurants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'tenant_id',
        'destination_id',
        'fournisseur_id',
        'nom',
        'type_cuisine',
        'adresse',
        'telephone',
        'email',
        'description',
        'prix_moyen',
        'devise',
        'note',
        'actif',
    ];

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[180]',
    ];
}