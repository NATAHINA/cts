<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AssuranceModel;

class AssuranceController extends BaseController
{
    protected $assuranceModel;

    public function __construct()
    {
        $this->assuranceModel = new AssuranceModel();
    }

    /**
     * Liste des assurances
     */
    public function index()
    {
        $data = [
            'title' => 'Liste des assurances',
            'assurances' => $this->assuranceModel
                ->orderBy('id', 'DESC')
                ->findAll(),
        ];

        return view('assurances/index', $data);
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $data = [
            'title' => 'Ajouter une assurance',
            'validation' => service('validation'),
        ];

        return view('assurances/create', $data);
    }

    /**
     * Enregistrement
     */
    public function store()
    {
        $data = [
            'tenant_id'  => $this->request->getPost('tenant_id'),
            'nom'        => $this->request->getPost('nom'),
            'description'=> $this->request->getPost('description'),
            'telephone'  => $this->request->getPost('telephone'),
            'email'      => $this->request->getPost('email'),
            'adresse'    => $this->request->getPost('adresse'),
            'site_web'   => $this->request->getPost('site_web'),
            'statut'     => $this->request->getPost('statut'),
        ];

        if (! $this->assuranceModel->insert($data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->assuranceModel->errors());
        }

        return redirect()
            ->to('/assurances')
            ->with('success', 'Assurance ajoutée avec succès.');
    }

    /**
     * Afficher une assurance
     */
    public function show($id)
    {
        $assurance = $this->assuranceModel->find($id);

        if (! $assurance) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Assurance introuvable.'
            );
        }

        $data = [
            'title' => 'Détails de l\'assurance',
            'assurance' => $assurance,
        ];

        return view('assurances/show', $data);
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $assurance = $this->assuranceModel->find($id);

        if (! $assurance) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Assurance introuvable.'
            );
        }

        $data = [
            'title' => 'Modifier une assurance',
            'assurance' => $assurance,
            'validation' => service('validation'),
        ];

        return view('assurances/edit', $data);
    }

    /**
     * Mise à jour
     */
    public function update($id)
    {
        $assurance = $this->assuranceModel->find($id);

        if (! $assurance) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Assurance introuvable.'
            );
        }

        $data = [
            'tenant_id'  => $this->request->getPost('tenant_id'),
            'nom'        => $this->request->getPost('nom'),
            'description'=> $this->request->getPost('description'),
            'telephone'  => $this->request->getPost('telephone'),
            'email'      => $this->request->getPost('email'),
            'adresse'    => $this->request->getPost('adresse'),
            'site_web'   => $this->request->getPost('site_web'),
            'statut'     => $this->request->getPost('statut'),
        ];

        if (! $this->assuranceModel->update($id, $data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->assuranceModel->errors());
        }

        return redirect()
            ->to('/assurances')
            ->with('success', 'Assurance modifiée avec succès.');
    }

    /**
     * Suppression
     */
    public function delete($id)
    {
        $assurance = $this->assuranceModel->find($id);

        if (! $assurance) {
            return redirect()
                ->to('/assurances')
                ->with('error', 'Assurance introuvable.');
        }

        $this->assuranceModel->delete($id);

        return redirect()
            ->to('/assurances')
            ->with('success', 'Assurance supprimée avec succès.');
    }
}