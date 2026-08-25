<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Table de référence globale (partagée entre tous les tenants) :
 * hérite du Model CI4 standard, pas de TenantModel ici.
 */
class DeviseModel extends Model
{
    protected $table         = 'devises';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['code', 'nom', 'symbole'];
}
