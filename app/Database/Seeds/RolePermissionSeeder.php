<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissionModel = $this->db->table('permissions');
        $roleModel       = $this->db->table('roles');
        $rolePermission  = $this->db->table('role_permissions');

        $tenants = $this->db
            ->table('tenants')
            ->get()
            ->getResultArray();

        /*
         * ADMIN
         * Toutes les permissions.
         */
        $adminPermissions = $permissionModel
            ->select('id')
            ->get()
            ->getResultArray();

        /*
         * COMMERCIAL
         */
        $commercialPermissions = [
            'demandes.view',
            'demandes.create',
            'demandes.edit',
            'demandes.delete',
            'demandes.print',

            'cotations.view',
            'cotations.create',
            'cotations.edit',
            'cotations.delete',
            'cotations.print',
            'cotations.send',

            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.print',

            'fournisseurs.view',

            'destinations.view',

            'hotels.view',

            'vols.view',

            'excursions.view',

            'transferts.view',

            'reservations.view',
            'reservations.create',
            'reservations.edit',
            'reservations.print',
        ];

        /*
         * OPERATIONNEL
         */
        $operationnelPermissions = [
            'clients.view',

            'fournisseurs.view',

            'destinations.view',
            'hotels.view',
            'vols.view',
            'excursions.view',
            'transferts.view',

            'reservations.view',
            'reservations.create',
            'reservations.edit',
            'reservations.delete',
            'reservations.print',
        ];

        /*
         * COMPTABLE
         */
        $comptablePermissions = [
            'clients.view',

            'factures.view',
            'factures.create',
            'factures.edit',
            'factures.delete',
            'factures.print',
            'factures.export',

            'paiements.view',
            'paiements.create',
            'paiements.edit',
            'paiements.delete',
            'paiements.print',

            'rapports.view',
            'rapports.export',
        ];

        foreach ($tenants as $tenant) {

            /*
             * ADMIN
             */
            $adminRole = $roleModel
                ->where('tenant_id', $tenant['id'])
                ->where('code', 'admin')
                ->get()
                ->getRowArray();

            if ($adminRole) {

                foreach ($adminPermissions as $permission) {

                    $this->insertRolePermission(
                        $adminRole['id'],
                        $permission['id'],
                        $rolePermission
                    );
                }
            }

            /*
             * COMMERCIAL
             */
            $commercialRole = $roleModel
                ->where('tenant_id', $tenant['id'])
                ->where('code', 'commercial')
                ->get()
                ->getRowArray();

            if ($commercialRole) {

                foreach ($commercialPermissions as $permissionCode) {

                    $permission = $permissionModel
                        ->where('code', $permissionCode)
                        ->get()
                        ->getRowArray();

                    if ($permission) {

                        $this->insertRolePermission(
                            $commercialRole['id'],
                            $permission['id'],
                            $rolePermission
                        );
                    }
                }
            }

            /*
             * OPERATIONNEL
             */
            $operationnelRole = $roleModel
                ->where('tenant_id', $tenant['id'])
                ->where('code', 'operationnel')
                ->get()
                ->getRowArray();

            if ($operationnelRole) {

                foreach ($operationnelPermissions as $permissionCode) {

                    $permission = $permissionModel
                        ->where('code', $permissionCode)
                        ->get()
                        ->getRowArray();

                    if ($permission) {

                        $this->insertRolePermission(
                            $operationnelRole['id'],
                            $permission['id'],
                            $rolePermission
                        );
                    }
                }
            }

            /*
             * COMPTABLE
             */
            $comptableRole = $roleModel
                ->where('tenant_id', $tenant['id'])
                ->where('code', 'comptable')
                ->get()
                ->getRowArray();

            if ($comptableRole) {

                foreach ($comptablePermissions as $permissionCode) {

                    $permission = $permissionModel
                        ->where('code', $permissionCode)
                        ->get()
                        ->getRowArray();

                    if ($permission) {

                        $this->insertRolePermission(
                            $comptableRole['id'],
                            $permission['id'],
                            $rolePermission
                        );
                    }
                }
            }
        }
    }

    private function insertRolePermission(
        int $roleId,
        int $permissionId,
        $table
    ): void {

        $exists = $table
            ->where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->countAllResults();

        if ($exists === 0) {

            $table->insert([
                'role_id'       => $roleId,
                'permission_id' => $permissionId,
            ]);
        }
    }
}