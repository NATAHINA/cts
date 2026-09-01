<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'tenant_id'   => null,          // rôle global (tous les tenants)
                'code'        => 'admin',
                'libelle'     => 'Administrateur',
                'description' => 'Accès complet à toutes les fonctionnalités',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'tenant_id'   => null,
                'code'        => 'commercial',
                'libelle'     => 'Commercial',
                'description' => 'Gestion des clients, demandes et cotations',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'tenant_id'   => null,
                'code'        => 'operationnel',
                'libelle'     => 'Opérationnel',
                'description' => 'Gestion des réservations et des départs',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'tenant_id'   => null,
                'code'        => 'comptable',
                'libelle'     => 'Comptable',
                'description' => 'Gestion des paiements et factures',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        // Évite les doublons si on relance le seeder
        foreach ($data as $role) {
            $exists = $this->db
                ->table('roles')
                ->where('code', $role['code'])
                ->where('tenant_id', null)
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('roles')->insert($role);
            }
        }
    }
}