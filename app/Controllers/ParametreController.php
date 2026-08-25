<?php

namespace App\Controllers;

use App\Models\TenantAccountModel;
use App\Models\UserModel;

class ParametreController extends BaseController
{
    public function index()
    {
        $tenantModel = new TenantAccountModel();

        return view('parametres/index', [
            'title'  => 'Paramètres',
            'tenant' => $tenantModel->find(session('tenant_id')),
        ]);
    }

    public function utilisateurs()
    {
        $userModel = new UserModel();

        return view('parametres/utilisateurs', [
            'title' => 'Utilisateurs de l\'agence',
            'users' => $userModel->where('tenant_id', session('tenant_id'))->orderBy('nom', 'ASC')->findAll(),
        ]);
    }

    public function storeUtilisateur()
    {
        if (session('user_role') !== 'admin') {
            return redirect()->to('/parametres/utilisateurs')->with('error', "Seul un administrateur peut ajouter un utilisateur.");
        }

        $rules = [
            'nom'      => 'required|min_length[2]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $userModel = new UserModel();
        $userModel->insert([
            'tenant_id'     => session('tenant_id'),
            'nom'           => $this->request->getPost('nom'),
            'prenom'        => $this->request->getPost('prenom'),
            'email'         => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role') === 'admin' ? 'admin' : 'agent',
            'statut'        => 'actif',
        ]);

        return redirect()->to('/parametres/utilisateurs')->with('success', 'Utilisateur ajouté.');
    }
}
