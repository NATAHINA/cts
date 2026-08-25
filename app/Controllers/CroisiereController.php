<?php

namespace App\Controllers;

use App\Models\CroisiereModel;
use App\Models\DeviseModel;

class CroisiereController extends BaseController
{
    public function index()
    {
        $model = new CroisiereModel();
        $query = trim((string) $this->request->getGet('q')); $statut = (string) $this->request->getGet('statut');
        if ($query !== '') { $model->groupStart()->like('nom', $query)->orLike('compagnie', $query)->orLike('itineraire', $query)->orLike('fournisseur', $query)->groupEnd(); }
        if (in_array($statut, ['actif', 'inactif', 'disponible', 'indisponible'], true)) { $model->where($statut === 'actif' || $statut === 'inactif' ? 'statut' : 'disponibilite', $statut); }
        $data = [
            'title' => 'Croisières',
            'items' => $model->orderBy('croisieres.created_at', 'DESC')->findAll(),
            'q' => $query, 'statutFiltre' => $statut,
        ];

        return view('croisieres/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Ajouter — Croisières', 'item' => null];

        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();

        return view('croisieres/form', $data);
    }

    public function store()
    {
        $model = new CroisiereModel();

        $model->insert([
            'tenant_id' => session('tenant_id'),
            'compagnie' => $this->request->getPost('compagnie'),
            'nom' => $this->request->getPost('nom'),
            'itineraire' => $this->request->getPost('itineraire'),
            'duree_jours' => $this->request->getPost('duree_jours'),
            'date_depart' => $this->request->getPost('date_depart'),
            'prix' => $this->request->getPost('prix'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null, 'prix_enfant' => $this->request->getPost('prix_enfant') ?: null, 'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'fournisseur' => $this->request->getPost('fournisseur'), 'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/croisieres')->with('success', "Enregistré avec succès.");
    }

    public function edit($id)
    {
        $model = new CroisiereModel();
        $item  = $model->find($id);

        if (! $item) {
            return redirect()->to('/croisieres')->with('error', "Élément introuvable.");
        }

        $data = ['title' => 'Modifier — Croisières', 'item' => $item];

        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();

        return view('croisieres/form', $data);
    }

    public function update($id)
    {
        $model = new CroisiereModel();

        $model->update($id, [
            'compagnie' => $this->request->getPost('compagnie'),
            'nom' => $this->request->getPost('nom'),
            'itineraire' => $this->request->getPost('itineraire'),
            'duree_jours' => $this->request->getPost('duree_jours'),
            'date_depart' => $this->request->getPost('date_depart'),
            'prix' => $this->request->getPost('prix'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null, 'prix_enfant' => $this->request->getPost('prix_enfant') ?: null, 'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'fournisseur' => $this->request->getPost('fournisseur'), 'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ]);

        return redirect()->to('/croisieres')->with('success', "Modifié avec succès.");
    }

    public function delete($id)
    {
        $model = new CroisiereModel();
        $model->delete($id);

        return redirect()->to('/croisieres')->with('success', "Supprimé avec succès.");
    }
}
