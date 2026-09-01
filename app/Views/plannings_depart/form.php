<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$isEdit = !empty($planning);

$statut = old(
    'statut',
    $planning['statut'] ?? 'planifie'
);

$devise = old(
    'devise',
    $planning['devise'] ?? 'MGA'
);
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2>
            <?= $isEdit ? 'Modifier le départ' : 'Nouveau départ' ?>
        </h2>

        <p class="text-muted mb-0">
            <?= $isEdit
                ? 'Modifier les informations du départ'
                : 'Planifier un nouveau départ' ?>
        </p>
    </div>

    <a href="<?= site_url('plannings-depart') ?>"
       class="btn btn-light border">

        <i class="bi bi-arrow-left"></i>
        Retour

    </a>

</div>


<!-- Messages -->

<?php if (session()->getFlashdata('error')): ?>

    <div class="alert alert-danger">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>

<?php endif; ?>


<?php if (session()->getFlashdata('success')): ?>

    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>

<?php endif; ?>


<?php if (session()->getFlashdata('errors')): ?>

    <div class="alert alert-danger">

        <ul class="mb-0">

            <?php foreach (
                session()->getFlashdata('errors')
                as $error
            ): ?>

                <li>
                    <?= esc($error) ?>
                </li>

            <?php endforeach; ?>

        </ul>

    </div>

<?php endif; ?>


<form method="post"
      action="<?= $isEdit
          ? site_url(
              'plannings-depart/' .
              $planning['id']
          )
          : site_url('plannings-depart') ?>">

    <?= csrf_field() ?>


    <!-- =====================================================
         DESTINATION ET DATES
    ====================================================== -->

    <div class="lc-card p-4">

        <h5 class="mb-4">

            <i class="bi bi-geo-alt"></i>

            Destination et dates

        </h5>


        <div class="row g-3">


            <!-- DESTINATION -->

            <div class="col-md-6">

                <label
                    for="destination_id"
                    class="form-label"
                >

                    Destination
                    <span class="text-danger">*</span>

                </label>


                <select
                    name="destination_id"
                    id="destination_id"
                    class="form-select"
                    required
                >

                    <option value="">
                        Sélectionner une destination
                    </option>


                    <?php foreach (
                        $destinations
                        as $destination
                    ): ?>

                        <?php

                        $selected = old(
                            'destination_id',
                            $planning['destination_id'] ?? ''
                        ) == $destination['id'];

                        ?>


                        <option
                            value="<?= esc(
                                $destination['id']
                            ) ?>"
                            <?= $selected
                                ? 'selected'
                                : '' ?>
                        >

                            <?= esc(
                                $destination['nom']
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- DATE DEPART -->

            <div class="col-md-3">

                <label
                    for="date_depart"
                    class="form-label"
                >

                    Date de départ
                    <span class="text-danger">*</span>

                </label>


                <input
                    type="date"
                    name="date_depart"
                    id="date_depart"
                    class="form-control"
                    required
                    value="<?= old(
                        'date_depart',
                        $planning['date_depart'] ?? ''
                    ) ?>"
                >

            </div>


            <!-- HEURE DEPART -->

            <div class="col-md-3">

                <label
                    for="heure_depart"
                    class="form-label"
                >

                    Heure de départ

                </label>


                <input
                    type="time"
                    name="heure_depart"
                    id="heure_depart"
                    class="form-control"
                    value="<?= old(
                        'heure_depart',
                        $planning['heure_depart'] ?? ''
                    ) ?>"
                >

            </div>


            <!-- DATE RETOUR -->

            <div class="col-md-3">

                <label
                    for="date_retour"
                    class="form-label"
                >

                    Date de retour

                </label>


                <input
                    type="date"
                    name="date_retour"
                    id="date_retour"
                    class="form-control"
                    value="<?= old(
                        'date_retour',
                        $planning['date_retour'] ?? ''
                    ) ?>"
                >

            </div>


            <!-- HEURE RETOUR -->

            <div class="col-md-3">

                <label
                    for="heure_retour"
                    class="form-label"
                >

                    Heure de retour

                </label>


                <input
                    type="time"
                    name="heure_retour"
                    id="heure_retour"
                    class="form-control"
                    value="<?= old(
                        'heure_retour',
                        $planning['heure_retour'] ?? ''
                    ) ?>"
                >

            </div>


        </div>

    </div>


    <!-- =====================================================
         CAPACITE
    ====================================================== -->

    <div class="lc-card p-4 mt-4">

        <h5 class="mb-4">

            <i class="bi bi-people"></i>

            Capacité

        </h5>


        <div class="row g-3">


            <!-- CAPACITE -->

            <div class="col-md-6">

                <label
                    for="capacite"
                    class="form-label"
                >

                    Capacité
                    <span class="text-danger">*</span>

                </label>


                <input
                    type="number"
                    name="capacite"
                    id="capacite"
                    min="1"
                    class="form-control"
                    required
                    value="<?= old(
                        'capacite',
                        $planning['capacite'] ?? 20
                    ) ?>"
                >


                <div class="form-text">

                    Nombre maximum de voyageurs
                    pour ce départ.

                </div>

            </div>


            <!-- STATUT -->

            <div class="col-md-6">

                <label
                    for="statut"
                    class="form-label"
                >

                    Statut

                </label>


                <select
                    name="statut"
                    id="statut"
                    class="form-select"
                >

                    <option
                        value="planifie"
                        <?= $statut === 'planifie'
                            ? 'selected'
                            : '' ?>
                    >

                        Planifié

                    </option>


                    <option
                        value="ouvert"
                        <?= $statut === 'ouvert'
                            ? 'selected'
                            : '' ?>
                    >

                        Ouvert aux réservations

                    </option>


                    <option
                        value="complet"
                        <?= $statut === 'complet'
                            ? 'selected'
                            : '' ?>
                    >

                        Complet

                    </option>


                    <option
                        value="termine"
                        <?= $statut === 'termine'
                            ? 'selected'
                            : '' ?>
                    >

                        Terminé

                    </option>


                    <option
                        value="annule"
                        <?= $statut === 'annule'
                            ? 'selected'
                            : '' ?>
                    >

                        Annulé

                    </option>

                </select>

            </div>


        </div>


        <?php if ($isEdit): ?>

            <?php

            $capacite =
                (int) (
                    $planning['capacite']
                    ?? 0
                );

            $placesReservees =
                (int) (
                    $planning['places_reservees']
                    ?? 0
                );

            $placesDisponibles =
                max(
                    0,
                    $capacite -
                    $placesReservees
                );

            ?>


            <div class="row mt-4 g-3">


                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Capacité
                        </div>

                        <div class="fs-4 fw-bold">

                            <?= $capacite ?>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Places réservées
                        </div>

                        <div class="fs-4 fw-bold">

                            <?= $placesReservees ?>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Places disponibles
                        </div>

                        <div class="fs-4 fw-bold">

                            <?= $placesDisponibles ?>

                        </div>

                    </div>

                </div>


            </div>

        <?php endif; ?>


    </div>


    <!-- =====================================================
         TARIFICATION
    ====================================================== -->

    <div class="lc-card p-4 mt-4">

        <h5 class="mb-4">

            <i class="bi bi-cash-stack"></i>

            Tarification

        </h5>


        <div class="row g-3">


            <!-- PRIX -->

            <div class="col-md-6">

                <label
                    for="prix"
                    class="form-label"
                >

                    Prix par personne

                </label>


                <input
                    type="number"
                    name="prix"
                    id="prix"
                    step="0.01"
                    min="0"
                    class="form-control"
                    value="<?= old(
                        'prix',
                        $planning['prix'] ?? ''
                    ) ?>"
                >

            </div>


            <!-- DEVISE -->

            <div class="col-md-6">

                <label
                    for="devise"
                    class="form-label"
                >

                    Devise

                </label>


                <select
                    name="devise"
                    id="devise"
                    class="form-select"
                >

                    <option
                        value="MGA"
                        <?= $devise === 'MGA'
                            ? 'selected'
                            : '' ?>
                    >

                        MGA - Ariary

                    </option>


                    <option
                        value="EUR"
                        <?= $devise === 'EUR'
                            ? 'selected'
                            : '' ?>
                    >

                        EUR - Euro

                    </option>


                    <option
                        value="USD"
                        <?= $devise === 'USD'
                            ? 'selected'
                            : '' ?>
                    >

                        USD - Dollar

                    </option>

                </select>

            </div>


        </div>

    </div>


    <!-- =====================================================
         NOTES
    ====================================================== -->

    <div class="lc-card p-4 mt-4">

        <label
            for="notes"
            class="form-label"
        >

            Notes

        </label>


        <textarea
            name="notes"
            id="notes"
            rows="4"
            class="form-control"
        ><?= old(
            'notes',
            $planning['notes'] ?? ''
        ) ?></textarea>

    </div>


    <!-- =====================================================
         BOUTONS
    ====================================================== -->

    <div class="text-end mt-4">

        <a
            href="<?= site_url('plannings-depart') ?>"
            class="btn btn-light border me-2"
        >

            Annuler

        </a>


        <button
            type="submit"
            class="btn lc-btn-primary"
        >

            <i class="bi bi-check-lg"></i>

            <?= $isEdit
                ? 'Enregistrer les modifications'
                : 'Créer le départ' ?>

        </button>

    </div>

</form>

<?= $this->endSection() ?>