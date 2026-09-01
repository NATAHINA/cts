<?php

namespace App\Models;

class FournisseurModel extends TenantModel
{
    protected $table = 'fournisseurs';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $useTimestamps = true;

    protected $allowedFields = [
        'tenant_id',
        'type',
        'nom',
        'contact_nom',
        'telephone',
        'email',
        'adresse',
        'ville',
        'pays',
        'devise',
        'conditions_paiement',
        'delai_paiement',
        'commission_pourcentage',
        'statut',
        'notes',
    ];

    protected $validationRules = [
        'type'      => 'required|max_length[50]',
        'nom'       => 'required|max_length[150]',
        'telephone' => 'permit_empty|max_length[50]',
        'email'     => 'permit_empty|valid_email|max_length[150]',
        'devise'    => 'permit_empty|max_length[10]',
        'statut'    => 'permit_empty|in_list[actif,inactif]',
    ];
}