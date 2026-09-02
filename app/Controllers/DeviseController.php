<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DeviseModel;

class DeviseController extends BaseController
{
    protected $deviseModel;

    public function __construct()
    {
        $this->deviseModel = new DeviseModel();
    }
    /**
     * Liste des devises
     */
    public function index()
    {
        $tenantId = $this->tenantId();

        $devises = $this->deviseModel
            ->where('tenant_id', $tenantId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('code', 'ASC')
            ->findAll();

        return view('devises/index', [
            'title'   => 'Devises',
            'devises' => $devises,
        ]);
    }

    /**
     * Formulaire de création
     */
    public function new()
    {
        return view('devises/form', [
            'title'  => 'Nouvelle devise',
            'devise' => null,
        ]);
    }

    /**
     * Création
     */
    public function create()
    {
        $tenantId = $this->tenantId();

        $rules = [
            'code'        => 'required|min_length[3]|max_length[5]|alpha',
            'nom'         => 'required|min_length[2]|max_length[50]',
            'symbole'     => 'required|max_length[10]',
            'taux_change' => 'required|decimal',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $code = strtoupper(trim($this->request->getPost('code')));

        // Vérifie l'unicité du code pour ce tenant
        $existe = $this->deviseModel
            ->where('tenant_id', $tenantId)
            ->where('code', $code)
            ->first();

        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ce code devise existe déjà.');
        }

        $isDefault = (int) $this->request->getPost('is_default');

        // Si on met cette devise par défaut → on retire le flag des autres
        if ($isDefault === 1) {
            $this->deviseModel
                ->where('tenant_id', $tenantId)
                ->set(['is_default' => 0])
                ->update();
        }

        $data = [
            'tenant_id'   => $tenantId,
            'code'        => $code,
            'nom'         => $this->request->getPost('nom'),
            'symbole'     => $this->request->getPost('symbole'),
            'taux_change' => (float) $this->request->getPost('taux_change'),
            'is_default'  => $isDefault,
            'actif'       => 1,
        ];

        if (! $this->deviseModel->insert($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->deviseModel->errors());
        }

        return redirect()->to(site_url('devises'))
            ->with('success', 'Devise créée avec succès.');
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $tenantId = $this->tenantId();

        $devise = $this->deviseModel
            ->where('id', $id)
            ->where('tenant_id', $tenantId)
            ->first();

        if (! $devise) {
            return redirect()->to(site_url('devises'))
                ->with('error', 'Devise introuvable.');
        }

        return view('devises/form', [
            'title'  => 'Modifier la devise',
            'devise' => $devise,
        ]);
    }

    /**
     * Mise à jour
     */
    public function update($id)
    {
        $tenantId = $this->tenantId();

        $devise = $this->deviseModel
            ->where('id', $id)
            ->where('tenant_id', $tenantId)
            ->first();

        if (! $devise) {
            return redirect()->to(site_url('devises'))
                ->with('error', 'Devise introuvable.');
        }

        $rules = [
            'code'        => 'required|min_length[3]|max_length[5]|alpha',
            'nom'         => 'required|min_length[2]|max_length[50]',
            'symbole'     => 'required|max_length[10]',
            'taux_change' => 'required|decimal',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $code = strtoupper(trim($this->request->getPost('code')));

        // Unicité du code (sauf pour la devise actuelle)
        $existe = $this->deviseModel
            ->where('tenant_id', $tenantId)
            ->where('code', $code)
            ->where('id !=', $id)
            ->first();

        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ce code devise existe déjà.');
        }

        $isDefault = (int) $this->request->getPost('is_default');

        if ($isDefault === 1) {
            $this->deviseModel
                ->where('tenant_id', $tenantId)
                ->where('id !=', $id)
                ->set(['is_default' => 0])
                ->update();
        }

        $data = [
            'code'        => $code,
            'nom'         => $this->request->getPost('nom'),
            'symbole'     => $this->request->getPost('symbole'),
            'taux_change' => (float) $this->request->getPost('taux_change'),
            'is_default'  => $isDefault,
        ];

        if (! $this->deviseModel->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->deviseModel->errors());
        }

        return redirect()->to(site_url('devises'))
            ->with('success', 'Devise mise à jour.');
    }

    /**
     * Suppression
     */
    public function delete($id)
    {
        $tenantId = $this->tenantId();

        $devise = $this->deviseModel
            ->where('id', $id)
            ->where('tenant_id', $tenantId)
            ->first();

        if (! $devise) {
            return redirect()->to(site_url('devises'))
                ->with('error', 'Devise introuvable.');
        }

        // On empêche la suppression de la devise par défaut
        if (! empty($devise['is_default'])) {
            return redirect()->to(site_url('devises'))
                ->with('error', 'Impossible de supprimer la devise par défaut.');
        }

        $this->deviseModel->delete($id);

        return redirect()->to(site_url('devises'))
            ->with('success', 'Devise supprimée.');
    }

    /**
     * Définir comme devise par défaut
     */
    public function setDefault($id)
    {
        $tenantId = $this->tenantId();

        $devise = $this->deviseModel
            ->where('id', $id)
            ->where('tenant_id', $tenantId)
            ->first();

        if (! $devise) {
            return redirect()->to(site_url('devises'))
                ->with('error', 'Devise introuvable.');
        }

        // Retire le flag des autres
        $this->deviseModel
            ->where('tenant_id', $tenantId)
            ->set(['is_default' => 0])
            ->update();

        // Met celle-ci par défaut + taux à 1
        $this->deviseModel->update($id, [
            'is_default'  => 1,
            'taux_change' => 1,
        ]);

        return redirect()->to(site_url('devises'))
            ->with('success', 'Devise définie par défaut.');
    }
}