<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">

        <h3>Détails de l'assurance</h3>

        <div>
            <a href="<?= site_url('assurances') ?>"
               class="btn btn-secondary">
                Retour
            </a>

            <a href="<?= site_url('assurances/' . $assurance['id'] . '/edit') ?>"
               class="btn btn-warning">
                Modifier
            </a>
        </div>

    </div>

    <div class="card">
        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Nom :</strong><br>
                    <?= esc($assurance['nom']) ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Téléphone :</strong><br>
                    <?= esc($assurance['telephone'] ?? '-') ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Email :</strong><br>
                    <?= esc($assurance['email'] ?? '-') ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Site web :</strong><br>

                    <?php if (! empty($assurance['site_web'])) : ?>
                        <a href="<?= esc($assurance['site_web']) ?>"
                           target="_blank">
                            <?= esc($assurance['site_web']) ?>
                        </a>
                    <?php else : ?>
                        -
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Adresse :</strong><br>
                    <?= esc($assurance['adresse'] ?? '-') ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Statut :</strong><br>

                    <?php if ($assurance['statut'] === 'actif') : ?>
                        <span class="badge bg-success">Actif</span>
                    <?php else : ?>
                        <span class="badge bg-secondary">Inactif</span>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <strong>Description :</strong><br>
                    <?= nl2br(esc($assurance['description'] ?? '-')) ?>
                </div>

            </div>

        </div>
    </div>

</div>

<?= $this->endSection() ?>