<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php

$statut = $reservation['statut'] ?? 'en_attente';

$statutClass = match ($statut) {
    'confirmée', 'confirmee' => 'text-bg-success',
    'en_attente'             => 'text-bg-warning',
    'annulée', 'annulee'      => 'text-bg-danger',
    'terminée', 'terminee'    => 'text-bg-secondary',
    default                   => 'text-bg-primary',
};

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <div class="text-muted small">
            Réservation
        </div>

        <h4 class="mb-1">
            <?= esc($reservation['numero'] ?? '-') ?>
        </h4>

        <span class="badge <?= $statutClass ?>">
            <?= esc($statut) ?>
        </span>

    </div>

    <div class="d-flex gap-2">

        <?php
            $statutRes = $reservation['statut'] ?? '';
            $peutFacturer = ! in_array($statutRes, ['annulee', 'annulée'], true);
        ?>

        <?php if (! empty($factureExistante)): ?>
            <a href="<?= site_url('factures/' . $factureExistante['id']) ?>"
            class="btn btn-light border">
                <i class="bi bi-receipt me-1"></i>
                Voir la facture
                <?= esc($factureExistante['numero']) ?>
            </a>

            <?php if ((float)($factureExistante['montant_restant'] ?? 0) > 0): ?>
                <a href="<?= site_url('factures/' . $factureExistante['id']) ?>#paiement"
                class="btn lc-btn-primary">
                    <i class="bi bi-cash-coin me-1"></i>
                    Enregistrer un paiement
                </a>
            <?php endif; ?>

        <?php elseif ($peutFacturer): ?>
            <button type="button"
                    class="btn lc-btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalCreerFacture">
                <i class="bi bi-receipt me-1"></i>
                Créer la facture
            </button>
        <?php endif; ?>

        <a href="<?= site_url('reservations/' . $reservation['id'] . '/edit') ?>"
           class="btn btn-info border">

            <i class="bi bi-pencil me-1"></i>
            Modifier

        </a>

        <a href="<?= site_url('reservations') ?>"
           class="btn btn-light border">

            <i class="bi bi-arrow-left me-1"></i>
            Retour

        </a>

    </div>

</div>


<div class="row g-3">

    <!-- INFORMATIONS CLIENT ET VOYAGE -->
    <div class="col-lg-4 col-md-6">

        <div class="lc-card p-4 mb-3">

            <h6 class="mb-4">
                <i class="bi bi-person me-2"></i>
                Informations du client
            </h6>

            <div class="row g-3">

                <div class="col-md-6">

                    <div class="text-muted small">
                        Client
                    </div>

                    <div class="fw-semibold">

                        <?= esc(
                            trim(
                                ($reservation['client_prenom'] ?? '') . ' ' .
                                ($reservation['client_nom'] ?? '')
                            )
                        ) ?: '—' ?>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Numéro de cotation
                    </div>

                    <div class="fw-semibold">

                        <?= esc(
                            $reservation['cotation_numero'] ?? 'Sans cotation'
                        ) ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-4 col-md-6">

        <div class="lc-card p-4 mb-3">

            <h6 class="mb-4">
                <i class="bi bi-airplane me-2"></i>
                Informations du voyage
            </h6>

            <div class="row g-3">

                <div class="col-md-6">

                    <div class="text-muted small">
                        Date de départ
                    </div>

                    <div class="fw-semibold">
                        <?= esc($reservation['date_depart'] ?? '—') ?>
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Date de retour
                    </div>

                    <div class="fw-semibold">
                        <?= esc($reservation['date_retour'] ?? '—') ?>
                    </div>

                </div>

            </div>

        </div>


    </div>

    <div class="col-lg-4 col-md-6">

        <div class="lc-card p-4">

            <h6 class="mb-4">
                <i class="bi bi-people me-2"></i>
                Voyageurs
            </h6>

            <div class="row text-center g-3">

                <div class="col-4">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Adultes
                        </div>

                        <div class="fs-4 fw-bold">
                            <?= (int) ($reservation['nb_adultes'] ?? 0) ?>
                        </div>

                    </div>

                </div>


                <div class="col-4">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Enfants
                        </div>

                        <div class="fs-4 fw-bold">
                            <?= (int) ($reservation['nb_enfants'] ?? 0) ?>
                        </div>

                    </div>

                </div>


                <div class="col-4">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Bébés
                        </div>

                        <div class="fs-4 fw-bold">
                            <?= (int) ($reservation['nb_bebes'] ?? 0) ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- LIGNES DE PRESTATION -->
<div class="lc-card p-4 mb-3">
    <h6 class="mb-3">
        <i class="bi bi-list-ul me-2"></i>
        Prestations
    </h6>

    <?php if (empty($lignes)): ?>
        <p class="text-muted mb-0">Aucune ligne de prestation.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Désignation</th>
                        <th class="text-center">Qté</th>
                        <th class="text-end">P.U.</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes as $ligne): ?>
                        <tr>
                            <td>
                                <?php
                                    $typeLabels = [
                                        'hotel'     => 'Hôtel',
                                        'vol'       => 'Vol',
                                        'excursion' => 'Excursion',
                                        'transfert' => 'Transfert',
                                        'croisiere' => 'Croisière',
                                        'restaurant' => 'Restaurant',
                                        'forfait'   => 'Forfait',
                                        'circuit'   => 'Circuit',
                                        'autre'     => 'Autre prestation',
                                    ];
                                    $type = $ligne['type_prestation'] ?? 'autre';
                                    ?>
                                    <span class="badge text-bg-light border">
                                        <?= esc($typeLabels[$type] ?? ucfirst($type)) ?>
                                    </span>
                            </td>
                            <td><?= esc($ligne['designation'] ?? '') ?></td>
                            <td class="text-center"><?= (int) ($ligne['quantite'] ?? 0) ?></td>
                            <td class="text-end">
                                <?= number_format((float) ($ligne['prix_unitaire'] ?? 0), 0, ',', ' ') ?>
                            </td>
                            <td class="text-end fw-semibold">
                                <?= number_format((float) ($ligne['prix_total'] ?? 0), 0, ',', ' ') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-semibold">Total</td>
                        <td class="text-end fw-bold">
                            <?= number_format((float) ($reservation['montant_total'] ?? 0), 0, ',', ' ') ?>
                            <?= esc($reservation['devise'] ?? '') ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>


    <!-- RÉCAPITULATIF -->
    <div class="col-lg-4 col-md-6">

        <div class="lc-card p-4 mb-3">

            <h6 class="mb-3">
                Récapitulatif financier
            </h6>

            <div class="d-flex justify-content-between align-items-center mb-3">

                <span class="text-muted">
                    Montant total
                </span>

                <strong class="fs-5">

                    <?= number_format(
                        (float) ($reservation['montant_total'] ?? 0),
                        0,
                        ',',
                        ' '
                    ) ?>

                    <?= esc($reservation['devise'] ?? '') ?>

                </strong>
            </div>

            <?php if (! empty($factureExistante)): ?>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Facture</span>
                    <a href="<?= site_url('factures/' . $factureExistante['id']) ?>">
                        <?= esc($factureExistante['numero']) ?>
                    </a>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="text-muted">Restant dû</span>
                    <strong class="<?= ((float)$factureExistante['montant_restant'] > 0) ? 'text-danger' : 'text-success' ?>">
                        <?= number_format((float)$factureExistante['montant_restant'], 0, ',', ' ') ?>
                        <?= esc($factureExistante['devise'] ?? $reservation['devise'] ?? '') ?>
                    </strong>
                </div>
            <?php endif; ?>

        </div>

    </div>

    <div class="col-lg-4 col-md-6">

        <div class="lc-card p-4 mb-3">

            <h6 class="mb-3">
                Destination
            </h6>

            <div class="fw-semibold">

                <?= esc(
                    $reservation['destination_nom'] ?? 'Non définie'
                ) ?>

            </div>

        </div>

    </div>

    <div class="col-lg-4 col-md-6">

        <div class="lc-card p-4">

            <h6 class="mb-3">
                Informations
            </h6>

            <div class="mb-3">

                <div class="text-muted small">
                    Créée le
                </div>

                <div>
                    <?= esc($reservation['created_at'] ?? '—') ?>
                </div>

            </div>


            <?php if (!empty($reservation['notes_client'])): ?>

                <hr>

                <div class="text-muted small mb-1">
                    Notes client
                </div>

                <div>
                    <?= nl2br(esc($reservation['notes_client'])) ?>
                </div>

            <?php endif; ?>


            <?php if (!empty($reservation['notes_interne'])): ?>

                <hr>

                <div class="text-muted small mb-1">
                    Notes internes
                </div>

                <div>
                    <?= nl2br(esc($reservation['notes_interne'])) ?>
                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<!-- =========================================================
     MODAL CONFIRMATION CRÉATION FACTURE
========================================================= -->
<div class="modal fade" id="modalCreerFacture" tabindex="-1" aria-labelledby="modalCreerFactureLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalCreerFactureLabel">
                    <i class="bi bi-receipt me-2"></i>
                    Créer une facture
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    Voulez-vous vraiment créer une facture à partir de cette réservation
                    <strong><?= esc($reservation['numero'] ?? '') ?></strong> ?
                </p>
                <p class="text-muted small mt-2 mb-0">
                    Cette action générera une nouvelle facture liée à la réservation.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                    Annuler
                </button>

                <a href="<?= site_url('reservations/' . $reservation['id'] . '/facture') ?>"
                   class="btn lc-btn-primary">
                    <i class="bi bi-check-lg me-1"></i>
                    Oui, créer la facture
                </a>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>