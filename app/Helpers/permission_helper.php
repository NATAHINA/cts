<?php

if (! function_exists('can')) {

    function can(string $permission): bool
    {
        $session = session();

        $tenantId = (int) $session->get('tenant_id');
        $roleId   = (int) $session->get('user_role');

        if ($tenantId <= 0 || $roleId <= 0) {
            return false;
        }

        /*
         * Vérification du rôle :
         *
         * Le rôle doit obligatoirement appartenir
         * au tenant courant.
         */
        $role = db_connect()
            ->table('roles')
            ->select('id')
            ->where('id', $roleId)
            ->where('tenant_id', $tenantId)
            ->get()
            ->getRowArray();

        if (! $role) {
            return false;
        }

        /*
         * Vérification de la permission.
         */
        $permission = db_connect()
            ->table('role_permissions rp')
            ->select('p.id')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id', $roleId)
            ->where('p.code', $permission)
            ->get()
            ->getRowArray();

        return ! empty($permission);
    }
}