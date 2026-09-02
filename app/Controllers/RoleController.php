<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RoleModel;

class RoleController extends BaseController
{
    protected $roleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }

    /**
     * Liste des rôles
     */
    public function index()
    {
        $roles = $this->roleModel
            ->where('tenant_id', $this->tenantId())
            ->orderBy('libelle', 'ASC')
            ->findAll();

        return view('roles/index', [
            'title' => 'Rôles',
            'roles' => $roles,
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('roles/form', [
            'title' => 'Nouveau rôle',
        ]);
    }

    /**
     * Enregistrer un rôle
     */
    public function store()
    {
        $tenantId = $this->tenantId();

        $code = trim($this->request->getPost('code'));

        $data = [
            'tenant_id'   => $tenantId,
            'code'        => $code,
            'libelle'     => trim($this->request->getPost('libelle')),
            'description' => trim($this->request->getPost('description')) ?: null,
        ];

        if (! $this->roleModel->insert($data)) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible de créer le rôle.'
                );
        }

        return redirect()
            ->to(site_url('roles'))
            ->with(
                'success',
                'Rôle créé avec succès.'
            );
    }

    /**
     * Modifier un rôle
     */
    public function edit($id)
    {
        $role = $this->roleModel
            ->where('id', $id)
            ->where('tenant_id', $this->tenantId())
            ->first();

        if (! $role) {
            return redirect()
                ->to(site_url('roles'))
                ->with(
                    'error',
                    'Rôle introuvable.'
                );
        }

        return view('roles/form', [
            'title' => 'Modifier le rôle',
            'role'  => $role,
        ]);
    }

    /**
     * Mettre à jour un rôle
     */
    public function update($id)
    {
        $role = $this->roleModel
            ->where('id', $id)
            ->where('tenant_id', $this->tenantId())
            ->first();

        if (! $role) {
            return redirect()
                ->to(site_url('roles'))
                ->with(
                    'error',
                    'Rôle introuvable.'
                );
        }

        $data = [
            'code'        => trim($this->request->getPost('code')),
            'libelle'     => trim($this->request->getPost('libelle')),
            'description' => trim($this->request->getPost('description')) ?: null,
        ];

        $this->roleModel->update($id, $data);

        return redirect()
            ->to(site_url('roles'))
            ->with(
                'success',
                'Rôle mis à jour.'
            );
    }

    /**
     * Supprimer un rôle
     */
    public function delete($id)
    {
        $role = $this->roleModel
            ->where('id', $id)
            ->where('tenant_id', $this->tenantId())
            ->first();

        if (! $role) {
            return redirect()
                ->to(site_url('roles'))
                ->with(
                    'error',
                    'Rôle introuvable.'
                );
        }

        /*
         * Vérifier si le rôle est utilisé.
         */
        $usersCount = $this->db
            ->table('users')
            ->where('tenant_id', $this->tenantId())
            ->where('role_id', $id)
            ->countAllResults();

        if ($usersCount > 0) {

            return redirect()
                ->to(site_url('roles'))
                ->with(
                    'error',
                    'Impossible de supprimer ce rôle : il est utilisé par un ou plusieurs utilisateurs.'
                );
        }

        /*
         * Supprimer les permissions du rôle.
         */
        $this->db
            ->table('role_permissions')
            ->where('role_id', $id)
            ->delete();

        $this->roleModel->delete($id);

        return redirect()
            ->to(site_url('roles'))
            ->with(
                'success',
                'Rôle supprimé.'
            );
    }
}