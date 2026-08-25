<?php

namespace App\Models;

class ForfaitModel extends TenantModel
{
    protected $table            = 'forfaits';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'tenant_id',
        'destination_id',
        'nom',
        'description',
        'duree_jours',
        'prix',
        'devise_id',
        'statut',
    ];
}
