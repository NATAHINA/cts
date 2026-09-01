<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\TenantAccountModel;
use App\Models\UserModel;
use App\Models\PasswordResetModel;
use \App\Models\RoleModel;

class AuthController extends BaseController
{
    // -------------------------------------------------------------
    // LOGIN
    // -------------------------------------------------------------
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        return view('auth/login');
    }

    public function attemptLogin(){
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Merci de renseigner un email valide et un mot de passe.'
                );
        }

        $email    = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');

        $userModel = new UserModel();

        $user = $userModel->findByEmail($email);

        // Vérification utilisateur + mot de passe
        if (
            ! $user ||
            empty($user['password']) ||
            ! password_verify($password, $user['password'])
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Identifiants incorrects.');
        }

        // Vérification compte actif
        if ((int) $user['actif'] !== 1) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ce compte utilisateur est désactivé.');
        }

        // Récupération de l'agence
        $tenantModel = new TenantAccountModel();

        $tenant = $tenantModel->find($user['tenant_id']);

        if (! $tenant || $tenant['statut'] !== 'actif') {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Ce compte agence est suspendu. Contactez le support.'
                );
        }

        // Mise à jour de la dernière connexion
        $userModel->update($user['id'], [
            'dernier_login' => date('Y-m-d H:i:s'),
        ]);

        // Session
        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'user_nom'   => trim(
                ($user['nom'] ?? '') . ' ' . $user['prenom']
            ),
            'user_role'  => $user['role_id'],
            'tenant_id'  => $tenant['id'],
            'tenant_nom' => $tenant['nom_agence'],
        ]);

        return redirect()->to('/');
    }

    // -------------------------------------------------------------
    // INSCRIPTION (crée l'agence + le premier utilisateur admin)
    // -------------------------------------------------------------
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        return view('auth/register');
    }

    public function attemptRegister()
    {
        log_message('debug', '--- attemptRegister called ---');

        $rules = [
            'nom_agence' => 'required|min_length[2]|max_length[150]',
            'nom'        => 'required|min_length[2]|max_length[100]',
            'prenom'     => 'permit_empty|max_length[100]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'password'   => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $tenantModel = new TenantAccountModel();
        $userModel   = new UserModel();
        $roleModel = new RoleModel();

        $db = \Config\Database::connect();
        $db->transStart();

        $slugBase = url_title($this->request->getPost('nom_agence'), '-', true);
        $slug     = $slugBase;
        $suffix   = 1;
        while ($tenantModel->where('slug', $slug)->first()) {
            $slug = $slugBase . '-' . (++$suffix);
        }

        $tenantId = $tenantModel->insert([
            'nom_agence'    => $this->request->getPost('nom_agence'),
            'slug'          => $slug,
            'email_contact' => $this->request->getPost('email'),
            'plan'          => 'essai',
            'statut'        => 'actif',
        ]);

        $role = $roleModel
            ->where('tenant_id', $tenantId)
            ->where('code', 'admin')
            ->first();

        if (! $role) {
            $roleId = $roleModel->insert([
                'tenant_id'   => $tenantId,
                'code'        => 'admin',
                'libelle'     => 'Administrateur',
                'description' => 'Administrateur de l’agence',
            ]);
        } else {
            $roleId = $role['id'];
        }

        if ($tenantId === false) {
            log_message('error', 'Tenant insert failed: ' . json_encode($tenantModel->errors()));
        }

        $userId = $userModel->insert([
            'tenant_id' => $tenantId,
            'role_id'   => $roleId,
            'nom'       => $this->request->getPost('nom'),
            'prenom'    => $this->request->getPost('prenom'),
            'email'     => $this->request->getPost('email'),
            'password'  => $this->request->getPost('password'),
            'actif'     => 1,
        ]);

        if ($userId === false) {
            log_message('error', 'User insert failed: ' . json_encode($userModel->errors()));
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            log_message('error', 'DB transaction failed: ' . $db->error()['message']);
            return redirect()->back()->withInput()->with('error', "Une erreur est survenue lors de la création du compte. Merci de réessayer.");
        }

        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $userId,
            'user_nom'   => trim($this->request->getPost('nom') . ' ' . $this->request->getPost('prenom')),
            'user_role'  => 'admin',
            'tenant_id'  => $tenantId,
            'tenant_nom' => $this->request->getPost('nom_agence'),
        ]);

        return redirect()->to('/')->with('success', 'Bienvenue ! Votre espace agence a été créé.');
    }

    // -------------------------------------------------------------
    // LOGOUT
    // -------------------------------------------------------------
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }


        // -------------------------------------------------------------
    // MOT DE PASSE OUBLIÉ
    // -------------------------------------------------------------
    public function forgotPassword()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        return view('auth/forgot_password');
    }

    public function attemptForgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Merci de renseigner une adresse email valide.');
        }

        $email = $this->request->getPost('email');

        $userModel = new UserModel();
        $user      = $userModel->findByEmail($email);

        // Message identique que le compte existe ou non (on ne révèle jamais si un email est enregistré)
        $genericSuccess = "Si un compte est associé à cet email, un lien de réinitialisation vient d'être envoyé.";

        if (! $user) {
            return redirect()->to('/login')->with('success', $genericSuccess);
        }

        $resetModel = new PasswordResetModel();
        $rawToken   = $resetModel->createTokenForEmail($email);
        $resetLink  = site_url('reset-password/' . $rawToken);

        $emailService = \Config\Services::email();

        $emailService->setFrom(
            env('email.fromEmail'),
            env('email.fromName')
        );
        $emailService->setTo($email);
        $emailService->setSubject('Réinitialisation de votre mot de passe — CTS');
        $emailService->setMailType('html');
        // $emailService->setCharset('utf-8');
        $emailService->setNewline("\r\n");
        $emailService->setCRLF("\r\n");

        $message = view('emails/reset_password', [
            'resetLink' => $resetLink,
            'email'     => $email,
        ]);

        $emailService->setMessage($message);

        if (!$emailService->send(false)) {
            log_message('error', 'Échec envoi email reset : ' . $emailService->printDebugger(['headers', 'subject', 'body']));
        }

        return redirect()->to('/login')->with('success', $genericSuccess);
    }

    public function resetPassword(string $token)
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $resetModel = new PasswordResetModel();
        $row        = $resetModel->findValidToken($token);

        if (! $row) {
            return redirect()->to('/login')->with('error', 'Ce lien de réinitialisation est invalide ou a expiré.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function attemptResetPassword()
    {
        $rules = [
            'token'            => 'required',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $token      = $this->request->getPost('token');
        $resetModel = new PasswordResetModel();
        $row        = $resetModel->findValidToken($token);

        if (! $row) {
            return redirect()->to('/login')->with('error', 'Ce lien de réinitialisation est invalide ou a expiré.');
        }

        $userModel = new UserModel();
        $user      = $userModel->findByEmail($row['email']);

        if (! $user) {
            return redirect()->to('/login')->with('error', 'Ce lien de réinitialisation est invalide ou a expiré.');
        }

        $userModel->update($user['id'], [
            'password' => $this->request->getPost('password'),
        ]);

        $resetModel->deleteForEmail($row['email']);

        return redirect()->to('/login')->with('success', 'Votre mot de passe a été réinitialisé. Vous pouvez vous connecter.');
    }
}
