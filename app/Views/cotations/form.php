<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php $isEdit = !empty($cotation); ?>

<form method="post"
      action="<?= $isEdit
        ? site_url('cotations/' . $cotation['id'])
        : site_url('cotations') ?>">

    <?= csrf_field() ?>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <?= $isEdit ? 'Modifier la cotation' : 'Nouvelle cotation' ?>
            </h4>
            <p class="text-muted mb-0">
                Informations générales du voyage.
            </p>
        </div>

        <a href="<?= site_url('cotations') ?>" class="btn btn-light border">
            Annuler
        </a>

    </div>


    <div class="lc-card p-4 mb-3">

        <h6 class="mb-3">Informations générales</h6>

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label">Client *</label>

                <select name="client_id" class="form-select" required>

                    <option value="">Sélectionner un client</option>

                    <?php foreach ($clients ?? [] as $client): ?>

                        <option value="<?= $client['id'] ?>"
                            <?= old(
                                'client_id',
                                $cotation['client_id'] ?? ''
                            ) == $client['id'] ? 'selected' : '' ?>>

                            <?= esc(
                                trim(($client['prenom'] ?? '') . ' ' . $client['nom'])
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="col-md-6">

                <label class="form-label">Destination</label>

                <select name="destination_id" class="form-select">

                    <option value="">Sélectionner</option>

                    <?php foreach ($destinations ?? [] as $destination): ?>

                        <option 
                            value="<?= $destination['id'] ?>"
                            <?= old(
                                'destination_id',
                                $cotation['destination_id'] ?? ''
                            ) == $destination['id'] ? 'selected' : '' ?>
                        >
                            <?= esc($destination['nom']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="col-md-4">

                <label class="form-label">Date de départ</label>

                <input type="date"
                       name="date_depart"
                       class="form-control"
                       value="<?= old('date_depart', $cotation['date_depart'] ?? '') ?>">

            </div>


            <div class="col-md-4">

                <label class="form-label">Date de retour</label>

                <input type="date"
                       name="date_retour"
                       class="form-control"
                       value="<?= old('date_retour', $cotation['date_retour'] ?? '') ?>">

            </div>


            <div class="col-md-4">

                <label class="form-label">Validité de l'offre</label>

                <input type="date"
                       name="date_validite"
                       class="form-control"
                       value="<?= old('date_validite', $cotation['date_validite'] ?? '') ?>">

            </div>


            <div class="col-md-4">

                <label class="form-label">Adultes</label>

                <input type="number"
                       name="nb_adultes"
                       min="1"
                       class="form-control"
                       value="<?= old('nb_adultes', $cotation['nb_adultes'] ?? 1) ?>">

            </div>


            <div class="col-md-4">

                <label class="form-label">Enfants</label>

                <input type="number"
                       name="nb_enfants"
                       min="0"
                       class="form-control"
                       value="<?= old('nb_enfants', $cotation['nb_enfants'] ?? 0) ?>">

            </div>


            <div class="col-md-4">

                <label class="form-label">Bébés</label>

                <input type="number"
                       name="nb_bebes"
                       min="0"
                       class="form-control"
                       value="<?= old('nb_bebes', $cotation['nb_bebes'] ?? 0) ?>">

            </div>


            <div class="col-md-6">

                <label class="form-label">Devise</label>

                <select name="devise" class="form-select">

                    <?php
                    $deviseActuelle = old(
                        'devise',
                        $cotation['devise'] ?? 'EUR'
                    );
                    ?>

                    <option value="MGA"
                        <?= $deviseActuelle === 'MGA' ? 'selected' : '' ?>>
                        MGA - Ariary
                    </option>

                    <option value="EUR"
                        <?= $deviseActuelle === 'EUR' ? 'selected' : '' ?>>
                        EUR - Euro
                    </option>

                    <option value="USD"
                        <?= $deviseActuelle === 'USD' ? 'selected' : '' ?>>
                        USD - Dollar américain
                    </option>

                </select>

            </div>

            <div class="col-md-6">

                <label class="form-label">Statut</label>

                <?php
                $statutActuel = old(
                    'statut',
                    $cotation['statut'] ?? 'brouillon'
                );
                ?>

                <select name="statut" class="form-select">

                    <option value="brouillon"
                        <?= $statutActuel === 'brouillon' ? 'selected' : '' ?>>
                        Brouillon
                    </option>

                    <option value="envoyee"
                        <?= $statutActuel === 'envoyee' ? 'selected' : '' ?>>
                        Envoyée
                    </option>

                    <option value="acceptee"
                        <?= $statutActuel === 'acceptee' ? 'selected' : '' ?>>
                        Acceptée
                    </option>

                    <option value="refusee"
                        <?= $statutActuel === 'refusee' ? 'selected' : '' ?>>
                        Refusée
                    </option>

                    <option value="expiree"
                        <?= $statutActuel === 'expiree' ? 'selected' : '' ?>>
                        Expirée
                    </option>

                </select>

            </div>


            <div class="col-md-4">

                <label class="form-label">Marge (%)</label>

                <input type="number"
                    name="marge_pourcentage"
                    class="form-control"
                    step="0.1"
                    min="0"
                    value="<?= esc($cotation['marge_pourcentage'] ?? 0) ?>">

            </div>


            <div class="col-md-4">

                <label class="form-label">Réduction (%)</label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="reduction_pourcentage"
                    class="form-control"
                    value="<?= old(
                        'reduction_pourcentage',
                        $cotation['reduction_pourcentage'] ?? 0
                    ) ?>"
                >

            </div>


            <div class="col-md-4">

                <label class="form-label">Taxe</label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="taxe_montant"
                    class="form-control"
                    value="<?= old(
                        'taxe_montant',
                        $cotation['taxe_montant'] ?? 0
                    ) ?>"
                >

            </div>

        </div>

    </div>


    <div class="lc-card p-4 mb-3">

        <h6 class="mb-3">Notes pour le client</h6>

        <textarea name="notes_client"
                  rows="4"
                  class="form-control"><?= old('notes_client', $cotation['notes_client'] ?? '') ?></textarea>

    </div>


    <div class="lc-card p-4 mb-3">

        <h6 class="mb-3">Notes internes</h6>

        <textarea name="notes_interne"
                  rows="4"
                  class="form-control"><?= old('notes_interne', $cotation['notes_interne'] ?? '') ?></textarea>

    </div>


    <div class="text-end">

        <button type="submit" class="btn lc-btn-primary px-4">

            <i class="bi bi-check-lg me-1"></i>

            <?= $isEdit
                ? 'Enregistrer les modifications'
                : 'Créer la cotation' ?>

        </button>

    </div>

</form>

<?= $this->endSection() ?>