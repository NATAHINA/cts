<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$nomClient = trim(($demande['client_prenom'] ?? '') . ' ' . ($demande['client_nom'] ?? ''));
if ($nomClient === '') {
    $nomClient = $demande['client_entreprise'] ?? 'Client non renseigné';
}

$devise     = $demande['devise'] ?? 'MGA';
$modeTarif  = $demande['mode_tarif'] ?? 'lignes';
$prixFinal  = ($modeTarif === 'forfait' && !empty($demande['prix_forfait']))
    ? (float) $demande['prix_forfait']
    : (float) ($totalPrix ?? 0);

$nbLignes = count($lignes ?? []);
?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <div class="text-secondary small mb-1">CONVERSION DE DEMANDE</div>
            <h4 class="mb-1">Créer une cotation</h4>
            <p class="text-secondary mb-0">
                Transformez la demande
                <strong><?= esc($demande['numero']) ?></strong>
                en une nouvelle cotation.
            </p>
        </div>

        <a href="<?= site_url('demandes/' . $demande['id']) ?>" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>
</div>


<div class="row g-4">

    <!-- ===================== COLONNE GAUCHE ===================== -->
    <div class="col-lg-8">

        <!-- CLIENT -->
        <div class="lc-card p-4 mb-4">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:45px;height:45px;background:#eef3ff;">
                    <i class="bi bi-person fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0">Client</h6>
                    <small class="text-secondary">Informations du client associé à la demande</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="small text-secondary">Nom complet</div>
                    <div class="fw-semibold"><?= esc($nomClient) ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="small text-secondary">Entreprise</div>
                    <div><?= esc($demande['client_entreprise'] ?? '—') ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-secondary">Adresse e-mail</div>
                    <div><?= esc($demande['client_email'] ?? '—') ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-secondary">Téléphone</div>
                    <div><?= esc($demande['client_telephone'] ?? '—') ?></div>
                </div>
            </div>
        </div>


        <!-- VOYAGE -->
        <div class="lc-card p-4 mb-4">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:45px;height:45px;background:#eef8f0;">
                    <i class="bi bi-geo-alt fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0">Informations du voyage</h6>
                    <small class="text-secondary">Détails demandés par le client</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="small text-secondary">Destination</div>
                    <div class="fw-semibold">
                        <i class="bi bi-geo-alt me-1"></i>
                        <?= esc($demande['destination_nom'] ?? $demande['destination'] ?? 'Non renseignée') ?>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="small text-secondary">Date de départ</div>
                    <div>
                        <?= !empty($demande['date_depart'])
                            ? date('d/m/Y', strtotime($demande['date_depart']))
                            : '—' ?>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="small text-secondary">Date de retour</div>
                    <div>
                        <?= !empty($demande['date_retour'])
                            ? date('d/m/Y', strtotime($demande['date_retour']))
                            : '—' ?>
                    </div>
                </div>
            </div>
        </div>


        <!-- VOYAGEURS -->
        <div class="lc-card p-4 mb-4">
            <h6 class="mb-3"><i class="bi bi-people me-2"></i> Voyageurs</h6>

            <div class="row text-center">
                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <div class="fs-4 fw-bold"><?= (int) ($demande['nb_adultes'] ?? 0) ?></div>
                        <div class="small text-secondary">Adultes</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <div class="fs-4 fw-bold"><?= (int) ($demande['nb_enfants'] ?? 0) ?></div>
                        <div class="small text-secondary">Enfants</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <div class="fs-4 fw-bold"><?= (int) ($demande['nb_bebes'] ?? 0) ?></div>
                        <div class="small text-secondary">Bébés</div>
                    </div>
                </div>
            </div>
        </div>


        <!-- PRESTATIONS QUI SERONT COPIÉES -->
        <div class="lc-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="bi bi-list-check me-2"></i>
                    Prestations à transférer
                </h6>
                <span class="badge text-bg-light border">
                    <?= $nbLignes ?> ligne(s)
                </span>
            </div>

            <?php if ($nbLignes > 0): ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Désignation</th>
                                <th class="text-end">Qté</th>
                                <th class="text-end">Prix total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lignes as $i => $ligne): ?>
                                <tr>
                                    <td class="text-muted"><?= $i + 1 ?></td>
                                    <td>
                                        <span class="badge text-bg-light border text-capitalize">
                                            <?= esc($ligne['type_prestation'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= esc($ligne['designation'] ?? '-') ?></div>
                                        <?php if (!empty($ligne['description'])): ?>
                                            <small class="text-muted"><?= esc($ligne['description']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end"><?= esc($ligne['quantite'] ?? 1) ?></td>
                                    <td class="text-end fw-semibold">
                                        <?= number_format((float)($ligne['prix_total'] ?? 0), 2, ',', ' ') ?>
                                        <small class="text-muted"><?= esc($ligne['devise'] ?? $devise) ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-semibold">Total prestations</td>
                                <td class="text-end fw-bold" style="color:var(--lc-accent);">
                                    <?= number_format((float)($totalPrix ?? 0), 2, ',', ' ') ?>
                                    <?= esc($devise) ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-muted">
                    Aucune prestation sur cette demande.
                    La cotation sera créée vide (brouillon) : vous pourrez ajouter les lignes ensuite.
                </div>
            <?php endif; ?>
        </div>


        <!-- NOTES -->
        <?php if (!empty($demande['notes_client']) || !empty($demande['notes_interne'])): ?>
            <div class="lc-card p-4">
                <h6 class="mb-3"><i class="bi bi-chat-left-text me-2"></i> Notes et précisions</h6>

                <?php if (!empty($demande['notes_client'])): ?>
                    <div class="mb-3">
                        <div class="small text-secondary mb-1">Demande du client</div>
                        <div class="border rounded p-3 bg-light">
                            <?= nl2br(esc($demande['notes_client'])) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($demande['notes_interne'])): ?>
                    <div>
                        <div class="small text-secondary mb-1">Note interne</div>
                        <div class="border rounded p-3">
                            <?= nl2br(esc($demande['notes_interne'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>


    <!-- ===================== COLONNE DROITE ===================== -->
    <div class="col-lg-4">

        <div class="lc-card p-4 position-sticky" style="top:20px;">

            <h6 class="mb-3">Résumé de la demande</h6>

            <div class="mb-3">
                <div class="small text-secondary">Numéro</div>
                <div class="fw-semibold"><?= esc($demande['numero']) ?></div>
            </div>

            <div class="mb-3">
                <div class="small text-secondary">Date de la demande</div>
                <div>
                    <?= !empty($demande['date_demande'])
                        ? date('d/m/Y', strtotime($demande['date_demande']))
                        : '—' ?>
                </div>
            </div>

            <div class="mb-3">
                <div class="small text-secondary">Budget estimé</div>
                <div class="fs-5 fw-semibold">
                    <?php if (!empty($demande['budget'])): ?>
                        <?= number_format((float) $demande['budget'], 0, ',', ' ') ?>
                        <?= esc($devise) ?>
                    <?php else: ?>
                        Non renseigné
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <div class="small text-secondary">Source</div>
                <div><?= esc($demande['source'] ?? '—') ?></div>
            </div>

            <hr>

            <!-- Montants -->
            <div class="mb-3">
                <div class="small text-secondary">Coût total lignes</div>
                <div class="fw-semibold">
                    <?= number_format((float)($totalCout ?? 0), 2, ',', ' ') ?> <?= esc($devise) ?>
                </div>
            </div>

            <div class="mb-3">
                <div class="small text-secondary">Total prestations</div>
                <div class="fw-semibold" style="color:var(--lc-accent);">
                    <?= number_format((float)($totalPrix ?? 0), 2, ',', ' ') ?> <?= esc($devise) ?>
                </div>
            </div>

            <?php if ($modeTarif === 'forfait' && !empty($demande['prix_forfait'])): ?>
                <div class="mb-3">
                    <div class="small text-secondary">Mode forfait</div>
                    <div class="fs-5 fw-bold" style="color:var(--lc-accent);">
                        <?= number_format((float) $demande['prix_forfait'], 2, ',', ' ') ?>
                        <?= esc($devise) ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <div class="small text-secondary">Prix qui servira de base</div>
                <div class="fs-4 fw-bold" style="color:var(--lc-accent);">
                    <?= number_format($prixFinal, 2, ',', ' ') ?>
                    <span class="fs-6 text-muted"><?= esc($devise) ?></span>
                </div>
            </div>

            <hr>

            <form id="conversionForm"
                  method="post"
                  action="<?= site_url('demandes/' . $demande['id'] . '/convertir') ?>">
                <?= csrf_field() ?>

                <div class="alert alert-info small">
                    <i class="bi bi-info-circle me-1"></i>
                    Une cotation <strong>brouillon</strong> sera créée pour
                    <strong><?= esc($nomClient) ?></strong>.
                    <?= $nbLignes > 0
                        ? $nbLignes . ' prestation(s) seront copiées automatiquement.'
                        : 'Aucune prestation à copier.' ?>
                </div>

                <button type="button"
                        class="btn lc-btn-primary w-100"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmConversionModal">
                    <i class="bi bi-file-earmark-plus me-2"></i>
                    Créer la cotation
                </button>
            </form>

        </div>
    </div>
</div>


<!-- Modal confirmation -->
<div class="modal fade" id="confirmConversionModal" tabindex="-1"
     aria-labelledby="confirmConversionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="confirmConversionModalLabel">
                    <i class="bi bi-arrow-repeat me-2"></i>
                    Confirmer la conversion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body py-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:50px;height:50px;background:#eef4ff;">
                        <i class="bi bi-file-earmark-plus fs-4" style="color:#0d6efd;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-2">Convertir cette demande en cotation ?</h6>
                        <p class="text-secondary mb-0">
                            Une nouvelle cotation sera créée avec les informations du voyage
                            <?php if ($nbLignes > 0): ?>
                                et <strong><?= $nbLignes ?> prestation(s)</strong>.
                            <?php else: ?>
                                (sans prestation pour l’instant).
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="border rounded p-3 mt-4 bg-light">
                    <div class="small text-secondary mb-1">Demande</div>
                    <div class="fw-semibold"><?= esc($demande['numero'] ?? '') ?></div>
                    <div class="small mt-1">
                        Client : <strong><?= esc($nomClient) ?></strong>
                    </div>
                    <?php if ($nbLignes > 0): ?>
                        <div class="small mt-1">
                            Prestations : <strong><?= $nbLignes ?></strong>
                            — Total : <strong><?= number_format($prixFinal, 2, ',', ' ') ?> <?= esc($devise) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Annuler
                </button>
                <button type="button" class="btn lc-btn-primary" id="confirmConversionBtn">
                    <i class="bi bi-check-lg me-1"></i> Oui, créer la cotation
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const confirmBtn = document.getElementById('confirmConversionBtn');
    const form = document.getElementById('conversionForm');

    confirmBtn.addEventListener('click', function () {
        this.disabled = true;
        this.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Création en cours...
        `;
        form.submit();
    });
});
</script>

<?= $this->endSection() ?>