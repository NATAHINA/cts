<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="mb-4">
        <h3><?= esc($title) ?></h3>

        <a href="<?= site_url('assurances') ?>"
           class="btn btn-secondary">
            ← Retour à la liste
        </a>
    </div>

    <?php if (session()->getFlashdata('errors')) : ?>

        <div class="alert alert-danger">
            <ul class="mb-0">

                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>

            </ul>
        </div>

    <?php endif; ?>

    <div class="card">
        <div class="card-body">

            <form action="<?= site_url('assurances') ?>"
                  method="post">

                <?= csrf_field() ?>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Nom de l'assurance *
                        </label>

                        <input type="text"
                               name="nom"
                               class="form-control"
                               value="<?= old('nom') ?>"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Téléphone
                        </label>

                        <input type="text"
                               name="telephone"
                               class="form-control"
                               value="<?= old('telephone') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="<?= old('email') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Site web
                        </label>

                        <input type="url"
                               name="site_web"
                               class="form-control"
                               value="<?= old('site_web') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Adresse
                        </label>

                        <input type="text"
                               name="adresse"
                               class="form-control"
                               value="<?= old('adresse') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Statut *
                        </label>

                        <select name="statut"
                                class="form-select"
                                required>

                            <option value="actif"
                                <?= old('statut', 'actif') === 'actif' ? 'selected' : '' ?>>
                                Actif
                            </option>

                            <option value="inactif"
                                <?= old('statut') === 'inactif' ? 'selected' : '' ?>>
                                Inactif
                            </option>

                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control"><?= old('description') ?></textarea>
                    </div>

                </div>

                <input type="hidden"
                       name="tenant_id"
                       value="<?= old('tenant_id', 1) ?>">

                <button type="submit"
                        class="btn btn-primary">
                    Enregistrer
                </button>

                <a href="<?= site_url('assurances') ?>"
                   class="btn btn-light">
                    Annuler
                </a>

            </form>

        </div>
    </div>

</div>

<?= $this->endSection() ?>