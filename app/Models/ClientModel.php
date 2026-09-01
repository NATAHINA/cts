<?php

namespace App\Models;

class ClientModel extends TenantModel
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields = [
        'tenant_id',
        'type_client',
        'nom',
        'prenom',
        'entreprise',
        'telephone',
        'email',
        'adresse',
        'ville',
        'pays',
        'nationalite',
        'statut',
        'notes',
    ];

    protected $validationRules = [
        'nom' => 'required|max_length[150]',
        'email' => 'permit_empty|valid_email|max_length[150]',
        'telephone' => 'permit_empty|max_length[50]',
    ];
}
