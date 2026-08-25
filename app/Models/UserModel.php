<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Ce modèle hérite du Model CI4 standard (pas de TenantModel) :
 * lors de la connexion, on ne connaît pas encore le tenant_id en
 * session, il faut donc pouvoir chercher un utilisateur par email
 * sans filtre automatique. Le filtrage par tenant_id se fait alors
 * explicitement là où c'est nécessaire (ex : liste des utilisateurs
 * de l'agence dans ParametreController).
 */
class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'tenant_id', 'nom', 'prenom', 'email', 'password_hash',
        'role', 'statut', 'derniere_connexion',
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }
}
