<?php

namespace App\Models;

class ClientModel extends TenantModel
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'tenant_id',
        'type',
        'nom',
        'email',
        'telephone',
        'adresse',
        'notes',
    ];
}
