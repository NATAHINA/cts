<?php

namespace App\Controllers;

use App\Models\HotelModel;
use App\Models\DestinationModel;
use App\Models\DeviseModel;

class HotelController extends BaseController
{
    public function index()
    {
        $model = new HotelModel();
        $query = trim((string) $this->request->getGet('q'));
        $statut = (string) $this->request->getGet('statut');
        if ($query !== '') {
            $model->groupStart()->like('hotels.nom', $query)->orLike('destinations.nom', $query)->orLike('hotels.fournisseur', $query)->groupEnd();
        }
        if (in_array($statut, ['actif', 'inactif', 'disponible', 'indisponible'], true)) {
            $model->where($statut === 'actif' || $statut === 'inactif' ? 'hotels.statut' : 'hotels.disponibilite', $statut);
        }
        $data = [
            'title' => 'Hôtels',
            'items' => $model
            ->select('hotels.*, destinations.nom as destination_nom')
            ->join('destinations', 'destinations.id = hotels.destination_id', 'left')->orderBy('hotels.created_at', 'DESC')->findAll(),
            'q' => $query, 'statutFiltre' => $statut,
        ];

        return view('hotels/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Ajouter — Hôtels', 'item' => null];

        $destinationModel = new DestinationModel();
        $data['destinations'] = $destinationModel->orderBy('nom', 'ASC')->findAll();
        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();

        return view('hotels/form', $data);
    }

    public function store()
    {
        $model = new HotelModel();

        $model->insert([
            'tenant_id' => session('tenant_id'),
            'destination_id' => $this->request->getPost('destination_id'),
            'nom' => $this->request->getPost('nom'),
            'categorie' => $this->request->getPost('categorie'),
            'adresse' => $this->request->getPost('adresse'),
            'description' => $this->request->getPost('description'),
            'prix_nuit' => $this->request->getPost('prix_nuit'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null, 'prix_enfant' => $this->request->getPost('prix_enfant') ?: null, 'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'fournisseur' => $this->request->getPost('fournisseur'), 'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/hotels')->with('success', "Enregistré avec succès.");
    }

    public function edit($id)
    {
        $model = new HotelModel();
        $item  = $model->find($id);

        if (! $item) {
            return redirect()->to('/hotels')->with('error', "Élément introuvable.");
        }

        $data = ['title' => 'Modifier — Hôtels', 'item' => $item];

        $destinationModel = new DestinationModel();
        $data['destinations'] = $destinationModel->orderBy('nom', 'ASC')->findAll();
        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();

        return view('hotels/form', $data);
    }

    public function update($id)
    {
        $model = new HotelModel();

        $model->update($id, [
            'destination_id' => $this->request->getPost('destination_id'),
            'nom' => $this->request->getPost('nom'),
            'categorie' => $this->request->getPost('categorie'),
            'adresse' => $this->request->getPost('adresse'),
            'description' => $this->request->getPost('description'),
            'prix_nuit' => $this->request->getPost('prix_nuit'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null, 'prix_enfant' => $this->request->getPost('prix_enfant') ?: null, 'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'fournisseur' => $this->request->getPost('fournisseur'), 'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/hotels')->with('success', "Modifié avec succès.");
    }

    public function delete($id)
    {
        $model = new HotelModel();
        $model->delete($id);

        return redirect()->to('/hotels')->with('success', "Supprimé avec succès.");
    }
}
