<?php

namespace App\Models;

class UserModel extends TenantModel
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'tenant_id',
        'role_id',
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'actif',
        'dernier_login',
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    public function findByEmail(string $email): ?array
    {
        return $this
            ->where('email', $email)
            ->first();
    }

    protected function hashPassword(array $data)
    {
        if (! empty($data['data']['password'])) {
            $data['data']['password'] = password_hash(
                $data['data']['password'],
                PASSWORD_DEFAULT
            );
        } else {
            unset($data['data']['password']);
        }
        return $data;
    }
}