<?php

namespace App\Models;

class CotationModel extends TenantModel
{
    protected $table            = 'cotations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        // Multi-agence
        'tenant_id',

        // Identification
        'numero',

        // Relations
        'client_id',
        'demande_id',
        'destination_id',

        // Voyage
        'date_depart',
        'date_retour',
        'nb_adultes',
        'nb_enfants',
        'nb_bebes',

        // Devise
        'devise',
        'taux_change',

        // Montants
        'cout_total',

        'marge_montant',
        'marge_pourcentage',

        'reduction_montant',
        'reduction_pourcentage',

        'taxe_montant',
        'prix_total',
        'prix_par_personne',

        // État
        'statut',

        // Validité
        'date_validite',

        // Notes
        'notes_client',
        'notes_interne',

        // Utilisateurs
        'created_by',
        'validated_by',
        'validated_at',
    ];

    /**
     * Génère le prochain numéro de cotation.
     */
    public function prochaineReference(int $tenantId): string
    {
        $annee = date('Y');

        $count = $this
            ->where('tenant_id', $tenantId)
            ->like('numero', 'DEV-' . $annee . '-', 'after')
            ->countAllResults();

        return sprintf(
            'DEV-%s-%05d',
            $annee,
            $count + 1
        );
    }

    /**
     * Recalcule tous les montants de la cotation
     * à partir de ses lignes.
     */
    /**
 * Recalcule tous les montants de la cotation
 * à partir de ses lignes.
 */
public function recalculerMontants(int $cotationId): void
{
    $cotation = $this->find($cotationId);

    if (! $cotation) {
        return;
    }

    $ligneModel = new CotationLigneModel();

    $coutResult = $ligneModel
        ->selectSum('cout_total', 'total')
        ->where('cotation_id', $cotationId)
        ->first();

    $coutTotal = (float) ($coutResult['total'] ?? 0);

    $prixResult = $ligneModel
        ->selectSum('prix_total', 'total')
        ->where('cotation_id', $cotationId)
        ->first();

    $prixLignes = (float) ($prixResult['total'] ?? 0);

    $margeMontant = $prixLignes - $coutTotal;

    // Si un coût existe → marge calculée à partir des lignes
    // Sinon → on garde la marge saisie sur la cotation (marge par défaut)
    if ($coutTotal > 0) {
        $margePourcentage = ($margeMontant / $coutTotal) * 100;
    } else {
        $margePourcentage = (float) ($cotation['marge_pourcentage'] ?? 0);
    }

    $reductionPourcentage = (float) (
        $cotation['reduction_pourcentage'] ?? 0
    );

    $reductionMontant = $reductionPourcentage > 0
        ? $prixLignes * $reductionPourcentage / 100
        : (float) ($cotation['reduction_montant'] ?? 0);

    $taxeMontant = (float) (
        $cotation['taxe_montant'] ?? 0
    );

    $prixTotal = $prixLignes
        - $reductionMontant
        + $taxeMontant;

    $nbPersonnes =
        (int) ($cotation['nb_adultes'] ?? 0)
        + (int) ($cotation['nb_enfants'] ?? 0);

    $prixParPersonne = $nbPersonnes > 0
        ? $prixTotal / $nbPersonnes
        : 0;

    $this->update($cotationId, [
        'cout_total'        => $coutTotal,
        'marge_montant'     => $margeMontant,
        'marge_pourcentage' => $margePourcentage,
        'reduction_montant' => $reductionMontant,
        'prix_total'        => $prixTotal,
        'prix_par_personne' => $prixParPersonne,
    ]);
}
}