<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$statut = $facture['statut'] ?? 'brouillon';
$statutsClass = [
    'brouillon'           => 'bg-secondary',
    'emise'               => 'bg-primary',
    'partiellement_payee' => 'bg-warning text-dark',
    'payee'               => 'bg-success',
    'annulee'             => 'bg-danger',
    'en_retard'           => 'bg-danger',
];
$statutsLabel = [
    'brouillon'           => 'Brouillon',
    'emise'               => 'Émise',
    'partiellement_payee' => 'Partiellement payée',
    'payee'               => 'Payée',
    'annulee'             => 'Annulée',
    'en_retard'           => 'En retard',
];
$clientNom = trim(($facture['client_prenom'] ?? '') . ' ' . ($facture['client_nom'] ?? ''));
if ($clientNom === '') {
    $clientNom = $facture['client_entreprise'] ?? '—';
}
$devise = $facture['devise'] ?? 'MGA';
$canEdit = ! in_array($statut, ['payee', 'annulee'], true);
$canPay  = ! in_array($statut, ['brouillon', 'annulee', 'payee'], true)
           && (float)($facture['montant_restant'] ?? 0) > 0;
?>

<!-- En-tête -->
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="mb-0 fw-bold">Facture <?= esc($facture['numero']) ?></h4>
            <span class="badge <?= $statutsClass[$statut] ?? 'bg-secondary' ?>">
                <?= esc($statutsLabel[$statut] ?? $statut) ?>
            </span>
        </div>
        <p class="text-muted mb-0">
            Émise le
            <?= !empty($facture['date_facture'])
                ? date('d/m/Y', strtotime($facture['date_facture']))
                : '—' ?>
            <?php if (!empty($facture['date_echeance'])): ?>
                · Échéance <?= date('d/m/Y', strtotime($facture['date_echeance'])) ?>
            <?php endif; ?>
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= site_url('factures') ?>" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
        <a href="<?= site_url('factures/' . $facture['id'] . '/print') ?>"
           class="btn btn-light border" target="_blank">
            <i class="bi bi-printer me-1"></i> Imprimer
        </a>
        <?php if ($canEdit): ?>
            <a href="<?= site_url('factures/' . $facture['id'] . '/edit') ?>" class="btn btn-light border">
                <i class="bi bi-pencil-square me-1"></i> Modifier
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">

    <!-- Colonne gauche -->
    <div class="col-lg-8">

        <!-- Client -->
        <div class="lc-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Client</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted d-block">Nom</small>
                    <div class="fw-semibold"><?= esc($clientNom) ?></div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Entreprise</small>
                    <div><?= esc($facture['client_entreprise'] ?? '—') ?></div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">E-mail</small>
                    <div>
                        <?php if (!empty($facture['client_email'])): ?>
                            <a href="mailto:<?= esc($facture['client_email']) ?>"><?= esc($facture['client_email']) ?></a>
                        <?php else: ?>—<?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Téléphone</small>
                    <div><?= esc($facture['client_telephone'] ?? '—') ?></div>
                </div>
            </div>
        </div>

        <!-- Lignes -->
        <div class="lc-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-list-ul me-2"></i>Lignes de facture</h6>

            <?php if (!empty($lignes)): ?>
                <div class="table-responsive mb-3">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Désignation</th>
                                <th class="text-end">Qté</th>
                                <th class="text-end">P.U.</th>
                                <th class="text-end">Montant</th>
                                <?php if ($canEdit): ?><th></th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lignes as $i => $l): ?>
                                <tr>
                                    <td class="text-muted"><?= $i + 1 ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= esc($l['designation']) ?></div>
                                        <?php if (!empty($l['description'])): ?>
                                            <small class="text-muted"><?= esc($l['description']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end"><?= esc($l['quantite']) ?></td>
                                    <td class="text-end"><?= number_format((float)$l['prix_unitaire'], 2, ',', ' ') ?></td>
                                    <td class="text-end fw-semibold"><?= number_format((float)$l['montant'], 2, ',', ' ') ?></td>
                                    <?php if ($canEdit): ?>
                                        <td class="text-end">
                                            <form method="post"
                                                  action="<?= site_url('factures/' . $facture['id'] . '/lignes/' . $l['id'] . '/delete') ?>"
                                                  onsubmit="return confirm('Supprimer cette ligne ?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mb-3">Aucune ligne.</p>
            <?php endif; ?>

            <?php if ($canEdit): ?>
    <hr>
    <h6 class="fw-bold mb-3">Ajouter une ligne</h6>

    <form method="post" action="<?= site_url('factures/' . $facture['id'] . '/lignes') ?>">
        <?= csrf_field() ?>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Type de prestation</label>
                <select name="type_prestation" id="fac_type_prestation" class="form-select">
                    <option value="">— Saisie manuelle —</option>
                    <option value="hotel">Hôtel</option>
                    <option value="vol">Vol</option>
                    <option value="excursion">Excursion</option>
                    <option value="transfert">Transfert</option>
                    <option value="restaurant">Restaurant</option>
                    <option value="croisiere">Croisière</option>
                    <option value="forfait">Forfait</option>
                    <option value="circuit">Circuit</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <div class="col-md-8">
                <label class="form-label">Prestation du catalogue</label>
                <select name="prestation_id" id="fac_prestation_id" class="form-select">
                    <option value="">— Choisir ou saisir à la main —</option>
                </select>
                <div class="form-text">Le nom et le prix se remplissent automatiquement</div>
            </div>

            <div class="col-md-12">
                <label class="form-label">Désignation <span class="text-danger">*</span></label>
                <input type="text"
                       name="designation"
                       id="fac_designation"
                       class="form-control"
                       placeholder="Ex : Hôtel Palm Beach — chambre double"
                       required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Description <span class="text-muted">(optionnel)</span></label>
                <input type="text"
                       name="description"
                       class="form-control"
                       placeholder="Précisions sur la facture">
            </div>

            <div class="col-md-4">
                <label class="form-label">Quantité</label>
                <input type="number"
                       name="quantite"
                       class="form-control"
                       value="1"
                       min="0.01"
                       step="0.01">
                <div class="form-text">Nombre d’unités (nuits, pax…)</div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Prix unitaire (<?= esc($devise) ?>)</label>
                <input type="number"
                       name="prix_unitaire"
                       id="fac_prix_unitaire"
                       class="form-control"
                       min="0"
                       step="0.01"
                       placeholder="0,00"
                       required>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn lc-btn-primary w-100">
                    <i class="bi bi-plus-lg me-1"></i>
                    Ajouter la ligne
                </button>
            </div>

        </div>
    </form>

    <?php if (!empty($catalogue)): ?>
        <script>
        (function () {
            const catalogue = <?= json_encode($catalogue ?? []) ?>;
            const typeSelect  = document.getElementById('fac_type_prestation');
            const prestaSelect = document.getElementById('fac_prestation_id');
            const designation  = document.getElementById('fac_designation');
            const prixInput    = document.getElementById('fac_prix_unitaire');

            function fillPrestations() {
                const type = typeSelect.value;
                prestaSelect.innerHTML = '<option value="">— Choisir ou saisir à la main —</option>';

                if (!type || !catalogue[type]) return;

                catalogue[type].forEach(function (item) {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.label + (item.prix ? ' — ' + item.prix : '');
                    opt.dataset.label = item.label;
                    opt.dataset.prix  = item.prix || 0;
                    prestaSelect.appendChild(opt);
                });
            }

            typeSelect.addEventListener('change', fillPrestations);

            prestaSelect.addEventListener('change', function () {
                const opt = this.options[this.selectedIndex];
                if (!opt || !opt.value) return;

                if (opt.dataset.label) {
                    designation.value = opt.dataset.label;
                }
                if (opt.dataset.prix !== undefined) {
                    prixInput.value = opt.dataset.prix;
                }
            });
        })();
        </script>
        <?php endif; ?>
    <?php endif; ?>
        </div>

        <!-- Paiements -->
        <div class="lc-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-cash-coin me-2"></i>Paiements</h6>

            <?php if (!empty($paiements)): ?>
                <div class="table-responsive mb-3">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>N°</th>
                                <th>Date</th>
                                <th>Mode</th>
                                <th class="text-end">Montant</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paiements as $p): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($p['numero']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['date_paiement'])) ?></td>
                                    <td class="text-capitalize"><?= esc(str_replace('_', ' ', $p['mode_paiement'] ?? '')) ?></td>
                                    <td class="text-end text-success fw-semibold">
                                        <?= number_format((float)$p['montant'], 2, ',', ' ') ?>
                                        <?= esc($p['devise'] ?? $devise) ?>
                                    </td>
                                    <td class="text-end">
                                        <form method="post"
                                              action="<?= site_url('paiements/' . $p['id'] . '/annuler') ?>"
                                              onsubmit="return confirm('Annuler ce paiement ?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Annuler">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mb-3">Aucun paiement enregistré.</p>
            <?php endif; ?>

            <?php if ($canPay): ?>
                <hr>
                <h6 class="fw-bold mb-3">Enregistrer un paiement</h6>
                <form method="post" action="<?= site_url('paiements') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="facture_id" value="<?= $facture['id'] ?>">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="date" name="date_paiement" class="form-control"
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="montant" class="form-control" step="0.01" min="0.01"
                                   max="<?= (float)$facture['montant_restant'] ?>"
                                   placeholder="Montant *" required>
                        </div>
                        <div class="col-md-3">
                            <select name="mode_paiement" class="form-select">
                                <option value="especes">Espèces</option>
                                <option value="virement">Virement</option>
                                <option value="cheque">Chèque</option>
                                <option value="carte">Carte</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn lc-btn-primary w-100">Enregistrer</button>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="reference_externe" class="form-control"
                                   placeholder="Réf. chèque / virement">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="notes" class="form-control" placeholder="Notes">
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <?php if (!empty($facture['notes_client'])): ?>
            <div class="lc-card p-4">
                <h6 class="fw-bold mb-2">Notes client</h6>
                <div class="border rounded-3 p-3 bg-light" style="white-space:pre-line;">
                    <?= esc($facture['notes_client']) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Colonne droite — montants -->
    <div class="col-lg-4">
        <div class="lc-card p-4 position-sticky" style="top:20px;">
            <h6 class="fw-bold mb-4"><i class="bi bi-calculator me-2"></i>Montants</h6>

            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Montant HT</span>
                <span class="fw-semibold">
                    <?= number_format((float)($facture['montant_ht'] ?? 0), 2, ',', ' ') ?>
                    <?= esc($devise) ?>
                </span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">TVA</span>
                <span>
                    <?= number_format((float)($facture['montant_tva'] ?? 0), 2, ',', ' ') ?>
                    <?= esc($devise) ?>
                </span>
            </div>
            <hr>
            <div class="d-flex justify-content-between mb-3">
                <span class="fw-bold">Total TTC</span>
                <span class="fw-bold fs-5" style="color:var(--lc-accent);">
                    <?= number_format((float)($facture['montant_ttc'] ?? 0), 2, ',', ' ') ?>
                    <?= esc($devise) ?>
                </span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-success">Payé</span>
                <span class="text-success fw-semibold">
                    <?= number_format((float)($facture['montant_paye'] ?? 0), 2, ',', ' ') ?>
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="<?= ((float)($facture['montant_restant'] ?? 0) > 0) ? 'text-danger' : 'text-muted' ?>">
                    Restant dû
                </span>
                <span class="fw-bold <?= ((float)($facture['montant_restant'] ?? 0) > 0) ? 'text-danger' : '' ?>">
                    <?= number_format((float)($facture['montant_restant'] ?? 0), 2, ',', ' ') ?>
                    <?= esc($devise) ?>
                </span>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>