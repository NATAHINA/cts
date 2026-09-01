<?php

namespace App\Controllers;

use App\Models\TransfertModel;
use App\Models\DestinationModel;
use App\Models\DeviseModel;
use App\Models\FournisseurModel;

class TransfertController extends BaseController
{
    public function index()
    {
        $model = new TransfertModel();
        $query = trim((string) $this->request->getGet('q')); $statut = (string) $this->request->getGet('statut');

        $model
            ->select('
                transferts.*,
                destinations.nom AS destination_nom,
                fournisseurs.nom AS fournisseur_nom
            ')
            ->join(
                'destinations',
                'destinations.id = transferts.destination_id',
                'left'
            )
            ->join(
                'fournisseurs',
                'fournisseurs.id = transferts.fournisseur_id',
                'left'
            );

        if ($query !== '') { $model->groupStart()->like('transferts.nom', $query)->orLike('transferts.type', $query)->orLike('transferts.vehicule', $query)->orLike('destinations.nom', $query)->orLike('fournisseurs.nom', $query)->groupEnd(); }
        if (in_array($statut, ['actif', 'inactif', 'disponible', 'indisponible'], true)) { $model->where($statut === 'actif' || $statut === 'inactif' ? 'transferts.statut' : 'transferts.disponibilite', $statut); }
        
        $items = $model
            ->orderBy('transferts.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Transferts',
            'items' => $items,
            'q' => $query, 
            'statutFiltre' => $statut,
        ];

        return view('transferts/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Ajouter — Transferts', 'item' => null];

        $destinationModel = new DestinationModel();
        $data['destinations'] = $destinationModel->orderBy('nom', 'ASC')->findAll();
        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();
        $fournisseurModel = new FournisseurModel();
        $data['fournisseurs'] = $fournisseurModel->orderBy('nom', 'ASC')->findAll();

        return view('transferts/form', $data);
    }

    public function store()
    {
        $model = new TransfertModel();

        $model->insert([
            'tenant_id' => session('tenant_id'),
            'destination_id' => $this->request->getPost('destination_id'),
            'nom' => $this->request->getPost('nom'),
            'type' => $this->request->getPost('type'),
            'vehicule' => $this->request->getPost('vehicule'),
            'capacite' => $this->request->getPost('capacite'),
            'prix' => $this->request->getPost('prix'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null, 'prix_enfant' => $this->request->getPost('prix_enfant') ?: null, 'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'fournisseur_id' => $this->request->getPost('fournisseur_id'), 'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/transferts')->with('success', "Enregistré avec succès.");
    }

    public function edit($id)
    {
        $model = new TransfertModel();
        $item  = $model->find($id);

        if (! $item) {
            return redirect()->to('/transferts')->with('error', "Élément introuvable.");
        }

        $data = ['title' => 'Modifier — Transferts', 'item' => $item];

        $destinationModel = new DestinationModel();
        $data['destinations'] = $destinationModel->orderBy('nom', 'ASC')->findAll();
        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();
        $fournisseurModel = new FournisseurModel();
        $data['fournisseurs'] = $fournisseurModel->orderBy('nom', 'ASC')->findAll();

        return view('transferts/form', $data);
    }

    public function update($id)
    {
        $model = new TransfertModel();

        $model->update($id, [
            'destination_id' => $this->request->getPost('destination_id'),
            'nom' => $this->request->getPost('nom'),
            'type' => $this->request->getPost('type'),
            'vehicule' => $this->request->getPost('vehicule'),
            'capacite' => $this->request->getPost('capacite'),
            'prix' => $this->request->getPost('prix'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null, 'prix_enfant' => $this->request->getPost('prix_enfant') ?: null, 'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'fournisseur_id' => $this->request->getPost('fournisseur_id'), 'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/transferts')->with('success', "Modifié avec succès.");
    }

    public function delete($id)
    {
        $model = new TransfertModel();
        $item = $model->find($id);

        if (! $item) {
            return redirect()
                ->to(site_url('transferts'))
                ->with(
                    'error',
                    'Transfert introuvable.'
                );
        }

        if (! $model->delete($id)) {
            return redirect()
                ->to(site_url('transferts'))
                ->with(
                    'error',
                    'Impossible de supprimer ce transfert.'
                );
        }

        return redirect()
            ->to(site_url('transferts'))
            ->with(
                'success',
                'Transfert supprimé avec succès.'
            );
    
    }
}
