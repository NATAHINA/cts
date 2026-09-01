<?php

namespace App\Controllers;

use App\Models\FactureModel;
use App\Models\PaiementModel;

class PaiementController extends BaseController
{
    protected $paiementModel;
    protected $factureModel;

    public function __construct()
    {
        $this->paiementModel = new PaiementModel();
        $this->factureModel  = new FactureModel();
    }

    protected function tenantId(): int
    {
        return (int) session('tenant_id');
    }

    public function index()
    {
        $items = $this->paiementModel
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

    /**
     * Enregistre un paiement sur une FACTURE
     */
    public function store()
    {
        $tenantId  = $this->tenantId();
        $factureId = (int) $this->request->getPost('facture_id');

        if ($factureId <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Facture non précisée.');
        }

        $facture = $this->factureModel
            ->where('tenant_id', $tenantId)
            ->find($factureId);

        if (! $facture) {
            return redirect()->back()
                ->with('error', 'Facture introuvable.');
        }

        if (in_array($facture['statut'] ?? '', ['annulee', 'brouillon'], true)) {
            return redirect()->back()
                ->with('error', 'Impossible d\'enregistrer un paiement sur cette facture.');
        }

        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Montant invalide.');
        }

        // Optionnel : ne pas dépasser le restant dû
        $restant = (float) ($facture['montant_restant'] ?? 0);
        if ($restant > 0 && $montant > $restant + 0.01) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le montant dépasse le restant dû (' . number_format($restant, 2, ',', ' ') . ').');
        }

        $numero = $this->paiementModel->prochaineReference($tenantId);

        $id = $this->paiementModel->insert([
            'tenant_id'         => $tenantId,
            'numero'            => $numero,
            'facture_id'        => $factureId,
            'client_id'         => $facture['client_id'] ?? null,
            'date_paiement'     => $this->request->getPost('date_paiement') ?: date('Y-m-d'),
            'montant'           => $montant,
            'devise'            => $facture['devise'] ?? 'MGA',
            'mode_paiement'     => $this->request->getPost('mode_paiement') ?: 'virement',
            'reference_externe' => $this->request->getPost('reference_externe'),
            'notes'             => $this->request->getPost('notes'),
            'statut'            => 'valide',
            'created_by'        => session('user_id'),
        ]);

        if (! $id) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->paiementModel->errors());
        }

        // Recalcule payé / restant / statut de la facture
        $this->factureModel->recalculerMontants($factureId);

        return redirect()
            ->to(site_url('factures/' . $factureId))
            ->with('success', 'Paiement ' . $numero . ' enregistré.');
    }

    public function annuler($id)
    {
        $paiement = $this->paiementModel
            ->where('tenant_id', $this->tenantId())
            ->find($id);

        if (! $paiement) {
            return redirect()->to(site_url('paiements'))
                ->with('error', 'Paiement introuvable.');
        }

        $this->paiementModel->update($id, ['statut' => 'annule']);
        $this->factureModel->recalculerMontants((int) $paiement['facture_id']);

        return redirect()
            ->to(site_url('factures/' . $paiement['facture_id']))
            ->with('success', 'Paiement annulé.');
    }
}