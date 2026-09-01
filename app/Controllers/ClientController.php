<?php

namespace App\Controllers;

use App\Models\ClientModel;

class ClientController extends BaseCrudController
{
    protected string $viewPath = 'clients';
    protected string $moduleTitle = 'Clients';

    protected $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    public function index()
    {
        $clients = $this->clientModel
            // ->where('tenant_id', session('tenant_id'))
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('clients/index', [
            'title'   => $this->moduleTitle,
            'clients' => $clients,
        ]);
    }

    public function create()
    {
        return view('clients/form', [
            'title'  => 'Nouveau client',
            'client' => null,
        ]);
    }

    
    public function store(){

        $data = $this->request->getPost();

        unset($data['tenant_id']);

        if (! $this->clientModel->save($data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->clientModel->errors());
        }

        return redirect()
            ->to(site_url('clients'))
            ->with('success', 'Client ajouté avec succès.');
    }


    public function show($id)
    {
        $client = $this->clientModel
            ->where('tenant_id', session('tenant_id'))
            ->find($id);

        if (! $client) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('clients/show', [
            'title'  => 'Détails du client',
            'client' => $client,
        ]);
    }

    public function edit($id)
    {
        $client = $this->clientModel
            ->where('tenant_id', session('tenant_id'))
            ->find($id);

        if (! $client) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('clients/form', [
            'title'  => 'Modifier le client',
            'client' => $client,
        ]);
    }

    public function update($id)
    {
        $client = $this->clientModel
            ->where('tenant_id', session('tenant_id'))
            ->find($id);

        if (! $client) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (! $this->clientModel->update($id, $this->request->getPost())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->clientModel->errors());
        }

        return redirect()
            ->to(site_url('clients'))
            ->with('success', 'Client modifié avec succès.');
    }

    public function delete($id)
    {
        $client = $this->clientModel
            ->where('tenant_id', session('tenant_id'))
            ->find($id);

        if (! $client) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->clientModel->delete($id);

        return redirect()
            ->to(site_url('clients'))
            ->with('success', 'Client supprimé avec succès.');
    }

    public function quickStore(){
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false]);
        }

        $tenantId = (int) session('tenant_id');
        $nom = trim((string) $this->request->getPost('nom'));

        if ($nom === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Le nom est obligatoire.',
            ]);
        }

        $model = new \App\Models\ClientModel();

        $id = $model->insert([
            'tenant_id'  => $tenantId,
            'nom'        => $nom,
            'type_client' => $this->request->getPost('type_client') ?: null,
            'prenom'     => $this->request->getPost('prenom') ?: null,
            'email'      => $this->request->getPost('email') ?: null,
            'telephone'  => $this->request->getPost('telephone') ?: null,
            'entreprise' => $this->request->getPost('entreprise') ?: null,
            'statut'     => 'actif',
            'nationalite'     => $this->request->getPost('nationalite') ?: null,
            'ville'     => $this->request->getPost('ville') ?: null,
            'pays'     => $this->request->getPost('pays') ?: null,
            'adresse'     => $this->request->getPost('adresse') ?: null,
        ]);

        if (! $id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(' ', $model->errors() ?: ['Création impossible.']),
            ]);
        }

        $prenom = trim((string) $this->request->getPost('prenom'));
        $label  = trim($prenom . ' ' . $nom);

        return $this->response->setJSON([
            'success' => true,
            'id'      => $id,
            'label'   => $label,
        ]);
    }
}