<?php

namespace App\Models;

class DestinationModel extends TenantModel
{
    protected $table            = 'destinations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'tenant_id',
        'nom',
        'pays',
        'region',
        'description',
        'statut',
    ];
}
