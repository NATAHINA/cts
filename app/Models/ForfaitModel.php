<?php

namespace App\Models;

class ForfaitModel extends TenantModel
{
    protected $table            = 'forfaits';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        // Multi-agence
        'tenant_id',

        // Informations générales
        'destination_id',
        'fournisseur_id',
        'code',
        'nom',
        'description',

        // Durée
        'duree_jours',
        'duree_nuits',

        // Tarification
        'prix',
        'prix_adulte',
        'prix_enfant',
        'prix_groupe',
        'devise_id',

        // Gestion commerciale
        'commission_pourcentage',
        'disponibilite',
        'statut',
    ];
}