<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">Fournisseurs</h4>
        <p class="text-muted mb-0">
            Hôtels, compagnies aériennes, transporteurs et autres partenaires.
        </p>
    </div>

    <a href="<?= site_url('fournisseurs/new') ?>" class="btn lc-btn-primary">
        <i class="bi bi-plus-lg"></i>
        Ajouter
    </a>

</div>

<div class="lc-card">

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead>
                <tr>
                    <th>Fournisseur</th>
                    <th>Type</th>
                    <th>Contact</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Commission</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($fournisseurs as $fournisseur): ?>

                <tr>

                    <td class="fw-semibold">
                        <?= esc($fournisseur['nom']) ?>
                    </td>

                    <td><?= esc($fournisseur['type']) ?></td>
                    <td><?= esc($fournisseur['contact_nom']) ?></td>
                    <td><?= esc($fournisseur['telephone']) ?></td>
                    <td><?= esc($fournisseur['email']) ?></td>

                    <td>
                        <?= esc($fournisseur['commission_pourcentage']) ?> %
                    </td>

                    <td>
                        <?= esc($fournisseur['statut']) ?>
                    </td>

                    <td class="text-end">

                        <a href="<?= site_url('fournisseurs/' . $fournisseur['id'] . '/edit') ?>"
                           class="btn btn-sm btn-light border">
                            <i class="bi bi-pencil"></i>
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?= $this->endSection() ?>