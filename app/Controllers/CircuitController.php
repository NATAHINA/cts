<?php

namespace App\Controllers;

use App\Models\CircuitModel;
use App\Models\DeviseModel;
use App\Models\FournisseurModel;

class CircuitController extends BaseController
{
    public function index()
    {
        $model = new CircuitModel();
        $query = trim((string) $this->request->getGet('q')); $statut = (string) $this->request->getGet('statut');
        
        $model
            ->select('
                circuits.*,
                fournisseurs.nom AS fournisseur_nom
            ')
            ->join(
                'fournisseurs',
                'fournisseurs.id = circuits.fournisseur_id',
                'left'
            );
        
        if ($query !== '') { $model->groupStart()->like('nom', $query)->orLike('fournisseurs.nom', $query)->groupEnd(); }
        if (in_array($statut, ['actif', 'inactif', 'disponible', 'indisponible'], true)) { $model->where($statut === 'actif' || $statut === 'inactif' ? 'statut' : 'disponibilite', $statut); }
        $data = [
            'title' => 'Circuits',
            'items' => $model->orderBy('circuits.created_at', 'DESC')->findAll(),
            'q' => $query, 'statutFiltre' => $statut,
        ];

        return view('circuits/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Ajouter — Circuits', 'item' => null];

        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();
        $fournisseurModel = new FournisseurModel();
        $data['fournisseurs'] = $fournisseurModel->orderBy('nom', 'ASC')->findAll();

        return view('circuits/form', $data);
    }

    public function store()
    {
        $model = new CircuitModel();

        $model->insert([
            'tenant_id' => session('tenant_id'),
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'duree_jours' => $this->request->getPost('duree_jours'),
            'prix' => $this->request->getPost('prix'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null, 'prix_enfant' => $this->request->getPost('prix_enfant') ?: null, 'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'fournisseur_id' => $this->request->getPost('fournisseur_id'), 'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/circuits')->with('success', "Enregistré avec succès.");
    }

    public function edit($id)
    {
        $model = new CircuitModel();
        $item  = $model->find($id);

        if (! $item) {
            return redirect()->to('/circuits')->with('error', "Élément introuvable.");
        }

        $data = ['title' => 'Modifier — Circuits', 'item' => $item];

        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();
        $fournisseurModel = new FournisseurModel();
        $data['fournisseurs'] = $fournisseurModel->orderBy('nom', 'ASC')->findAll();

        return view('circuits/form', $data);
    }

    public function update($id)
    {
        $model = new CircuitModel();

        $model->update($id, [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'duree_jours' => $this->request->getPost('duree_jours'),
            'prix' => $this->request->getPost('prix'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null, 'prix_enfant' => $this->request->getPost('prix_enfant') ?: null, 'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'fournisseur_id' => $this->request->getPost('fournisseur_id'), 'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/circuits')->with('success', "Modifié avec succès.");
    }

    public function delete($id)
    {
        $model = new CircuitModel();
        $item = $model->find($id);

        if (! $item) {
            return redirect()
                ->to(site_url('circuits'))
                ->with(
                    'error',
                    'Circuits introuvable.'
                );
        }

        if (! $model->delete($id)) {
            return redirect()
                ->to(site_url('circuits'))
                ->with(
                    'error',
                    'Impossible de supprimer ce circuit.'
                );
        }

        return redirect()
            ->to(site_url('circuits'))
            ->with(
                'success',
                'Circuit supprimé avec succès.'
            );
    }
}
