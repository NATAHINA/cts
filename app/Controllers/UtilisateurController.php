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

    /**
     * Liste des utilisateurs
     */
    public function index(){
        $tenantId = $this->tenantId();

        $users = $this->userModel
            ->select('users.*, roles.libelle AS role_libelle, roles.code AS role_code')
            ->join(
                'roles',
                'roles.id = users.role_id',
                'left'
            )
            ->where('users.tenant_id', $tenantId)
            ->orderBy('users.nom', 'ASC')
            ->findAll();

        return view('utilisateurs/index', [
            'title' => 'Utilisateurs & rôles',
            'users' => $users,
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create(){
        $roles = $this->roleModel
            ->where('tenant_id', $this->tenantId())
            ->orderBy('libelle', 'ASC')
            ->findAll();

        return view('utilisateurs/form', [
            'title' => 'Nouvel utilisateur',
            'roles' => $roles,
        ]);
    }

    /**
     * Création d'un utilisateur
     */
    public function store(){
        $tenantId = $this->tenantId();

        $roleId = (int) $this->request->getPost('role_id');

        /*
         * Vérifier que le rôle appartient bien au tenant courant.
         */
        if ($roleId > 0) {
            $role = $this->roleModel
                ->where('id', $roleId)
                ->where('tenant_id', $tenantId)
                ->first();

            if (! $role) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Le rôle sélectionné est invalide.');
            }
        } else {
            $roleId = null;
        }

        $data = [
            'tenant_id' => $tenantId,
            'role_id'   => $roleId,
            'nom'       => trim($this->request->getPost('nom')),
            'prenom'    => trim($this->request->getPost('prenom')),
            'email'     => trim($this->request->getPost('email')),
            'telephone' => trim($this->request->getPost('telephone')) ?: null,
            'password'  => $this->request->getPost('password'),
            'actif'     => $this->request->getPost('actif') ? 1 : 0,
        ];

        if (! $this->userModel->insert($data)) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible de créer l\'utilisateur.'
                );
        }

        return redirect()
            ->to(site_url('utilisateurs'))
            ->with(
                'success',
                'Utilisateur créé avec succès.'
            );
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $tenantId = $this->tenantId();

        $user = $this->userModel
            ->where('id', $id)
            ->where('tenant_id', $tenantId)
            ->first();

        if (! $user) {
            return redirect()
                ->to(site_url('utilisateurs'))
                ->with(
                    'error',
                    'Utilisateur introuvable.'
                );
        }

        $roles = $this->roleModel
            ->where('tenant_id', $tenantId)
            ->orderBy('libelle', 'ASC')
            ->findAll();

        return view('utilisateurs/form', [
            'title' => 'Modifier l\'utilisateur',
            'user'  => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Modification d'un utilisateur
     */
    public function update($id)
    {
        $tenantId = $this->tenantId();

        $user = $this->userModel
            ->where('id', $id)
            ->where('tenant_id', $tenantId)
            ->first();

        if (! $user) {
            return redirect()
                ->to(site_url('utilisateurs'))
                ->with(
                    'error',
                    'Utilisateur introuvable.'
                );
        }

        $roleId = (int) $this->request->getPost('role_id');

        /*
         * Vérifier le rôle du tenant.
         */
        if ($roleId > 0) {

            $role = $this->roleModel
                ->where('id', $roleId)
                ->where('tenant_id', $tenantId)
                ->first();

            if (! $role) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Le rôle sélectionné est invalide.'
                    );
            }

        } else {
            $roleId = null;
        }

        $data = [
            'role_id'   => $roleId,
            'nom'       => trim($this->request->getPost('nom')),
            'prenom'    => trim($this->request->getPost('prenom')),
            'email'     => trim($this->request->getPost('email')),
            'telephone' => trim($this->request->getPost('telephone')) ?: null,
            'actif'     => $this->request->getPost('actif') ? 1 : 0,
        ];

        /*
         * Modifier le mot de passe uniquement
         * s'il a été renseigné.
         */
        $password = $this->request->getPost('password');

        if (! empty($password)) {
            $data['password'] = $password;
        }

        if (! $this->userModel->update($id, $data)) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible de mettre à jour l\'utilisateur.'
                );
        }

        return redirect()
            ->to(site_url('utilisateurs'))
            ->with(
                'success',
                'Utilisateur mis à jour.'
            );
    }

    /**
     * Suppression
     */
    public function delete($id)
    {
        $user = $this->userModel
            ->where('id', $id)
            ->where('tenant_id', $this->tenantId())
            ->first();

        if (! $user) {
            return redirect()
                ->to(site_url('utilisateurs'))
                ->with(
                    'error',
                    'Utilisateur introuvable.'
                );
        }

        /*
         * Empêcher l'utilisateur connecté
         * de supprimer son propre compte.
         */
        if ((int) $id === (int) session('user_id')) {

            return redirect()
                ->to(site_url('utilisateurs'))
                ->with(
                    'error',
                    'Vous ne pouvez pas supprimer votre propre compte.'
                );
        }

        $this->userModel->delete($id);

        return redirect()
            ->to(site_url('utilisateurs'))
            ->with(
                'success',
                'Utilisateur supprimé.'
            );
    }
}