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

    protected $allowedFields = [
        'nom_agence',
        'slug',
        'email_contact',
        'telephone',
        'adresse',
        'logo',
        'nif',
        'stat',
        'rcs',
        'site_web',
        'devise_defaut',
        'tva',
        'prefixe_cotation',
        'prefixe_reservation',
        'prefixe_facture',
        'conditions_generales',
        'pied_page_document',
        'plan',
        'statut',
    ];
}