<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2>
            <?= esc($planning['destination_nom']) ?>
        </h2>

        <p class="text-muted mb-0">
            Détail du planning de départ
        </p>
    </div>

    <div>

        <a href="<?= site_url('plannings-depart') ?>"
           class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

        <a href="<?= site_url(
            'plannings-depart/' . $planning['id'] . '/edit'
        ) ?>"
           class="btn btn-primary">
            <i class="bi bi-pencil"></i>
            Modifier
        </a>

    </div>

</div>

<div class="row g-4">

    <div class="col-md-8">

        <div class="lc-card p-4">

            <h5 class="mb-4">
                Informations du départ
            </h5>

            <div class="row g-4">

                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Destination
                    </small>

                    <strong>
                        <?= esc($planning['destination_nom']) ?>
                    </strong>

                </div>

                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Date de départ
                    </small>

                    <strong>
                        <?= date(
                            'd/m/Y',
                            strtotime($planning['date_depart'])
                        ) ?>

                        <?php if (!empty($planning['heure_depart'])): ?>

                            à
                            <?= substr(
                                $planning['heure_depart'],
                                0,
                                5
                            ) ?>

                        <?php endif; ?>

                    </strong>

                </div>

                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Date de retour
                    </small>

                    <strong>

                        <?php if (!empty($planning['date_retour'])): ?>

                            <?= date(
                                'd/m/Y',
                                strtotime($planning['date_retour'])
                            ) ?>

                            <?php if (!empty($planning['heure_retour'])): ?>

                                à
                                <?= substr(
                                    $planning['heure_retour'],
                                    0,
                                    5
                                ) ?>

                            <?php endif; ?>

                        <?php else: ?>

                            —

                        <?php endif; ?>

                    </strong>

                </div>

                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Statut
                    </small>

                    <?php
                    $labels = [
                        'planifie' => 'Planifié',
                        'ouvert'   => 'Ouvert',
                        'complet'  => 'Complet',
                        'termine'  => 'Terminé',
                        'annule'   => 'Annulé',
                    ];
                    ?>

                    <span class="badge bg-primary">
                        <?= esc(
                            $labels[$planning['statut']]
                            ?? $planning['statut']
                        ) ?>
                    </span>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="lc-card p-4">

            <h5 class="mb-4">
                Places
            </h5>

            <div class="mb-3">

                <div class="d-flex justify-content-between">

                    <span>
                        Capacité
                    </span>

                    <strong>
                        <?= number_format(
                            $planning['capacite'],
                            0,
                            ',',
                            ' '
                        ) ?>
                    </strong>

                </div>

            </div>

            <div class="mb-3">

                <div class="d-flex justify-content-between">

                    <span>
                        Places vendues
                    </span>

                    <strong>
                        <?= number_format(
                            $planning['places_vendues'],
                            0,
                            ',',
                            ' '
                        ) ?>
                    </strong>

                </div>

            </div>

            <div class="mb-3">

                <div class="d-flex justify-content-between">

                    <span>
                        Places disponibles
                    </span>

                    <strong>
                        <?= number_format(
                            $planning['places_disponibles'],
                            0,
                            ',',
                            ' '
                        ) ?>
                    </strong>

                </div>

            </div>

            <hr>

            <div class="text-center">

                <div class="display-6 fw-bold">
                    <?= $planning['taux_remplissage'] ?> %
                </div>

                <small class="text-muted">
                    Taux de remplissage
                </small>

            </div>

        </div>

    </div>

</div>

<?php if (!empty($planning['notes'])): ?>

    <div class="lc-card p-4 mt-4">

        <h5>
            Notes
        </h5>

        <p class="mb-0">
            <?= nl2br(esc($planning['notes'])) ?>
        </p>

    </div>

<?php endif; ?>

<?= $this->endSection() ?>