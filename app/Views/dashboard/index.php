<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>

.dashboard-welcome {
    border: 1px solid #e9e9e9;
    border-radius: 18px;
    padding: 28px;
    margin-bottom: 24px;
}

.stat-card {
    border: 0;
    border-radius: 18px;
    height: 100%;
    transition: all .2s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
}

.stat-value {
    font-size: 26px;
    font-weight: 700;
    line-height: 1.2;
}

.dashboard-card {
    border: 0;
    border-radius: 18px;
    overflow: hidden;
}

.dashboard-card .card-header {
    background: transparent;
    padding: 20px 20px 10px;
}

.quick-action {
    text-decoration: none;
    color: inherit;
    border: 1px solid #e9ecef;
    border-radius: 14px;
    padding: 16px;
    display: block;
    height: 100%;
    transition: .2s;
}

.quick-action:hover {
    transform: translateY(-2px);
    background: #f8f9fa;
    color: inherit;
}

.quick-action i {
    font-size: 22px;
}

</style>


<!-- ============================================================
     EN-TÊTE
============================================================ -->

<div class="card shadow-sm stat-card mb-3">

    <div class=" card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <div class="small text-uppercase mb-2">
                Tableau de bord
            </div>

            <h3 class="mb-1">
                Bonjour, <?= esc(session('user_nom') ?: 'Utilisateur') ?> 👋
            </h3>

            <div class="text-muted">
                Voici un aperçu de votre activité aujourd'hui.
            </div>
        </div>

        <div>
            <a href="<?= site_url('clients/new') ?>" class="btn lc-btn-primary">
                <i class="bi bi-person-plus"></i>
                Ajouter un client
            </a>

        </div>


    </div>

</div>


<!-- ============================================================
     INDICATEURS PRINCIPAUX
============================================================ -->

<div class="row g-3 mb-4">

    <!-- Cotations -->

    <div class="col-md-6 col-xl-3">

        <div class="card shadow-sm stat-card">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted small">
                            Cotations
                        </div>

                        <div class="stat-value mt-2">
                            <?= number_format($nbCotations) ?>
                        </div>

                        <div class="small text-success mt-2">
                            <i class="bi bi-arrow-up"></i>
                            <?= $nbCotationsMois ?> ce mois
                        </div>

                    </div>

                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Acceptées -->

    <div class="col-md-6 col-xl-3">

        <div class="card shadow-sm stat-card">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted small">
                            Cotations acceptées
                        </div>

                        <div class="stat-value mt-2">
                            <?= number_format($nbCotationsAcceptees) ?>
                        </div>

                        <div class="small text-muted mt-2">
                            <?= number_format($montantAccepte, 0, ',', ' ') ?>
                            Ar
                        </div>

                    </div>

                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Réservations -->

    <div class="col-md-6 col-xl-3">

        <div class="card shadow-sm stat-card">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted small">
                            Réservations
                        </div>

                        <div class="stat-value mt-2">
                            <?= number_format($nbReservations) ?>
                        </div>

                        <div class="small text-muted mt-2">
                            <?= $nbReservationsConfirmees ?>
                            confirmée(s)
                        </div>

                    </div>

                    <div class="stat-icon bg-info-subtle text-info">
                        <i class="bi bi-calendar-check"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- CA -->

    <div class="col-md-6 col-xl-3">

        <div class="card shadow-sm stat-card">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted small">
                            Chiffre d'affaires
                        </div>

                        <div class="stat-value mt-2">
                            <?= number_format($caMois, 0, ',', ' ') ?>
                            <small>Ar</small>
                        </div>

                        <div class="small text-muted mt-2">
                            Total :
                            <?= number_format($chiffreAffaires, 0, ',', ' ') ?>
                            Ar
                        </div>

                    </div>

                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ============================================================
     DEUXIÈME LIGNE KPI
============================================================ -->

<div class="row g-3 mb-4">

    <div class="col-md-6 col-xl-3">

        <div class="card dashboard-card shadow-sm">

            <div class="card-body">

                <div class="text-muted small">
                    Clients
                </div>

                <h3 class="mt-2 mb-1">
                    <?= number_format($nbClients) ?>
                </h3>

                <small class="text-success">
                    +<?= $nbNouveauxClientsMois ?> nouveau(x) ce mois
                </small>

            </div>

        </div>

    </div>


    <div class="col-md-6 col-xl-3">

        <div class="card dashboard-card shadow-sm">

            <div class="card-body">

                <div class="text-muted small">
                    Taux de conversion
                </div>

                <h3 class="mt-2 mb-1">
                    <?= number_format($tauxConversion, 1, ',', ' ') ?> %
                </h3>

                <small class="text-muted">
                    Cotation → Réservation
                </small>

            </div>

        </div>

    </div>


    <div class="col-md-6 col-xl-3">

        <div class="card dashboard-card shadow-sm">

            <div class="card-body">

                <div class="text-muted small">
                    Encaissé ce mois
                </div>

                <h3 class="mt-2 mb-1">
                    <?= number_format($montantEncaisseMois, 0, ',', ' ') ?>
                    Ar
                </h3>

                <small class="text-muted">
                    Total encaissé :
                    <?= number_format($montantEncaisse, 0, ',', ' ') ?> Ar
                </small>

            </div>

        </div>

    </div>


    <div class="col-md-6 col-xl-3">

        <div class="card dashboard-card shadow-sm">

            <div class="card-body">

                <div class="text-muted small">
                    À encaisser
                </div>

                <h3 class="mt-2 mb-1 text-danger">
                    <?= number_format($montantImpayes, 0, ',', ' ') ?>
                    Ar
                </h3>

                <small class="text-muted">
                    <?= $facturesImpayees ?> facture(s) impayée(s)
                </small>

            </div>

        </div>

    </div>

</div>


<!-- ============================================================
     GRAPHIQUES
============================================================ -->

<div class="row g-3 mb-4">

    <div class="col-lg-8">

        <div class="card dashboard-card shadow-sm h-100">

            <div class="card-header">

                <h6 class="mb-1">
                    Activité des 6 derniers mois
                </h6>

                <small class="text-muted">
                    Évolution des cotations et réservations
                </small>

            </div>

            <div class="card-body">

                <canvas id="activityChart"
                        style="max-height: 330px"></canvas>

            </div>

        </div>

    </div>


    <div class="col-lg-4">

        <div class="card dashboard-card shadow-sm h-100">

            <div class="card-header">

                <h6 class="mb-1">
                    Répartition des cotations
                </h6>

                <small class="text-muted">
                    Selon leur statut
                </small>

            </div>

            <div class="card-body">

                <canvas id="statusChart"
                        style="max-height: 280px"></canvas>

            </div>

        </div>

    </div>

</div>


<!-- ============================================================
     ACTIONS RAPIDES
============================================================ -->

<div class="card dashboard-card shadow-sm mb-4">

    <div class="card-header">

        <h6 class="mb-0">
            Actions rapides
        </h6>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-3">
                <a href="<?= site_url('plannings-depart/new') ?>"
                class="quick-action">

                    <i class="bi bi-calendar-plus"></i>

                    <div class="fw-semibold mt-2">
                        Nouveau départ
                    </div>

                    <small class="text-muted">
                        Planning de départ
                    </small>
                </a>
            </div>

            <div class="col-md-3">

                <a href="<?= site_url('cotations/new') ?>"
                   class="quick-action">

                    <i class="bi bi-file-earmark-plus"></i>

                    <div class="fw-semibold mt-2">
                        Nouvelle cotation
                    </div>

                    <small class="text-muted">
                        Créer une proposition
                    </small>

                </a>

            </div>


            <div class="col-md-3">

                <a href="<?= site_url('reservations/new') ?>"
                   class="quick-action">

                    <i class="bi bi-calendar"></i>

                    <div class="fw-semibold mt-2">
                        Nouvelle réservation
                    </div>

                    <small class="text-muted">
                        Enregistrer un voyage
                    </small>

                </a>

            </div>


            <div class="col-md-3">

                <a href="<?= site_url('factures/create') ?>"
                   class="quick-action">

                    <i class="bi bi-receipt"></i>

                    <div class="fw-semibold mt-2">
                        Nouvelle facture
                    </div>

                    <small class="text-muted">
                        Créer une facture
                    </small>

                </a>

            </div>

        </div>

    </div>

</div>


<!-- ============================================================
     TABLEAUX
============================================================ -->

<div class="row g-3">

    <!-- Dernières cotations -->

    <div class="col-lg-7">

        <div class="card dashboard-card shadow-sm">

            <div class="card-header d-flex justify-content-between">

                <div>

                    <h6 class="mb-1">
                        Dernières cotations
                    </h6>

                    <small class="text-muted">
                        Les propositions les plus récentes
                    </small>

                </div>

                <a href="<?= site_url('cotations') ?>"
                   class="btn btn-sm btn-light border">
                    Voir tout
                </a>

            </div>


            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>
                            <th>Numéro</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Statut</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php if (! empty($dernieresCotations)): ?>

                        <?php foreach ($dernieresCotations as $cotation): ?>

                            <?php

                            $statutClass = match ($cotation['statut']) {
                                'brouillon' => 'bg-secondary',
                                'envoyee'   => 'bg-primary',
                                'acceptee'  => 'bg-success',
                                'refusee'   => 'bg-danger',
                                'expiree'   => 'bg-warning text-dark',
                                default     => 'bg-secondary',
                            };

                            ?>

                            <tr>

                                <td>

                                    <a href="<?= site_url('cotations/' . $cotation['id']) ?>"
                                       class="fw-semibold text-decoration-none">

                                        <?= esc($cotation['numero']) ?>

                                    </a>

                                </td>

                                <td>

                                    <div>
                                        <?= esc($cotation['client_nom'] ?? '-') ?>
                                    </div>

                                    <small class="text-muted">
                                        <?= esc($cotation['destination_nom'] ?? '') ?>
                                    </small>

                                </td>

                                <td>

                                    <?= number_format(
                                        $cotation['prix_total'] ?? 0,
                                        0,
                                        ',',
                                        ' '
                                    ) ?>

                                    <?= esc($cotation['devise'] ?? 'MGA') ?>

                                </td>

                                <td>

                                    <span class="badge <?= $statutClass ?>">
                                        <?= esc(ucfirst($cotation['statut'])) ?>
                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="4"
                                class="text-center text-muted py-4">

                                Aucune cotation pour le moment.

                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- Dernières réservations -->

    <div class="col-lg-5">

        <div class="card dashboard-card shadow-sm">

            <div class="card-header d-flex justify-content-between">

                <div>

                    <h6 class="mb-1">
                        Dernières réservations
                    </h6>

                    <small class="text-muted">
                        Activité récente
                    </small>

                </div>

                <a href="<?= site_url('reservations') ?>"
                   class="btn btn-sm btn-light border">
                    Voir tout
                </a>

            </div>


            <div class="list-group list-group-flush">

                <?php if (! empty($dernieresReservations)): ?>

                    <?php foreach ($dernieresReservations as $reservation): ?>

                        <a href="<?= site_url(
                            'reservations/' . $reservation['id']
                        ) ?>"
                           class="list-group-item list-group-item-action">

                            <div class="d-flex justify-content-between">

                                <strong>
                                    <?= esc($reservation['numero'] ?? 'RES-' . $reservation['id']) ?>
                                </strong>

                                <small class="text-muted">
                                    <?= esc($reservation['statut']) ?>
                                </small>

                            </div>

                            <div class="text-muted small mt-1">

                                <?= esc($reservation['client_nom'] ?? '-') ?>

                                <?php if (! empty($reservation['montant_total'])): ?>

                                    · <?= number_format(
                                        $reservation['montant_total'],
                                        0,
                                        ',',
                                        ' '
                                    ) ?> Ar

                                <?php endif; ?>

                            </div>

                        </a>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="text-center text-muted py-5">

                        <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>

                        Aucune réservation pour le moment.

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const labelsGraphique = <?= json_encode($labelsGraphique) ?>;

const donneesCotations = <?= json_encode($donneesCotations) ?>;

const donneesReservations = <?= json_encode($donneesReservations) ?>;


/*
|--------------------------------------------------------------------------
| Graphique activité
|--------------------------------------------------------------------------
*/

new Chart(
    document.getElementById('activityChart'),
    {
        type: 'line',

        data: {

            labels: labelsGraphique,

            datasets: [

                {
                    label: 'Cotations',
                    data: donneesCotations,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                },

                {
                    label: 'Réservations',
                    data: donneesReservations,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }

            ]

        },

        options: {

            responsive: true,

            plugins: {

                legend: {
                    position: 'top'
                }

            },

            scales: {

                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }

            }

        }

    }
);


/*
|--------------------------------------------------------------------------
| Graphique statuts
|--------------------------------------------------------------------------
*/

new Chart(
    document.getElementById('statusChart'),
    {
        type: 'doughnut',

        data: {

            labels: [
                'Brouillon',
                'Envoyée',
                'Acceptée',
                'Refusée',
                'Expirée'
            ],

            datasets: [

                {
                    data: [
                        <?= $cotationsParStatut['brouillon'] ?? 0 ?>,
                        <?= $cotationsParStatut['envoyee'] ?? 0 ?>,
                        <?= $cotationsParStatut['acceptee'] ?? 0 ?>,
                        <?= $cotationsParStatut['refusee'] ?? 0 ?>,
                        <?= $cotationsParStatut['expiree'] ?? 0 ?>
                    ]
                }

            ]

        },

        options: {

            responsive: true,

            plugins: {

                legend: {
                    position: 'bottom'
                }

            }

        }

    }
);

</script>

<?= $this->endSection() ?>