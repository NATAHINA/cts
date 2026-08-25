<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table         = 'password_resets';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['email', 'token', 'expires_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Crée un token pour l'email donné, invalide les anciens,
     * et retourne le token EN CLAIR (à envoyer par email).
     * Seul son hash est stocké en base.
     */
    public function createTokenForEmail(string $email): string
    {
        $this->where('email', $email)->delete();

        $rawToken    = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $rawToken);

        $this->insert([
            'email'      => $email,
            'token'      => $hashedToken,
            'expires_at' => date('Y-m-d H:i:s', time() + 3600), // valable 1h
        ]);

        return $rawToken;
    }

    public function findValidToken(string $rawToken): ?array
    {
        $hashedToken = hash('sha256', $rawToken);

        $row = $this->where('token', $hashedToken)
                    ->where('expires_at >=', date('Y-m-d H:i:s'))
                    ->first();

        return $row ?: null;
    }

    public function deleteForEmail(string $email): void
    {
        $this->where('email', $email)->delete();
    }
}