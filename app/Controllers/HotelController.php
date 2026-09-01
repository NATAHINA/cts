<?php

namespace App\Controllers;

use App\Models\HotelModel;
use App\Models\DestinationModel;
use App\Models\DeviseModel;
use App\Models\FournisseurModel;

class HotelController extends BaseController
{
    public function index()
    {
        $model = new HotelModel();

        $query  = trim((string) $this->request->getGet('q'));
        $statut = (string) $this->request->getGet('statut');

        $model
            ->select('
                hotels.*,
                destinations.nom AS destination_nom,
                fournisseurs.nom AS fournisseur_nom
            ')
            ->join(
                'destinations',
                'destinations.id = hotels.destination_id',
                'left'
            )
            ->join(
                'fournisseurs',
                'fournisseurs.id = hotels.fournisseur_id',
                'left'
            );

        if ($query !== '') {
            $model
                ->groupStart()
                    ->like('hotels.nom', $query)
                    ->orLike('destinations.nom', $query)
                    ->orLike('fournisseurs.nom', $query)
                ->groupEnd();
        }

        if (in_array(
            $statut,
            ['actif', 'inactif', 'disponible', 'indisponible'],
            true
        )) {
            if (
                $statut === 'actif' ||
                $statut === 'inactif'
            ) {
                $model->where('hotels.statut', $statut);
            } else {
                $model->where(
                    'hotels.disponibilite',
                    $statut
                );
            }
        }

        $items = $model
            ->orderBy('hotels.created_at', 'DESC')
            ->findAll();

        return view('hotels/index', [
            'title'        => 'Hôtels',
            'items'        => $items,
            'q'            => $query,
            'statutFiltre' => $statut,
        ]);
    }


    public function create()
    {
        $destinationModel = new DestinationModel();
        $deviseModel      = new DeviseModel();
        $fournisseurModel = new FournisseurModel();

        return view('hotels/form', [
            'title'        => 'Ajouter — Hôtel',
            'item'         => null,

            'destinations' => $destinationModel
                ->orderBy('nom', 'ASC')
                ->findAll(),

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
        $model = new HotelModel();

        $data = [
            'tenant_id'      => session('tenant_id'),
            'destination_id' => $this->request->getPost('destination_id') ?: null,
            'fournisseur_id' => $this->request->getPost('fournisseur_id') ?: null,
            'nom'            => trim((string) $this->request->getPost('nom')),
            'categorie'      => $this->request->getPost('categorie') ?: null,
            'adresse'        => $this->request->getPost('adresse'),
            'description'    => $this->request->getPost('description'),
            'prix_nuit'      => $this->request->getPost('prix_nuit'),

            'prix_adulte'    => $this->request->getPost('prix_adulte') ?: null,
            'prix_enfant'    => $this->request->getPost('prix_enfant') ?: null,
            'prix_groupe'    => $this->request->getPost('prix_groupe') ?: null,

            'disponibilite'  => $this->request->getPost('disponibilite') ?: 'disponible',

            'devise_id'      => $this->request->getPost('devise_id'),

            'statut'         => $this->request->getPost('statut') ?: 'actif',
        ];

        if (! $model->insert($data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible d\'enregistrer l\'hôtel.'
                );
        }

        return redirect()
            ->to(site_url('hotels'))
            ->with(
                'success',
                'Hôtel enregistré avec succès.'
            );
    }


    public function edit($id)
    {
        $model = new HotelModel();

        $item = $model->find($id);

        if (! $item) {
            return redirect()
                ->to(site_url('hotels'))
                ->with(
                    'error',
                    'Hôtel introuvable.'
                );
        }

        $destinationModel = new DestinationModel();
        $deviseModel      = new DeviseModel();
        $fournisseurModel = new FournisseurModel();

        return view('hotels/form', [
            'title'        => 'Modifier — Hôtel',
            'item'         => $item,

            'destinations' => $destinationModel
                ->orderBy('nom', 'ASC')
                ->findAll(),

            'devises' => $deviseModel
                ->orderBy('code', 'ASC')
                ->findAll(),

            'fournisseurs' => $fournisseurModel
                ->orderBy('nom', 'ASC')
                ->findAll(),
        ]);
    }


    public function update($id)
    {
        $model = new HotelModel();

        $item = $model->find($id);

        if (! $item) {
            return redirect()
                ->to(site_url('hotels'))
                ->with(
                    'error',
                    'Hôtel introuvable.'
                );
        }

        $data = [
            'destination_id' => $this->request->getPost('destination_id') ?: null,
            'fournisseur_id' => $this->request->getPost('fournisseur_id') ?: null,
            'nom'            => trim((string) $this->request->getPost('nom')),
            'categorie'      => $this->request->getPost('categorie') ?: null,
            'adresse'        => $this->request->getPost('adresse'),
            'description'    => $this->request->getPost('description'),
            'prix_nuit'      => $this->request->getPost('prix_nuit'),

            'prix_adulte'    => $this->request->getPost('prix_adulte') ?: null,
            'prix_enfant'    => $this->request->getPost('prix_enfant') ?: null,
            'prix_groupe'    => $this->request->getPost('prix_groupe') ?: null,

            'disponibilite'  => $this->request->getPost('disponibilite') ?: 'disponible',
            'devise_id'      => $this->request->getPost('devise_id'),
            'statut'         => $this->request->getPost('statut') ?: 'actif',
        ];

        if (! $model->update($id, $data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible de modifier l\'hôtel.'
                );
        }

        return redirect()
            ->to(site_url('hotels'))
            ->with(
                'success',
                'Hôtel modifié avec succès.'
            );
    }


    public function delete($id)
    {
        $model = new HotelModel();

        $item = $model->find($id);

        if (! $item) {
            return redirect()
                ->to(site_url('hotels'))
                ->with(
                    'error',
                    'Hôtel introuvable.'
                );
        }

        if (! $model->delete($id)) {
            return redirect()
                ->to(site_url('hotels'))
                ->with(
                    'error',
                    'Impossible de supprimer cet hôtel.'
                );
        }

        return redirect()
            ->to(site_url('hotels'))
            ->with(
                'success',
                'Hôtel supprimé avec succès.'
            );
    }
}