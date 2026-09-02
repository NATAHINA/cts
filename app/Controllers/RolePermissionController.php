<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class RolePermissionController extends BaseController
{
    /**
     * Afficher les permissions d'un rôle
     */
    public function edit($roleId)
    {
        $db = db_connect();
        $tenantId = $this->tenantId();

        // Vérifier que le rôle appartient bien au tenant connecté
        $role = $db
            ->table('roles')
            ->where('id', $roleId)
            ->where('tenant_id', $tenantId)
            ->get()
            ->getRowArray();

        if (! $role) {
            return redirect()
                ->to(site_url('roles'))
                ->with('error', 'Rôle introuvable.');
        }

        // Récupérer toutes les permissions
        $permissions = $db
            ->table('permissions')
            ->select('id, code, libelle, module, action, description')
            ->orderBy('module', 'ASC')
            ->orderBy('action', 'ASC')
            ->get()
            ->getResultArray();

        // Permissions actuellement attribuées au rôle
        $selectedPermissions = $db
            ->table('role_permissions')
            ->select('permission_id')
            ->where('role_id', $roleId)
            ->get()
            ->getResultArray();

        $selectedPermissionIds = array_map(
            'intval',
            array_column($selectedPermissions, 'permission_id')
        );

        return view('roles/permissions', [
            'title'                 => 'Permissions - ' . $role['libelle'],
            'role'                  => $role,
            'permissions'           => $permissions,
            'selectedPermissionIds' => $selectedPermissionIds,
        ]);
    }


    /**
     * Enregistrer les permissions d'un rôle
     */
    public function update($roleId)
    {
        $db = db_connect();
        $tenantId = $this->tenantId();

        // Vérifier que le rôle appartient bien au tenant connecté
        $role = $db
            ->table('roles')
            ->where('id', $roleId)
            ->where('tenant_id', $tenantId)
            ->get()
            ->getRowArray();

        if (! $role) {
            return redirect()
                ->to(site_url('roles'))
                ->with('error', 'Rôle introuvable.');
        }

        // Récupérer les permissions envoyées par le formulaire
        $permissionIds = $this->request->getPost('permissions');

        if (! is_array($permissionIds)) {
            $permissionIds = [];
        }

        // Nettoyage + conversion en entiers
        $permissionIds = array_values(
            array_unique(
                array_filter(
                    array_map('intval', $permissionIds),
                    static fn ($id) => $id > 0
                )
            )
        );

        $db->transStart();

        // Supprimer les anciennes permissions
        $db
            ->table('role_permissions')
            ->where('role_id', $roleId)
            ->delete();

        // Ajouter les nouvelles permissions
        if (! empty($permissionIds)) {

            $rows = [];

            foreach ($permissionIds as $permissionId) {

                // Vérifier que la permission existe
                $permissionExists = $db
                    ->table('permissions')
                    ->where('id', $permissionId)
                    ->countAllResults();

                if ($permissionExists > 0) {
                    $rows[] = [
                        'role_id'       => $roleId,
                        'permission_id' => $permissionId,
                    ];
                }
            }

            if (! empty($rows)) {
                $db
                    ->table('role_permissions')
                    ->insertBatch($rows);
            }
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible d\'enregistrer les permissions.'
                );
        }

        return redirect()
            ->to(site_url('roles'))
            ->with(
                'success',
                'Les permissions du rôle "' .
                $role['libelle'] .
                '" ont été mises à jour.'
            );
    }
}