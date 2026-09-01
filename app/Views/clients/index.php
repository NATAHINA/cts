<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">Clients</h4>
        <p class="text-muted mb-0">
            Gérez votre portefeuille clients.
        </p>
    </div>

    <a href="<?= site_url('clients/new') ?>" class="btn lc-btn-primary">
        <i class="bi bi-person-plus me-1"></i>
        Nouveau client
    </a>

</div>

<div class="lc-card">

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead>
                <tr>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Pays</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($clients as $client): ?>

                    <tr>

                        <td>
                            <a href="<?= site_url('clients/' . $client['id']) ?>"
                               class="fw-semibold">
                                <?= esc(trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''))) ?>
                            </a>
                        </td>

                        <td><?= esc($client['type_client'] ?? 'Particulier') ?></td>
                        <td><?= esc($client['telephone'] ?? '-') ?></td>
                        <td><?= esc($client['email'] ?? '-') ?></td>
                        <td><?= esc($client['pays'] ?? '-') ?></td>

                        <td>
                            <span class="badge <?= ($client['statut'] ?? '') === 'actif'
                                ? 'lc-badge-actif'
                                : 'lc-badge-inactif' ?>">
                                <?= esc($client['statut'] ?? 'actif') ?>
                            </span>
                        </td>

                        <td class="text-end">

                            <a href="<?= site_url('clients/' . $client['id'] . '/edit') ?>"
                               class="btn btn-sm btn-light border">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="<?= site_url('clients/' . $client['id'] . '/delete') ?>"
                               class="btn btn-sm btn-light border text-danger"
                               onclick="return confirm('Supprimer ce client ?')">
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