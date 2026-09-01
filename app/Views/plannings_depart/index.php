<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="mb-1">
            Planning des départs
        </h2>

        <p class="text-muted mb-0">
            Gestion des départs programmés
        </p>
    </div>

    <a href="<?= site_url('plannings-depart/new') ?>"
       class="btn lc-btn-primary">
        <i class="bi bi-plus-lg"></i>
        Nouveau départ
    </a>

</div>

<?php if (session()->getFlashdata('success')): ?>

    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>

<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>

    <div class="alert alert-danger">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>

<?php endif; ?>

<div class="lc-card p-4">

    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th>Destination</th>
                    <th>Départ</th>
                    <th>Retour</th>
                    <th>Capacité</th>
                    <th>Vendues</th>
                    <th>Disponibles</th>
                    <th>Remplissage</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php if (!empty($plannings)): ?>

                <?php foreach ($plannings as $planning): ?>

                    <?php
                    $capacite = (int) $planning['capacite'];
                    $vendues = (int) $planning['places_vendues'];

                    $disponibles = max(
                        0,
                        $capacite - $vendues
                    );

                    $taux = $capacite > 0
                        ? round(($vendues / $capacite) * 100, 1)
                        : 0;

                    switch ($planning['statut']) {
                        case 'ouvert':
                            $badge = 'bg-success';
                            $labelStatut = 'Ouvert';
                            break;

                        case 'complet':
                            $badge = 'bg-danger';
                            $labelStatut = 'Complet';
                            break;

                        case 'termine':
                            $badge = 'bg-secondary';
                            $labelStatut = 'Terminé';
                            break;

                        case 'annule':
                            $badge = 'bg-dark';
                            $labelStatut = 'Annulé';
                            break;

                        default:
                            $badge = 'bg-primary';
                            $labelStatut = 'Planifié';
                    }
                    ?>

                    <tr>

                        <td>
                            <strong>
                                <?= esc($planning['destination_nom']) ?>
                            </strong>
                        </td>

                        <td>
                            <div>
                                <?= date(
                                    'd/m/Y',
                                    strtotime($planning['date_depart'])
                                ) ?>
                            </div>

                            <?php if (!empty($planning['heure_depart'])): ?>
                                <small class="text-muted">
                                    <?= substr($planning['heure_depart'], 0, 5) ?>
                                </small>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (!empty($planning['date_retour'])): ?>

                                <?= date(
                                    'd/m/Y',
                                    strtotime($planning['date_retour'])
                                ) ?>

                                <?php if (!empty($planning['heure_retour'])): ?>
                                    <small class="text-muted d-block">
                                        <?= substr($planning['heure_retour'], 0, 5) ?>
                                    </small>
                                <?php endif; ?>

                            <?php else: ?>

                                <span class="text-muted">—</span>

                            <?php endif; ?>
                        </td>

                        <td>
                            <?= number_format($capacite, 0, ',', ' ') ?>
                        </td>

                        <td>
                            <?= number_format($vendues, 0, ',', ' ') ?>
                        </td>

                        <td>
                            <strong>
                                <?= number_format($disponibles, 0, ',', ' ') ?>
                            </strong>
                        </td>

                        <td style="min-width: 130px;">

                            <div class="progress" style="height: 7px;">

                                <div class="progress-bar"
                                     role="progressbar"
                                     style="width: <?= min(100, $taux) ?>%">
                                </div>

                            </div>

                            <small class="text-muted">
                                <?= $taux ?> %
                            </small>

                        </td>

                        <td>
                            <?php if ($planning['prix'] !== null): ?>

                                <?= number_format(
                                    $planning['prix'],
                                    0,
                                    ',',
                                    ' '
                                ) ?>

                                <?= esc($planning['devise']) ?>

                            <?php else: ?>

                                <span class="text-muted">—</span>

                            <?php endif; ?>
                        </td>

                        <td>
                            <span class="badge <?= $badge ?>">
                                <?= $labelStatut ?>
                            </span>
                        </td>

                        <td class="text-end">

                            <div class="btn-group">

                                <a href="<?= site_url(
                                    'plannings-depart/' . $planning['id']
                                ) ?>"
                                   class="btn btn-sm btn-outline-secondary"
                                   title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="<?= site_url(
                                    'plannings-depart/' .
                                    $planning['id'] .
                                    '/edit'
                                ) ?>"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form method="post"
                                      action="<?= site_url(
                                          'plannings-depart/' .
                                          $planning['id'] .
                                          '/delete'
                                      ) ?>"
                                      onsubmit="return confirm('Voulez-vous vraiment supprimer ce départ ?');">

                                    <?= csrf_field() ?>

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="10"
                        class="text-center text-muted py-5">

                        <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>

                        Aucun départ programmé.

                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?= $this->endSection() ?>