<?php

namespace App\Models;

class CotationModel extends TenantModel
{
    protected $table            = 'cotations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'tenant_id', 'client_id', 'user_id', 'reference', 'statut',
        'date_validite', 'devise_id', 'montant_total', 'notes',
    ];

    /**
     * Recalcule et enregistre le montant total à partir des lignes.
     */
    public function recalculerMontant(int $cotationId): void
    {
        $ligneModel = new CotationLigneModel();
        $total      = $ligneModel->where('cotation_id', $cotationId)->selectSum('montant')->first()['montant'] ?? 0;

        $this->update($cotationId, ['montant_total' => $total]);
    }

    public function prochaineReference(int $tenantId): string
    {
        $annee = date('Y');
        $count = $this->where('reference LIKE', 'DEV-' . $annee . '-%')->countAllResults();

        return sprintf('DEV-%s-%04d', $annee, $count + 1);
    }
}
