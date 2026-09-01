<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Paiements</h4>
        <p class="text-muted mb-0">Historique des encaissements</p>
    </div>
    <a href="<?= site_url('factures') ?>" class="btn btn-light border">
        Voir les factures
    </a>
</div>

<div class="lc-card p-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>N°</th>
                    <th>Date</th>
                    <th>Facture</th>
                    <th>Client</th>
                    <th>Mode</th>
                    <th class="text-end">Montant</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Aucun paiement.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                            $client = trim(($item['client_prenom'] ?? '') . ' ' . ($item['client_nom'] ?? ''));
                            $annule = ($item['statut'] ?? '') === 'annule';
                        ?>
                        <tr class="<?= $annule ? 'table-secondary' : '' ?>">
                            <td class="fw-semibold"><?= esc($item['numero']) ?></td>
                            <td><?= date('d/m/Y', strtotime($item['date_paiement'])) ?></td>
                            <td>
                                <?php if (!empty($item['facture_id'])): ?>
                                    <a href="<?= site_url('factures/' . $item['facture_id']) ?>">
                                        <?= esc($item['facture_numero'] ?? '') ?>
                                    </a>
                                <?php else: ?>—<?php endif; ?>
                            </td>
                            <td><?= esc($client ?: '—') ?></td>
                            <td class="text-capitalize"><?= esc(str_replace('_', ' ', $item['mode_paiement'] ?? '')) ?></td>
                            <td class="text-end fw-semibold <?= $annule ? 'text-muted text-decoration-line-through' : 'text-success' ?>">
                                <?= number_format((float)$item['montant'], 2, ',', ' ') ?>
                                <small><?= esc($item['devise'] ?? '') ?></small>
                            </td>
                            <td>
                                <span class="badge <?= $annule ? 'bg-secondary' : 'bg-success' ?>">
                                    <?= $annule ? 'Annulé' : 'Validé' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>