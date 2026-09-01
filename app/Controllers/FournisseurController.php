<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FournisseurModel;

class FournisseurController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new FournisseurModel();
    }

    private function tenantId()
    {
        return (int) session('tenant_id');
    }

    public function index()
    {
        return view('fournisseurs/index', [
            'title' => 'Fournisseurs',
            'fournisseurs' => $this->model
                ->where('tenant_id', $this->tenantId())
                ->orderBy('nom', 'ASC')
                ->findAll()
        ]);
    }

    public function create()
    {
        return view('fournisseurs/form', [
            'title' => 'Nouveau fournisseur'
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $data['tenant_id'] = $this->tenantId();

        $this->model->insert($data);

        return redirect()->to('fournisseurs')
            ->with('success', 'Fournisseur ajouté.');
    }

    public function edit($id)
    {
        return view('fournisseurs/form', [
            'title' => 'Modifier fournisseur',
            'fournisseur' => $this->model
                ->where('tenant_id', $this->tenantId())
                ->find($id)
        ]);
    }

    public function update($id)
    {
        $this->model->update($id, $this->request->getPost());

        return redirect()->to('fournisseurs')
            ->with('success', 'Fournisseur modifié.');
    }

    public function delete($id)
    {
        $this->model
            ->where('tenant_id', $this->tenantId())
            ->delete($id);

        return redirect()->to('fournisseurs')
            ->with('success', 'Fournisseur supprimé.');
    }
}