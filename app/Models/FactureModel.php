<?php

namespace App\Models;

class FactureModel extends TenantModel
{
    protected $table            = 'factures';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'tenant_id',
        'numero',
        'client_id',
        'reservation_id',
        'cotation_id',
        'date_facture',
        'date_echeance',
        'devise',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'montant_paye',
        'montant_restant',
        'statut',
        'notes_client',
        'notes_interne',
        'created_by',
    ];

    public function prochaineReference(int $tenantId): string
    {
        $annee = date('Y');

        $count = $this
            ->where('tenant_id', $tenantId)
            ->like('numero', 'FAC-' . $annee . '-', 'after')
            ->countAllResults();

        return sprintf('FAC-%s-%05d', $annee, $count + 1);
    }

    public function recalculerMontants(int $factureId): void
    {
        $facture = $this->find($factureId);
        if (! $facture) {
            return;
        }

        $ligneModel = new FactureLigneModel();

        $result = $ligneModel
            ->selectSum('montant', 'total')
            ->where('facture_id', $factureId)
            ->first();

        $montantHt = (float) ($result['total'] ?? 0);

        // TVA simple (à adapter si vous avez un taux configurable)
        $tauxTva   = 0; // ex: 20 pour 20 %
        $montantTva = $montantHt * $tauxTva / 100;
        $montantTtc = $montantHt + $montantTva;

        $paiementModel = new PaiementModel();
        $payeResult = $paiementModel
            ->selectSum('montant', 'total')
            ->where('facture_id', $factureId)
            ->where('statut', 'valide')
            ->first();

        $montantPaye    = (float) ($payeResult['total'] ?? 0);
        $montantRestant = max(0, $montantTtc - $montantPaye);

        // Statut auto
        $statut = $facture['statut'];
        if ($statut !== 'annulee' && $statut !== 'brouillon') {
            if ($montantPaye <= 0) {
                $statut = 'emise';
            } elseif ($montantRestant <= 0.01) {
                $statut = 'payee';
            } else {
                $statut = 'partiellement_payee';
            }

            // En retard ?
            if (
                in_array($statut, ['emise', 'partiellement_payee'], true)
                && ! empty($facture['date_echeance'])
                && $facture['date_echeance'] < date('Y-m-d')
            ) {
                $statut = 'en_retard';
            }
        }

        $this->update($factureId, [
            'montant_ht'      => $montantHt,
            'montant_tva'     => $montantTva,
            'montant_ttc'     => $montantTtc,
            'montant_paye'    => $montantPaye,
            'montant_restant' => $montantRestant,
            'statut'          => $statut,
        ]);
    }
}