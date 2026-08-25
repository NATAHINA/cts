<?php

namespace App\Controllers;

use App\Models\ExcursionModel;
use App\Models\DestinationModel;
use App\Models\DeviseModel;

class ExcursionController extends BaseController
{
    public function index()
    {
        $model = new ExcursionModel();
        $query = trim((string) $this->request->getGet('q')); $statut = (string) $this->request->getGet('statut');
        if ($query !== '') { $model->groupStart()->like('excursions.nom', $query)->orLike('destinations.nom', $query)->orLike('excursions.fournisseur', $query)->groupEnd(); }
        if (in_array($statut, ['actif', 'inactif', 'disponible', 'indisponible'], true)) { $model->where($statut === 'actif' || $statut === 'inactif' ? 'excursions.statut' : 'excursions.disponibilite', $statut); }
        $data = [
            'title' => 'Excursions',
            'items' => $model
            ->select('excursions.*, destinations.nom as destination_nom')
            ->join('destinations', 'destinations.id = excursions.destination_id', 'left')->orderBy('excursions.created_at', 'DESC')->findAll(),
            'q' => $query, 'statutFiltre' => $statut,
        ];

        return view('excursions/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Ajouter — Excursions', 'item' => null];

        $destinationModel = new DestinationModel();
        $data['destinations'] = $destinationModel->orderBy('nom', 'ASC')->findAll();
        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();

        return view('excursions/form', $data);
    }

    public function store()
    {
        $model = new ExcursionModel();

        $model->insert([
            'tenant_id' => session('tenant_id'),
            'destination_id' => $this->request->getPost('destination_id'),
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'duree_heures' => $this->request->getPost('duree_heures'),
            'prix' => $this->request->getPost('prix'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null, 'prix_enfant' => $this->request->getPost('prix_enfant') ?: null, 'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'fournisseur' => $this->request->getPost('fournisseur'), 'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/excursions')->with('success', "Enregistré avec succès.");
    }

    public function edit($id)
    {
        $model = new ExcursionModel();
        $item  = $model->find($id);

        if (! $item) {
            return redirect()->to('/excursions')->with('error', "Élément introuvable.");
        }

        $data = ['title' => 'Modifier — Excursions', 'item' => $item];

        $destinationModel = new DestinationModel();
        $data['destinations'] = $destinationModel->orderBy('nom', 'ASC')->findAll();
        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();

        return view('excursions/form', $data);
    }

    public function update($id)
    {
        $model = new ExcursionModel();

        $model->update($id, [
            'destination_id' => $this->request->getPost('destination_id'),
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'duree_heures' => $this->request->getPost('duree_heures'),
            'prix' => $this->request->getPost('prix'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null, 'prix_enfant' => $this->request->getPost('prix_enfant') ?: null, 'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'fournisseur' => $this->request->getPost('fournisseur'), 'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/excursions')->with('success', "Modifié avec succès.");
    }

    public function delete($id)
    {
        $model = new ExcursionModel();
        $model->delete($id);

        return redirect()->to('/excursions')->with('success', "Supprimé avec succès.");
    }
}
