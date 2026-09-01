<?php

namespace App\Models;

class CroisiereModel extends TenantModel
{
    protected $table            = 'croisieres';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'tenant_id',
        'compagnie',
        'nom',
        'itineraire',
        'duree_jours',
        'date_depart',
        'prix',
        'prix_adulte', 'prix_enfant', 'prix_groupe',
        'fournisseur_id', 'disponibilite',
        'devise_id',
        'statut',
    ];
}
