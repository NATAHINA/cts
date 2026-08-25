<?php

namespace App\Models;

use CodeIgniter\Model;

class TenantAccountModel extends Model
{
    protected $table            = 'tenants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'nom_agence', 'slug', 'email_contact', 'telephone',
        'adresse', 'logo', 'plan', 'statut',
    ];
}
