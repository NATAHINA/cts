<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <div>
        <h5 class="mb-0">Liste des forfaits</h5>
        <small class="text-secondary">
            Gérez les forfaits et leurs tarifs.
        </small>
    </div>

    <a href="<?= site_url('forfaits/new') ?>" class="btn lc-btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Ajouter un forfait
    </a>

</div>

<?php if (session('success')): ?>
    <div class="alert alert-success">
        <?= esc(session('success')) ?>
    </div>
<?php endif; ?>

<?php if (session('error')): ?>
    <div class="alert alert-danger">
        <?= esc(session('error')) ?>
    </div>
<?php endif; ?>

<div class="lc-card p-3">

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead>
                <tr class="text-secondary small">
                    <th>Code</th>
                    <th>Forfait</th>
                    <th>Destination</th>
                    <th>Fournisseur</th>
                    <th>Durée</th>
                    <th>Prix</th>
                    <th>Disponibilité</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($items)): ?>

                    <tr>
                        <td colspan="9" class="text-secondary text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <div class="mt-2">
                                Aucun forfait pour le moment.
                            </div>
                        </td>
                    </tr>

                <?php endif; ?>

                <?php foreach ($items as $it): ?>

                    <tr>

                        <td>
                            <?= ! empty($it['code'])
                                ? esc($it['code'])
                                : '—' ?>
                        </td>

                        <td>
                            <div class="fw-semibold">
                                <?= esc($it['nom'] ?? '—') ?>
                            </div>

                            <?php if (! empty($it['description'])): ?>
                                <small class="text-secondary">
                                    <?= esc(
                                        mb_strimwidth(
                                            $it['description'],
                                            0,
                                            50,
                                            '...'
                                        )
                                    ) ?>
                                </small>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= esc($it['destination_nom'] ?? '—') ?>
                        </td>

                        <td>
                            <?= esc($it['fournisseur_nom'] ?? '—') ?>
                        </td>

                        <td>
                            <?php if (
                                ! empty($it['duree_jours']) ||
                                ! empty($it['duree_nuits'])
                            ): ?>

                                <?= (int) ($it['duree_jours'] ?? 0) ?> j

                                <?php if (
                                    isset($it['duree_nuits']) &&
                                    $it['duree_nuits'] !== null &&
                                    $it['duree_nuits'] !== ''
                                ): ?>
                                    / <?= (int) $it['duree_nuits'] ?> n
                                <?php endif; ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (
                                isset($it['prix']) &&
                                $it['prix'] !== null &&
                                $it['prix'] !== ''
                            ): ?>

                                <strong>
                                    <?= number_format(
                                        (float) $it['prix'],
                                        0,
                                        ',',
                                        ' '
                                    ) ?>
                                </strong>

                                <small class="text-secondary">
                                    <?= esc($it['devise_code'] ?? '') ?>
                                </small>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (
                                ($it['disponibilite'] ?? 'disponible') === 'disponible'
                            ): ?>
                                <span class="badge text-bg-success">
                                    Disponible
                                </span>
                            <?php else: ?>
                                <span class="badge text-bg-warning">
                                    Indisponible
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (($it['statut'] ?? '') === 'actif'): ?>
                                <span class="badge rounded-pill lc-badge-actif">
                                    Actif
                                </span>
                            <?php else: ?>
                                <span class="badge rounded-pill lc-badge-inactif">
                                    Inactif
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="text-end">

                            <a
                                href="<?= site_url(
                                    'forfaits/' . $it['id'] . '/edit'
                                ) ?>"
                                class="btn btn-sm btn-light border"
                                title="Modifier"
                            >
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a
                                href="<?= site_url(
                                    'forfaits/' . $it['id'] . '/delete'
                                ) ?>"
                                class="btn btn-sm btn-light border text-danger"
                                title="Supprimer"
                                onclick="return confirm('Voulez-vous vraiment supprimer ce forfait ?');"
                            >
                                <i class="bi bi-trash"></i>
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?= $this->endSection() ?>