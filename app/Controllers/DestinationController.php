<?php

namespace App\Controllers;

use App\Models\DestinationModel;
use Config\Database;

class DestinationController extends BaseController
{
    protected DestinationModel $model;

    public function __construct()
    {
        $this->model = new DestinationModel();
    }

    /**
     * ID du tenant actuellement connecté.
     */
    protected function tenantId(): int
    {
        return (int) session('tenant_id');
    }

    public function index()
    {
        // $tenantId = $this->tenantId();

        $items = $this->model
            // ->where('tenant_id', $tenantId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('destinations/index', [
            'title' => 'Destinations',
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('destinations/form', [
            'title' => 'Ajouter — Destination',
            'item'  => null,
        ]);
    }

    public function store()
    {
        // $tenantId = $this->tenantId();

        // if ($tenantId <= 0) {
        //     return redirect()
        //         ->back()
        //         ->withInput()
        //         ->with('error', 'Tenant non identifié.');
        // }

        // $db = Database::connect();

        // $tenant = $db
        //     ->table('tenants')
        //     ->where('id', $tenantId)
        //     ->get()
        //     ->getRowArray();
            
        // if (! $tenant) {
        //     return redirect()
        //         ->back()
        //         ->withInput()
        //         ->with(
        //             'error',
        //             "L'agence associée à votre session n'existe pas."
        //         );
        // }

        $data = [
            // 'tenant_id'   => $tenantId,
            'nom'         => trim((string) $this->request->getPost('nom')),
            'pays'        => trim((string) $this->request->getPost('pays')),
            'region'      => trim((string) $this->request->getPost('region')),
            'description' => $this->request->getPost('description'),
            'statut'      => $this->request->getPost('statut') ?: 'actif',
        ];

        if (! $this->model->insert($data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->model->errors());
        }

        return redirect()
            ->to(site_url('destinations'))
            ->with('success', 'Destination enregistrée avec succès.');
    }

    public function edit($id)
    {
        $tenantId = $this->tenantId();

        $item = $this->model
            ->where('tenant_id', $tenantId)
            ->where('id', $id)
            ->first();

        if (! $item) {
            return redirect()
                ->to(site_url('destinations'))
                ->with('error', 'Destination introuvable.');
        }

        return view('destinations/form', [
            'title' => 'Modifier — Destination',
            'item'  => $item,
        ]);
    }

    public function update($id)
    {
        $tenantId = $this->tenantId();

        $item = $this->model
            ->where('tenant_id', $tenantId)
            ->where('id', $id)
            ->first();

        if (! $item) {
            return redirect()
                ->to(site_url('destinations'))
                ->with('error', 'Destination introuvable.');
        }

        $data = [
            'nom'         => trim((string) $this->request->getPost('nom')),
            'pays'        => trim((string) $this->request->getPost('pays')),
            'region'      => trim((string) $this->request->getPost('region')),
            'description' => $this->request->getPost('description'),
            'statut'      => $this->request->getPost('statut') ?: 'actif',
        ];

        if (! $this->model->update($id, $data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->model->errors());
        }

        return redirect()
            ->to(site_url('destinations'))
            ->with('success', 'Destination modifiée avec succès.');
    }

    public function delete($id)
    {
        $tenantId = $this->tenantId();

        $item = $this->model
            ->where('tenant_id', $tenantId)
            ->where('id', $id)
            ->first();

        if (! $item) {
            return redirect()
                ->to(site_url('destinations'))
                ->with('error', 'Destination introuvable.');
        }

        if (! $this->model->delete($id)) {
            return redirect()
                ->to(site_url('destinations'))
                ->with('error', 'Impossible de supprimer la destination.');
        }

        return redirect()
            ->to(site_url('destinations'))
            ->with('success', 'Destination supprimée avec succès.');
    }

    public function quickStore(){
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false]);
        }

        $tenantId = (int) session('tenant_id');
        $nom = trim((string) $this->request->getPost('nom'));

        if ($nom === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Le nom est obligatoire.',
            ]);
        }

        $model = new \App\Models\DestinationModel();

        $data = [
            'nom'  => $nom,
            'pays' => $this->request->getPost('pays') ?: null,
            'region' => $this->request->getPost('region') ?: null,
            'description' => $this->request->getPost('description') ?: null,
        ];

        // Si multi-tenant sur destinations
        $fields = $model->db->getFieldNames($model->getTable());
        if (in_array('tenant_id', $fields, true)) {
            $data['tenant_id'] = $tenantId;
        }
        if (in_array('statut', $fields, true)) {
            $data['statut'] = 'actif';
        }
        if (in_array('actif', $fields, true)) {
            $data['actif'] = 1;
        }

        $id = $model->insert($data);

        if (! $id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(' ', $model->errors() ?: ['Création impossible.']),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'id'      => $id,
            'label'   => $nom,
        ]);
    }
}