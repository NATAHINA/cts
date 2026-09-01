<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="lc-card p-4" style="max-width: 1000px;">

    <?php if (session('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form
        method="post"
        action="<?= $item
            ? site_url('forfaits/' . $item['id'])
            : site_url('forfaits') ?>"
    >

        <?= csrf_field() ?>

        <div class="row">

            <!-- CODE -->
            <div class="col-md-4 mb-3" style="display: <?= $item ? 'block' : 'none' ?>">
                <label class="form-label">
                    Code du forfait
                </label>

                <input
                    type="text"
                    class="form-control"
                    name="code"
                    value="<?= esc(old('code', $item['code'] ?? '')) ?>"
                    readonly="<?= $item ? 'readonly' : '' ?>"
                >
            </div>


            <!-- NOM -->
            <div class="col-md-8 mb-3">
                <label class="form-label">
                    Nom du forfait <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    class="form-control"
                    name="nom"
                    required
                    value="<?= esc(old(
                        'nom',
                        $item['nom'] ?? ''
                    )) ?>"
                >
            </div>


            <!-- DESTINATION -->
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Destination
                </label>

                <select
                    class="form-select"
                    name="destination_id"
                >
                    <option value="">
                        — Sélectionner une destination —
                    </option>

                    <?php foreach ($destinations as $d): ?>
                        <option
                            value="<?= $d['id'] ?>"
                            <?= (
                                (int) old(
                                    'destination_id',
                                    $item['destination_id'] ?? 0
                                )
                                === (int) $d['id']
                            )
                                ? 'selected'
                                : ''
                            ?>
                        >
                            <?= esc($d['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- FOURNISSEUR -->
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Fournisseur
                </label>

                <select
                    class="form-select"
                    name="fournisseur_id"
                >
                    <option value="">
                        — Aucun fournisseur —
                    </option>

                    <?php foreach ($fournisseurs as $f): ?>
                        <option
                            value="<?= $f['id'] ?>"
                            <?= (
                                (int) old(
                                    'fournisseur_id',
                                    $item['fournisseur_id'] ?? 0
                                )
                                === (int) $f['id']
                            )
                                ? 'selected'
                                : ''
                            ?>
                        >
                            <?= esc($f['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- DESCRIPTION -->
            <div class="col-12 mb-3">
                <label class="form-label">
                    Description
                </label>

                <textarea
                    class="form-control"
                    name="description"
                    rows="4"
                ><?= esc(old(
                    'description',
                    $item['description'] ?? ''
                )) ?></textarea>
            </div>


            <!-- DUREE JOURS -->
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Durée (jours)
                </label>

                <input
                    type="number"
                    min="0"
                    class="form-control"
                    name="duree_jours"
                    value="<?= esc(old(
                        'duree_jours',
                        $item['duree_jours'] ?? ''
                    )) ?>"
                >
            </div>


            <!-- DUREE NUITS -->
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Durée (nuits)
                </label>

                <input
                    type="number"
                    min="0"
                    class="form-control"
                    name="duree_nuits"
                    value="<?= esc(old(
                        'duree_nuits',
                        $item['duree_nuits'] ?? ''
                    )) ?>"
                >
            </div>

        </div>


        <hr class="my-4">

        <h6 class="mb-3">
            <i class="bi bi-cash-stack me-2"></i>
            Tarification
        </h6>


        <div class="row">

            <!-- PRIX PRINCIPAL -->
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Prix principal
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    class="form-control"
                    name="prix"
                    value="<?= esc(old(
                        'prix',
                        $item['prix'] ?? ''
                    )) ?>"
                >
            </div>


            <!-- DEVISE -->
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Devise
                </label>

                <select
                    class="form-select"
                    name="devise_id"
                >
                    <option value="">
                        — Sélectionner une devise —
                    </option>

                    <?php foreach ($devises as $d): ?>
                        <option
                            value="<?= $d['id'] ?>"
                            <?= (
                                (int) old(
                                    'devise_id',
                                    $item['devise_id'] ?? 0
                                )
                                === (int) $d['id']
                            )
                                ? 'selected'
                                : ''
                            ?>
                        >
                            <?= esc($d['code']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- PRIX ADULTE -->
            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Prix adulte
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    class="form-control"
                    name="prix_adulte"
                    value="<?= esc(old(
                        'prix_adulte',
                        $item['prix_adulte'] ?? ''
                    )) ?>"
                >
            </div>


            <!-- PRIX ENFANT -->
            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Prix enfant
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    class="form-control"
                    name="prix_enfant"
                    value="<?= esc(old(
                        'prix_enfant',
                        $item['prix_enfant'] ?? ''
                    )) ?>"
                >
            </div>


            <!-- PRIX GROUPE -->
            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Prix groupe
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    class="form-control"
                    name="prix_groupe"
                    value="<?= esc(old(
                        'prix_groupe',
                        $item['prix_groupe'] ?? ''
                    )) ?>"
                >
            </div>

        </div>


        <hr class="my-4">

        <h6 class="mb-3">
            <i class="bi bi-briefcase me-2"></i>
            Gestion commerciale
        </h6>


        <div class="row">

            <!-- COMMISSION -->
            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Commission (%)
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    max="100"
                    class="form-control"
                    name="commission_pourcentage"
                    value="<?= esc(old(
                        'commission_pourcentage',
                        $item['commission_pourcentage'] ?? '0'
                    )) ?>"
                >
            </div>


            <!-- DISPONIBILITE -->
            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Disponibilité
                </label>

                <?php
                $disponibilite = old(
                    'disponibilite',
                    $item['disponibilite'] ?? 'disponible'
                );
                ?>

                <select
                    class="form-select"
                    name="disponibilite"
                >
                    <option
                        value="disponible"
                        <?= $disponibilite === 'disponible'
                            ? 'selected'
                            : ''
                        ?>
                    >
                        Disponible
                    </option>

                    <option
                        value="indisponible"
                        <?= $disponibilite === 'indisponible'
                            ? 'selected'
                            : ''
                        ?>
                    >
                        Indisponible
                    </option>
                </select>
            </div>


            <!-- STATUT -->
            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Statut
                </label>

                <?php
                $statut = old(
                    'statut',
                    $item['statut'] ?? 'actif'
                );
                ?>

                <select
                    class="form-select"
                    name="statut"
                >
                    <option
                        value="actif"
                        <?= $statut === 'actif'
                            ? 'selected'
                            : ''
                        ?>
                    >
                        Actif
                    </option>

                    <option
                        value="inactif"
                        <?= $statut === 'inactif'
                            ? 'selected'
                            : ''
                        ?>
                    >
                        Inactif
                    </option>
                </select>
            </div>

        </div>


        <div class="d-flex gap-2 mt-3">

            <button
                type="submit"
                class="btn lc-btn-primary px-4"
            >
                <i class="bi bi-check-lg me-1"></i>

                <?= $item
                    ? 'Enregistrer les modifications'
                    : 'Enregistrer'
                ?>
            </button>

            <a
                href="<?= site_url('forfaits') ?>"
                class="btn btn-light border"
            >
                Annuler
            </a>

        </div>

    </form>

</div>

<?= $this->endSection() ?>