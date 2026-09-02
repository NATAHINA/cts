<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $tenants = $this->db
            ->table('tenants')
            ->select('id')
            ->get()
            ->getResultArray();

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

        foreach ($tenants as $tenant) {
            foreach ($roles as $role) {
                $exists = $this->db
                    ->table('roles')
                    ->where('tenant_id', $tenant['id'])
                    ->where('code', $role['code'])
                    ->countAllResults();

                if ($exists === 0) {

                    $this->db->table('roles')->insert([
                        'tenant_id'   => $tenant['id'],
                        'code'        => $role['code'],
                        'libelle'     => $role['libelle'],
                        'description' => $role['description'],
                        'created_at'  => date('Y-m-d H:i:s'),
                        'updated_at'  => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }
}