<?php

namespace App\Controllers;

use App\Models\VolModel;
use App\Models\DeviseModel;
use App\Models\FournisseurModel;

class VolController extends BaseController
{
    public function index()
    {
        $model = new VolModel();
        $query = trim((string) $this->request->getGet('q'));
        $statut = (string) $this->request->getGet('statut');

        $model
            ->select('
                vols.*,
                fournisseurs.nom AS fournisseur_nom
            ')
            ->join(
                'fournisseurs',
                'fournisseurs.id = vols.id_fournisseur',
                'left'
            );

        if ($query !== '') {
            $model->groupStart()
                ->like('compagnie', $query)
                ->orLike('num_vol', $query)
                ->orLike('aeroport_depart', $query)
                ->orLike('aeroport_arrivee', $query)
                ->orLike('fournisseurs.nom', $query)
                ->groupEnd();
        }
        if (in_array($statut, ['actif', 'inactif', 'disponible', 'indisponible'], true)) {
            $model->where($statut === 'actif' || $statut === 'inactif' ? 'statut' : 'disponibilite', $statut);
        }
        $data = [
            'title' => 'Vols',
            'items' => $model->orderBy('vols.created_at', 'DESC')->findAll(),
            'q' => $query,
            'statutFiltre' => $statut,
        ];

        return view('vols/index', $data);
    }

    public function create()
    {
        $deviseModel = new DeviseModel();
        $fournisseurModel = new FournisseurModel();

        return view('vols/form', [
            'title'        => 'Ajouter — Vols',
            'item'         => null,

            'devises' => $deviseModel
                ->orderBy('code', 'ASC')
                ->findAll(),

            'fournisseurs' => $fournisseurModel
                ->orderBy('nom', 'ASC')
                ->findAll(),
        ]);
    }

    public function store()
    {
        $model = new VolModel();

        $data = [
            'tenant_id' => session('tenant_id'),
            'compagnie' => $this->request->getPost('compagnie'),
            'num_vol' => $this->request->getPost('num_vol'),
            'aeroport_depart' => $this->request->getPost('aeroport_depart'),
            'aeroport_arrivee' => $this->request->getPost('aeroport_arrivee'),
            'date_depart' => $this->request->getPost('date_depart'),
            'date_arrivee' => $this->request->getPost('date_arrivee'),
            'prix' => $this->request->getPost('prix'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null,
            'prix_enfant' => $this->request->getPost('prix_enfant') ?: null,
            'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'id_fournisseur' => $this->request->getPost('fournisseur_id') ?: null,
            'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ];

        if (! $model->insert($data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible d\'enregistrer le vols.'
                );
        }

        return redirect()
            ->to(site_url('vols'))
            ->with(
                'success',
                'Vols enregistré avec succès.'
            );

    }

    public function edit($id)
    {
        $model = new VolModel();
        $item  = $model->find($id);
        $fournisseurModel = new FournisseurModel();

        if (! $item) {
            return redirect()->to('/vols')->with('error', "Élément introuvable.");
        }

        $data = [
            'title' => 'Modifier — Vols', 
            'item' => $item,
            'fournisseurs' => $fournisseurModel
                ->orderBy('nom', 'ASC')
                ->findAll(),
            ];

        $deviseModel = new DeviseModel();
        $data['devises'] = $deviseModel->orderBy('code', 'ASC')->findAll();

        return view('vols/form', $data);
    }

    public function update($id)
    {
        $model = new VolModel();

        $data = [
            'compagnie' => $this->request->getPost('compagnie'),
            'num_vol' => $this->request->getPost('num_vol'),
            'aeroport_depart' => $this->request->getPost('aeroport_depart'),
            'aeroport_arrivee' => $this->request->getPost('aeroport_arrivee'),
            'date_depart' => $this->request->getPost('date_depart'),
            'date_arrivee' => $this->request->getPost('date_arrivee'),
            'prix' => $this->request->getPost('prix'),
            'prix_adulte' => $this->request->getPost('prix_adulte') ?: null,
            'prix_enfant' => $this->request->getPost('prix_enfant') ?: null,
            'prix_groupe' => $this->request->getPost('prix_groupe') ?: null,
            'id_fournisseur' => $this->request->getPost('fournisseur_id') ?: null,
            'disponibilite' => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id' => $this->request->getPost('devise_id'),
            'statut' => $this->request->getPost('statut'),
        ];

        if (! $model->update($id, $data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible de modifier le vol.'
                );
        }

        return redirect()->to('/vols')->with('success', "Modifié avec succès.");
    }

    public function delete($id)
    {

        $model = new VolModel();

        $item = $model->find($id);

        if (! $item) {
            return redirect()
                ->to(site_url('vols'))
                ->with(
                    'error',
                    'Vols introuvable.'
                );
        }

        if (! $model->delete($id)) {
            return redirect()
                ->to(site_url('vols'))
                ->with(
                    'error',
                    'Impossible de supprimer ce vol.'
                );
        }

        return redirect()
            ->to(site_url('vols'))
            ->with(
                'success',
                'Vol supprimé avec succès.'
            );

    }
}
