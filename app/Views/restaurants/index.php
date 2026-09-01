<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Restaurants</h4>
        <p class="text-muted mb-0">Catalogue des restaurants partenaires.</p>
    </div>
    <a href="<?= site_url('restaurants/new') ?>" class="btn lc-btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nouveau restaurant
    </a>
</div>

<div class="lc-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Destination</th>
                    <th>Cuisine</th>
                    <th class="text-end">Prix moyen</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($restaurants)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            Aucun restaurant enregistré.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($restaurants as $r): ?>
                        <tr>
                            <td class="fw-semibold"><?= esc($r['nom']) ?></td>
                            <td><?= esc($r['destination_nom'] ?? '—') ?></td>
                            <td><?= esc($r['type_cuisine'] ?? '—') ?></td>
                            <td class="text-end">
                                <?= number_format((float) $r['prix_moyen'], 0, ',', ' ') ?>
                                <?= esc($r['devise'] ?? '') ?>
                            </td>
                            <td>
                                <?php if ($r['actif']): ?>
                                    <span class="badge lc-badge-actif">Actif</span>
                                <?php else: ?>
                                    <span class="badge lc-badge-inactif">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="<?= site_url('restaurants/' . $r['id'] . '/edit') ?>"
                                       class="btn btn-sm btn-light border" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= site_url('restaurants/' . $r['id'] . '/delete') ?>"
                                       class="btn btn-sm btn-light border text-danger"
                                       title="Supprimer"
                                       onclick="return confirm('Supprimer ce restaurant ?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>