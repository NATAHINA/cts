<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;

class UtilisateurController extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    private function tenantId(): int
    {
        return (int) session('tenant_id');
    }

    public function index()
    {
        $users = $this->userModel
            ->select('users.*, roles.libelle AS role_libelle')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->where('users.tenant_id', $this->tenantId())
            ->orderBy('users.nom', 'ASC')
            ->findAll();

        return view('utilisateurs/index', [
            'title' => 'Utilisateurs & rôles',
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('utilisateurs/form', [
            'title' => 'Nouvel utilisateur',
            'roles' => $this->roleModel
                ->where('tenant_id', $this->tenantId())
                ->orWhere('tenant_id', null)
                ->orderBy('libelle', 'ASC')
                ->findAll(),
        ]);
    }

    public function store()
    {
        $data = [
            'tenant_id'  => $this->tenantId(),
            'role_id'    => $this->request->getPost('role_id') ?: null,
            'nom'        => $this->request->getPost('nom'),
            'prenom'     => $this->request->getPost('prenom'),
            'email'      => $this->request->getPost('email'),
            'telephone'  => $this->request->getPost('telephone') ?: null,
            'password'   => $this->request->getPost('password'),
            'actif'      => $this->request->getPost('actif') ? 1 : 0,
        ];

        if (! $this->userModel->insert($data)) {
            return redirect()->back()->withInput()
                ->with('error', 'Impossible de créer l\'utilisateur.');
        }

        return redirect()->to(site_url('utilisateurs'))
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit($id)
    {
        $user = $this->userModel
            ->where('id', $id)
            ->where('tenant_id', $this->tenantId())
            ->first();

        if (! $user) {
            return redirect()->to(site_url('utilisateurs'))
                ->with('error', 'Utilisateur introuvable.');
        }

        return view('utilisateurs/form', [
            'title' => 'Modifier l\'utilisateur',
            'user'  => $user,
            'roles' => $this->roleModel
                ->where('tenant_id', $this->tenantId())
                ->orWhere('tenant_id', null)
                ->orderBy('libelle', 'ASC')
                ->findAll(),
        ]);
    }

    public function update($id)
    {
        $user = $this->userModel
            ->where('id', $id)
            ->where('tenant_id', $this->tenantId())
            ->first();

        if (! $user) {
            return redirect()->to(site_url('utilisateurs'))
                ->with('error', 'Utilisateur introuvable.');
        }

        $data = [
            'role_id'   => $this->request->getPost('role_id') ?: null,
            'nom'       => $this->request->getPost('nom'),
            'prenom'    => $this->request->getPost('prenom'),
            'email'     => $this->request->getPost('email'),
            'telephone' => $this->request->getPost('telephone') ?: null,
            'actif'     => $this->request->getPost('actif') ? 1 : 0,
        ];

        $password = $this->request->getPost('password');
        if (! empty($password)) {
            $data['password'] = $password;
        }

        $this->userModel->update($id, $data);

        return redirect()->to(site_url('utilisateurs'))
            ->with('success', 'Utilisateur mis à jour.');
    }

    public function delete($id)
    {
        $user = $this->userModel
            ->where('id', $id)
            ->where('tenant_id', $this->tenantId())
            ->first();

        if (! $user) {
            return redirect()->to(site_url('utilisateurs'))
                ->with('error', 'Utilisateur introuvable.');
        }

        // Empêcher de se supprimer soi-même
        if ((int) $id === (int) session('user_id')) {
            return redirect()->to(site_url('utilisateurs'))
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $this->userModel->delete($id);

        return redirect()->to(site_url('utilisateurs'))
            ->with('success', 'Utilisateur supprimé.');
    }
}