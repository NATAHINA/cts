<?php

namespace App\Controllers;

use App\Models\ForfaitModel;
use App\Models\DestinationModel;
use App\Models\DeviseModel;

class ForfaitController extends BaseController
{
    public function index()
    {
        $model = new ForfaitModel();
        $data = [
            'title' => 'Forfaits',
            'items' => $model
            ->select('forfaits.*, destinations.nom as destination_nom')
            ->join('destinations', 'destinations.id = forfaits.destination_id', 'left')->orderBy('forfaits.created_at', 'DESC')->findAll(),
        ];

        return view('forfaits/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Ajouter — Forfaits', 'item' => null];

        $destinationModel = new DestinationModel();
        $data['destinations'] = $destinationModel->orderBy('nom', 'ASC')->findAll();
        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();

        return view('forfaits/form', $data);
    }

    public function store()
    {
        $model = new ForfaitModel();

        $model->insert([
            'tenant_id' => session('tenant_id'),
            'destination_id' => $this->request->getPost('destination_id'),
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'duree_jours' => $this->request->getPost('duree_jours'),
            'prix' => $this->request->getPost('prix'),
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/forfaits')->with('success', "Enregistré avec succès.");
    }

    public function edit($id)
    {
        $model = new ForfaitModel();
        $item  = $model->find($id);

        if (! $item) {
            return redirect()->to('/forfaits')->with('error', "Élément introuvable.");
        }

        $data = ['title' => 'Modifier — Forfaits', 'item' => $item];

        $destinationModel = new DestinationModel();
        $data['destinations'] = $destinationModel->orderBy('nom', 'ASC')->findAll();
        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();

        return view('forfaits/form', $data);
    }

    public function update($id)
    {
        $model = new ForfaitModel();

        $model->update($id, [
            'destination_id' => $this->request->getPost('destination_id'),
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'duree_jours' => $this->request->getPost('duree_jours'),
            'prix' => $this->request->getPost('prix'),
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/forfaits')->with('success', "Modifié avec succès.");
    }

    public function delete($id)
    {
        $model = new ForfaitModel();
        $model->delete($id);

        return redirect()->to('/forfaits')->with('success', "Supprimé avec succès.");
    }
}
