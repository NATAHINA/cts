<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1"><?= esc($title) ?></h4>
        <p class="text-muted mb-0">
            Gestion du catalogue touristique.
        </p>
    </div>

    <a href="<?= site_url($module . '/new') ?>"
       class="btn lc-btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Ajouter
    </a>

</div>

<div class="lc-card">

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Devise</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($items)): ?>

                    <?php foreach ($items as $item): ?>

                        <tr>

                            <td class="fw-semibold">
                                <?= esc($item['nom'] ?? '-') ?>
                            </td>

                            <td>
                                <?= esc($item['description'] ?? '-') ?>
                            </td>

                            <td>
                                <?= isset($item['prix_defaut'])
                                    ? number_format($item['prix_defaut'], 0, ',', ' ')
                                    : '-' ?>
                            </td>

                            <td><?= esc($item['devise'] ?? '-') ?></td>

                            <td>
                                <?= esc($item['statut'] ?? 'actif') ?>
                            </td>

                            <td class="text-end">

                                <a href="<?= site_url($module . '/' . $item['id'] . '/edit') ?>"
                                   class="btn btn-sm btn-light border">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="<?= site_url($module . '/' . $item['id'] . '/delete') ?>"
                                   class="btn btn-sm btn-light border text-danger"
                                   onclick="return confirm('Supprimer cet élément ?')">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            Aucun élément enregistré.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?= $this->endSection() ?>