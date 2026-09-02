<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Config\Permissions;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = Permissions::all();

        foreach ($permissions as $permission) {

            $exists = $this->db
                ->table('permissions')
                ->where('code', $permission['code'])
                ->countAllResults();

            if ($exists === 0) {

                $this->db
                    ->table('permissions')
                    ->insert([
                        'code'        => $permission['code'],
                        'module'      => $permission['module'],
                        'action'      => $permission['action'],
                        'libelle'     => $permission['libelle'],
                        'description' => $permission['description'] ?? null,
                        'created_at'  => date('Y-m-d H:i:s'),
                        'updated_at'  => date('Y-m-d H:i:s'),
                    ]);
            }
        }
    }
}