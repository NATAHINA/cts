<?php

namespace App\Controllers;

use App\Models\ClientModel;

class ClientController extends BaseController
{
    public function index()
    {
        $model = new ClientModel();
        $data = [
            'title' => 'Clients',
            'items' => $model->orderBy('clients.created_at', 'DESC')->findAll(),
        ];

        return view('clients/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Ajouter — Clients', 'item' => null];


        return view('clients/form', $data);
    }

    public function store()
    {
        $model = new ClientModel();

        $model->insert([
            'tenant_id' => session('tenant_id'),
            'type' => $this->request->getPost('type'),
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'telephone' => $this->request->getPost('telephone'),
            'adresse' => $this->request->getPost('adresse'),
            'notes' => $this->request->getPost('notes'),
        ]);

        return redirect()->to('/clients')->with('success', "Enregistré avec succès.");
    }

    public function edit($id)
    {
        $model = new ClientModel();
        $item  = $model->find($id);

        if (! $item) {
            return redirect()->to('/clients')->with('error', "Élément introuvable.");
        }

        $data = ['title' => 'Modifier — Clients', 'item' => $item];


        return view('clients/form', $data);
    }

    public function update($id)
    {
        $model = new ClientModel();

        $model->update($id, [
            'type' => $this->request->getPost('type'),
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'telephone' => $this->request->getPost('telephone'),
            'adresse' => $this->request->getPost('adresse'),
            'notes' => $this->request->getPost('notes'),
        ]);

        return redirect()->to('/clients')->with('success', "Modifié avec succès.");
    }

    public function delete($id)
    {
        $model = new ClientModel();
        $model->delete($id);

        return redirect()->to('/clients')->with('success', "Supprimé avec succès.");
    }
}
