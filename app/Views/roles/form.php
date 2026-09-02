<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$isEdit = ! empty($role);
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                <?= $isEdit ? 'Modifier le rôle' : 'Nouveau rôle' ?>
            </h1>

            <p class="text-muted mb-0">
                <?= $isEdit
                    ? 'Modifiez les informations du rôle.'
                    : 'Créez un nouveau rôle pour votre agence.'
                ?>
            </p>
        </div>

        <a href="<?= site_url('roles') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Retour
        </a>

    </div>


    <?php if (session()->getFlashdata('error')): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <?= esc(session()->getFlashdata('error')) ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>


    <?php if (session()->getFlashdata('success')): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <?= esc(session()->getFlashdata('success')) ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>


    <?php if (isset($validation)): ?>

        <div class="alert alert-danger">

            <?= $validation->listErrors() ?>

        </div>

    <?php endif; ?>


    <div class="row">

        <div class="col-lg-8 col-xl-7">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        Informations du rôle
                    </h5>

                </div>


                <div class="card-body">

                    <form
                        method="post"
                        action="<?= $isEdit
                            ? site_url('roles/' . $role['id'])
                            : site_url('roles')
                        ?>"
                    >

                        <?= csrf_field() ?>


                        <div class="mb-3">

                            <label for="libelle" class="form-label">
                                Libellé
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="libelle"
                                id="libelle"
                                class="form-control"
                                value="<?= old(
                                    'libelle',
                                    $role['libelle'] ?? ''
                                ) ?>"
                                placeholder="Exemple : Commercial"
                                required
                            >

                            <div class="form-text">
                                Nom affiché du rôle dans l'application.
                            </div>

                        </div>


                        <div class="mb-3">

                            <label for="code" class="form-label">
                                Code
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="code"
                                id="code"
                                class="form-control"
                                value="<?= old(
                                    'code',
                                    $role['code'] ?? ''
                                ) ?>"
                                placeholder="Exemple : commercial"
                                required
                                <?= $isEdit ? 'readonly' : '' ?>
                            >

                            <div class="form-text">
                                Identifiant technique unique du rôle.
                                Utilisez uniquement des lettres minuscules,
                                chiffres, tirets ou underscores.
                            </div>

                        </div>


                        <div class="mb-4">

                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                class="form-control"
                                rows="5"
                                placeholder="Décrivez les responsabilités de ce rôle..."
                            ><?= old(
                                'description',
                                $role['description'] ?? ''
                            ) ?></textarea>

                        </div>


                        <div class="d-flex justify-content-between">

                            <a
                                href="<?= site_url('roles') ?>"
                                class="btn btn-outline-secondary"
                            >
                                Annuler
                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-check-lg me-1"></i>

                                <?= $isEdit
                                    ? 'Enregistrer les modifications'
                                    : 'Créer le rôle'
                                ?>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        <div class="col-lg-4 col-xl-5 mt-4 mt-lg-0">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Gestion des permissions
                    </h5>

                </div>


                <div class="card-body">

                    <p class="text-muted">

                        Après avoir créé le rôle, vous pourrez lui attribuer
                        les permissions disponibles dans l'application.

                    </p>


                    <div class="alert alert-info mb-0">

                        <div class="d-flex">

                            <i class="bi bi-shield-check fs-4 me-3"></i>

                            <div>

                                <strong>Permissions</strong>

                                <p class="mb-0 mt-1 small">

                                    Les permissions déterminent les actions
                                    que les utilisateurs possédant ce rôle
                                    peuvent effectuer.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <?php if ($isEdit): ?>

                <div class="card border-0 shadow-sm mt-3">

                    <div class="card-body">

                        <a
                            href="<?= site_url(
                                'roles/' . $role['id'] . '/permissions'
                            ) ?>"
                            class="btn btn-outline-primary w-100"
                        >

                            <i class="bi bi-shield-check me-2"></i>

                            Gérer les permissions

                        </a>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?= $this->endSection() ?>