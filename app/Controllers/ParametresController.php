<?php

namespace App\Controllers;

use App\Models\FactureModel;
use App\Models\PaiementModel;

class PaiementController extends BaseController
{
    protected function tenantId(): int
    {
        return (int) session('tenant_id');
    }

    public function index()
    {
        $model = new PaiementModel();

        $items = $model
            ->select('
                paiements.*,
                factures.numero AS facture_numero,
                clients.nom AS client_nom,
                clients.prenom AS client_prenom
            ')
            ->join('factures', 'factures.id = paiements.facture_id', 'left')
            ->join('clients', 'clients.id = paiements.client_id', 'left')
            ->where('paiements.tenant_id', $this->tenantId())
            ->orderBy('paiements.date_paiement', 'DESC')
            ->findAll();

        return view('paiements/index', [
            'title' => 'Paiements',
            'items' => $items,
        ]);
    }

    public function store()
    {
        $factureModel  = new FactureModel();
        $paiementModel = new PaiementModel();
        $tenantId      = $this->tenantId();

        $factureId = (int) $this->request->getPost('facture_id');
        $facture   = $factureModel
            ->where('tenant_id', $tenantId)
            ->find($factureId);

        if (! $facture) {
            return redirect()->back()->with('error', 'Facture introuvable.');
        }

        if (in_array($facture['statut'], ['annulee', 'brouillon'], true)) {
            return redirect()->back()->with('error', 'Impossible d\'enregistrer un paiement sur cette facture.');
        }

        $montant = (float) $this->request->getPost('montant');
        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide.');
        }

        $numero = $paiementModel->prochaineReference($tenantId);

        $paiementModel->insert([
            'tenant_id'         => $tenantId,
            'numero'            => $numero,
            'facture_id'        => $factureId,
            'client_id'         => $facture['client_id'],
            'date_paiement'     => $this->request->getPost('date_paiement') ?: date('Y-m-d'),
            'montant'           => $montant,
            'devise'            => $facture['devise'] ?? 'MGA',
            'mode_paiement'     => $this->request->getPost('mode_paiement') ?: 'virement',
            'reference_externe' => $this->request->getPost('reference_externe'),
            'notes'             => $this->request->getPost('notes'),
            'statut'            => 'valide',
            'created_by'        => session('user_id'),
        ]);

        $factureModel->recalculerMontants($factureId);

        return redirect()
            ->to(site_url('factures/' . $factureId))
            ->with('success', 'Paiement ' . $numero . ' enregistré.');
    }

    public function annuler($id)
    {
        $paiementModel = new PaiementModel();
        $factureModel  = new FactureModel();

        $paiement = $paiementModel
            ->where('tenant_id', $this->tenantId())
            ->find($id);

        if (! $paiement) {
            return redirect()->to(site_url('paiements'))->with('error', 'Paiement introuvable.');
        }

        $paiementModel->update($id, ['statut' => 'annule']);
        $factureModel->recalculerMontants((int) $paiement['facture_id']);

        return redirect()
            ->to(site_url('factures/' . $paiement['facture_id']))
            ->with('success', 'Paiement annulé.');
    }
}