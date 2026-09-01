<?php


namespace App\Models;

class RoleModel extends TenantModel
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'tenant_id',
        'code',
        'libelle',
        'description',
    ];
}