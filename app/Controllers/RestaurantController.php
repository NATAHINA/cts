<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RestaurantModel;
use App\Models\DestinationModel;
use App\Models\FournisseurModel;

class RestaurantController extends BaseController
{
    protected $model;
    protected $destinationModel;
    protected $fournisseurModel;

    public function __construct()
    {
        $this->model            = new RestaurantModel();
        $this->destinationModel = new DestinationModel();
        $this->fournisseurModel = new FournisseurModel();
    }

    private function tenantId(): int
    {
        return (int) session('tenant_id');
    }

    private function getRestaurant(int $id): ?array
    {
        return $this->model
            ->where('id', $id)
            ->where('tenant_id', $this->tenantId())
            ->first();
    }

    public function index()
    {
        $restaurants = $this->model
            ->select('
                restaurants.*,
                destinations.nom AS destination_nom
            ')
            ->join(
                'destinations',
                'destinations.id = restaurants.destination_id',
                'left'
            )
            ->where('restaurants.tenant_id', $this->tenantId())
            ->orderBy('restaurants.nom', 'ASC')
            ->findAll();

        return view('restaurants/index', [
            'title'       => 'Restaurants',
            'restaurants' => $restaurants,
        ]);
    }

    public function create()
    {
        $tenantId = $this->tenantId();

        return view('restaurants/form', [
            'title'        => 'Nouveau restaurant',
            'destinations' => $this->destinationModel
                ->where('tenant_id', $tenantId)
                ->orderBy('nom', 'ASC')
                ->findAll(),
            'fournisseurs' => $this->fournisseurModel
                ->where('tenant_id', $tenantId)
                ->orderBy('nom', 'ASC')
                ->findAll(),
        ]);
    }

    public function store()
    {
        $tenantId = $this->tenantId();

        $data = [
            'tenant_id'      => $tenantId,
            'destination_id' => $this->request->getPost('destination_id') ?: null,
            'fournisseur_id' => $this->request->getPost('fournisseur_id') ?: null,
            'nom'            => $this->request->getPost('nom'),
            'type_cuisine'   => $this->request->getPost('type_cuisine') ?: null,
            'adresse'        => $this->request->getPost('adresse') ?: null,
            'telephone'      => $this->request->getPost('telephone') ?: null,
            'email'          => $this->request->getPost('email') ?: null,
            'description'    => $this->request->getPost('description') ?: null,
            'prix_moyen'     => (float) ($this->request->getPost('prix_moyen') ?: 0),
            'devise'         => $this->request->getPost('devise') ?: 'MGA',
            'note'           => $this->request->getPost('note') ?: null,
            'actif'          => $this->request->getPost('actif') ? 1 : 0,
        ];

        if (! $this->model->insert($data)) {
            return redirect()->back()->withInput()
                ->with('error', 'Impossible de créer le restaurant.');
        }

        return redirect()->to(site_url('restaurants'))
            ->with('success', 'Restaurant créé avec succès.');
    }

    public function edit($id)
    {
        $tenantId   = $this->tenantId();
        $restaurant = $this->getRestaurant((int) $id);

        if (! $restaurant) {
            return redirect()->to(site_url('restaurants'))
                ->with('error', 'Restaurant introuvable.');
        }

        return view('restaurants/form', [
            'title'        => 'Modifier le restaurant',
            'restaurant'   => $restaurant,
            'destinations' => $this->destinationModel
                ->where('tenant_id', $tenantId)
                ->orderBy('nom', 'ASC')
                ->findAll(),
            'fournisseurs' => $this->fournisseurModel
                ->where('tenant_id', $tenantId)
                ->orderBy('nom', 'ASC')
                ->findAll(),
        ]);
    }

    public function update($id)
    {
        $restaurant = $this->getRestaurant((int) $id);

        if (! $restaurant) {
            return redirect()->to(site_url('restaurants'))
                ->with('error', 'Restaurant introuvable.');
        }

        $data = [
            'destination_id' => $this->request->getPost('destination_id') ?: null,
            'fournisseur_id' => $this->request->getPost('fournisseur_id') ?: null,
            'nom'            => $this->request->getPost('nom'),
            'type_cuisine'   => $this->request->getPost('type_cuisine') ?: null,
            'adresse'        => $this->request->getPost('adresse') ?: null,
            'telephone'      => $this->request->getPost('telephone') ?: null,
            'email'          => $this->request->getPost('email') ?: null,
            'description'    => $this->request->getPost('description') ?: null,
            'prix_moyen'     => (float) ($this->request->getPost('prix_moyen') ?: 0),
            'devise'         => $this->request->getPost('devise') ?: 'MGA',
            'note'           => $this->request->getPost('note') ?: null,
            'actif'          => $this->request->getPost('actif') ? 1 : 0,
        ];

        $this->model->update($id, $data);

        return redirect()->to(site_url('restaurants'))
            ->with('success', 'Restaurant mis à jour avec succès.');
    }

    public function delete($id)
    {
        $restaurant = $this->getRestaurant((int) $id);

        if (! $restaurant) {
            return redirect()->to(site_url('restaurants'))
                ->with('error', 'Restaurant introuvable.');
        }

        $this->model->delete($id);

        return redirect()->to(site_url('restaurants'))
            ->with('success', 'Restaurant supprimé avec succès.');
    }
}