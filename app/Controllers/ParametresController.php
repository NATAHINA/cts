<?php

namespace App\Controllers;

use App\Models\TenantAccountModel;

class ParametresController extends BaseController
{
    protected function tenantId(): int
    {
        return (int) session('tenant_id');
    }

    /**
     * Afficher les paramètres de l'agence
     */
    public function index()
    {
        $tenantId = $this->tenantId();

        if (!$tenantId) {
            return redirect()
                ->to(site_url('login'))
                ->with('error', 'Session agence introuvable.');
        }

        $model = new TenantAccountModel();

        $agence = $model
            ->where('id', $tenantId)
            ->first();

        if (!$agence) {
            return redirect()
                ->to(site_url('/'))
                ->with('error', 'Informations de l\'agence introuvables.');
        }

        return view('parametres/index', [
            'title'  => 'Paramètres de l’agence',
            'agence' => $agence,
        ]);
    }

    /**
     * Enregistrer les informations de l'agence
     */
    public function update()
    {
        $tenantId = $this->tenantId();

        if (!$tenantId) {
            return redirect()
                ->to(site_url('login'))
                ->with('error', 'Session agence introuvable.');
        }

        $model = new TenantAccountModel();

        $agence = $model
            ->where('id', $tenantId)
            ->first();

        if (!$agence) {
            return redirect()
                ->back()
                ->with('error', 'Informations de l\'agence introuvables.');
        }

        $data = [
            'nom_agence'          => trim($this->request->getPost('nom_agence')),
            'email_contact'       => trim($this->request->getPost('email_contact')),
            'telephone'           => trim($this->request->getPost('telephone')),
            'adresse'             => trim($this->request->getPost('adresse')),
            'nif'                 => trim($this->request->getPost('nif')),
            'stat'                => trim($this->request->getPost('stat')),
            'rcs'                 => trim($this->request->getPost('rcs')),
            'site_web'            => trim($this->request->getPost('site_web')),
            'devise_defaut'       => trim($this->request->getPost('devise_defaut')),
            'tva'                 => $this->request->getPost('tva'),
            'prefixe_cotation'    => trim($this->request->getPost('prefixe_cotation')),
            'prefixe_reservation' => trim($this->request->getPost('prefixe_reservation')),
            'prefixe_facture'     => trim($this->request->getPost('prefixe_facture')),
            'conditions_generales'=> $this->request->getPost('conditions_generales'),
            'pied_page_document'  => $this->request->getPost('pied_page_document'),
        ];

        /*
         * Validation minimale
         */
        if ($data['nom_agence'] === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Le nom de l\'agence est obligatoire.');
        }

        if (
            $data['email_contact'] !== '' &&
            !filter_var($data['email_contact'], FILTER_VALIDATE_EMAIL)
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'L\'adresse e-mail est invalide.');
        }

        /*
         * Gestion du logo
         */
        $logo = $this->request->getFile('logo');

        if ($logo && $logo->isValid() && !$logo->hasMoved()) {

            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp',
            ];

            if (!in_array($logo->getMimeType(), $allowedTypes, true)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Le logo doit être au format JPG, PNG ou WEBP.');
            }

            /*
             * Taille maximale : 2 Mo
             */
            if ($logo->getSize() > 2 * 1024 * 1024) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Le logo ne doit pas dépasser 2 Mo.');
            }

            /*
             * Nom unique
             */
            $newName = $logo->getRandomName();

            /*
             * Dossier de stockage
             */
            $uploadPath = FCPATH . 'uploads/agences';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            /*
             * Supprimer l'ancien logo
             */
            if (!empty($agence['logo'])) {

                $oldLogo = FCPATH . 'uploads/agences/' . $agence['logo'];

                if (is_file($oldLogo)) {
                    unlink($oldLogo);
                }
            }

            /*
             * Déplacement du nouveau logo
             */
            $logo->move($uploadPath, $newName);

            $data['logo'] = $newName;
        }

        /*
         * Mise à jour du tenant
         */
        $updated = $model
            ->where('id', $tenantId)
            ->set($data)
            ->update();

        if (!$updated) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Impossible de mettre à jour les informations.');
        }

        return redirect()
            ->to(site_url('parametres'))
            ->with('success', 'Les informations de l’agence ont été mises à jour.');
    }

    /**
     * Supprimer le logo
     */
    public function deleteLogo()
    {
        $tenantId = $this->tenantId();

        if (!$tenantId) {
            return redirect()->to(site_url('login'));
        }

        $model = new TenantAccountModel();

        $agence = $model
            ->where('id', $tenantId)
            ->first();

        if (!$agence) {
            return redirect()
                ->back()
                ->with('error', 'Agence introuvable.');
        }

        if (!empty($agence['logo'])) {

            $logoPath = FCPATH . 'uploads/agences/' . $agence['logo'];

            if (is_file($logoPath)) {
                unlink($logoPath);
            }

            $model
                ->where('id', $tenantId)
                ->set(['logo' => null])
                ->update();
        }

        return redirect()
            ->to(site_url('parametres'))
            ->with('success', 'Logo supprimé.');
    }
}