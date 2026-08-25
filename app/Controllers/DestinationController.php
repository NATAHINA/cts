<?php

namespace App\Controllers;

use App\Models\DestinationModel;

class DestinationController extends BaseController
{
    public function index()
    {
        $model = new DestinationModel();
        $data = [
            'title' => 'Destinations',
            'items' => $model->orderBy('destinations.created_at', 'DESC')->findAll(),
        ];

        return view('destinations/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Ajouter — Destinations', 'item' => null];


        return view('destinations/form', $data);
    }

    public function store()
    {
        $model = new DestinationModel();

        $model->insert([
            'tenant_id' => session('tenant_id'),
            'nom' => $this->request->getPost('nom'),
            'pays' => $this->request->getPost('pays'),
            'region' => $this->request->getPost('region'),
            'description' => $this->request->getPost('description'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/destinations')->with('success', "Enregistré avec succès.");
    }

    public function edit($id)
    {
        $model = new DestinationModel();
        $item  = $model->find($id);

        if (! $item) {
            return redirect()->to('/destinations')->with('error', "Élément introuvable.");
        }

        $data = ['title' => 'Modifier — Destinations', 'item' => $item];


        return view('destinations/form', $data);
    }

    public function update($id)
    {
        $model = new DestinationModel();

        $model->update($id, [
            'nom' => $this->request->getPost('nom'),
            'pays' => $this->request->getPost('pays'),
            'region' => $this->request->getPost('region'),
            'description' => $this->request->getPost('description'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/destinations')->with('success', "Modifié avec succès.");
    }

    public function delete($id)
    {
        $model = new DestinationModel();
        $model->delete($id);

        return redirect()->to('/destinations')->with('success', "Supprimé avec succès.");
    }
}
