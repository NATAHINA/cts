<?php

namespace App\Models;

use CodeIgniter\Model;

class AssuranceModel extends Model
{
    protected $table            = 'assurances';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';

    protected $allowedFields = [
        'tenant_id',
        'nom',
        'description',
        'telephone',
        'email',
        'adresse',
        'site_web',
        'statut',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nom' => [
            'label' => 'Nom',
            'rules' => 'required|min_length[2]|max_length[255]',
        ],
        'email' => [
            'label' => 'Email',
            'rules' => 'permit_empty|valid_email|max_length[255]',
        ],
        'telephone' => [
            'label' => 'Téléphone',
            'rules' => 'permit_empty|max_length[50]',
        ],
        'site_web' => [
            'label' => 'Site web',
            'rules' => 'permit_empty|valid_url|max_length[255]',
        ],
        'statut' => [
            'label' => 'Statut',
            'rules' => 'required|in_list[actif,inactif]',
        ],
    ];

    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom est obligatoire.',
            'min_length' => 'Le nom doit contenir au moins 2 caractères.',
        ],
        'email' => [
            'valid_email' => 'Veuillez saisir une adresse email valide.',
        ],
    ];
}