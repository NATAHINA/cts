<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Rapports & statistiques</h4>
        <p class="text-muted mb-0">Vue d’ensemble de l’activité</p>
    </div>
    <form method="get" class="d-flex gap-2 align-items-center">
        <label class="text-muted small mb-0">Année</label>
        <select name="annee" class="form-select form-select-sm" style="width:100px;"
                onchange="this.form.submit()">
            <?php for ($y = (int)date('Y'); $y >= (int)date('Y') - 5; $y--): ?>
                <option value="<?= $y ?>" <?= (int)$annee === $y ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
    </form>
</div>

<!-- KPI -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="lc-card p-3 h-100">
            <small class="text-muted d-block">CA facturé</small>
            <div class="fs-4 fw-bold" style="color:var(--lc-accent);">
                <?= number_format($caFacture, 0, ',', ' ') ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="lc-card p-3 h-100">
            <small class="text-muted d-block">CA encaissé</small>
            <div class="fs-4 fw-bold text-success">
                <?= number_format($caEncaisse, 0, ',', ' ') ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="lc-card p-3 h-100">
            <small class="text-muted d-block">Impayés</small>
            <div class="fs-4 fw-bold text-danger">
                <?= number_format($impayes, 0, ',', ' ') ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="lc-card p-3 h-100">
            <small class="text-muted d-block">Réservations</small>
            <div class="fs-4 fw-bold"><?= (int)$nbReservations ?></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Graphique CA -->
    <div class="col-lg-8">
        <div class="lc-card p-4 h-100">
            <h6 class="fw-bold mb-3">Encaissements mensuels — <?= (int)$annee ?></h6>
            <canvas id="chartCA" height="140"></canvas>
        </div>
    </div>

    <!-- Entonnoir -->
    <div class="col-lg-4">
        <div class="lc-card p-4 h-100">
            <h6 class="fw-bold mb-3">Entonnoir commercial</h6>
            <?php
            $steps = [
                'demandes'     => ['Demandes', $entonnoir['demandes'] ?? 0],
                'cotations'    => ['Cotations', $entonnoir['cotations'] ?? 0],
                'reservations' => ['Réservations', $entonnoir['reservations'] ?? 0],
                'factures'     => ['Factures', $entonnoir['factures'] ?? 0],
            ];
            $max = max(array_column($steps, 1) ?: [1]);
            if ($max < 1) $max = 1;
            foreach ($steps as $key => [$label, $val]):
                $pct = round($val / $max * 100);
            ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span><?= $label ?></span>
                        <strong><?= (int)$val ?></strong>
                    </div>
                    <div class="progress" style="height:8px;">
                        <div class="progress-bar" style="width:<?= $pct ?>%;background:var(--lc-accent);"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Top clients -->
    <div class="col-lg-7">
        <div class="lc-card p-4">
            <h6 class="fw-bold mb-3">Top 10 clients (encaissements)</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th class="text-end">Factures</th>
                            <th class="text-end">Total payé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($topClients)): ?>
                            <tr><td colspan="4" class="text-muted text-center py-3">Aucune donnée</td></tr>
                        <?php else: ?>
                            <?php foreach ($topClients as $i => $c): ?>
                                <tr>
                                    <td class="text-muted"><?= $i + 1 ?></td>
                                    <td>
                                        <?= esc(trim(($c['prenom'] ?? '') . ' ' . ($c['nom'] ?? ''))) ?>
                                        <?php if (!empty($c['entreprise'])): ?>
                                            <small class="text-muted d-block"><?= esc($c['entreprise']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end"><?= (int)$c['nb_factures'] ?></td>
                                    <td class="text-end fw-semibold">
                                        <?= number_format((float)$c['total_paye'], 0, ',', ' ') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Factures par statut -->
    <div class="col-lg-5">
        <div class="lc-card p-4">
            <h6 class="fw-bold mb-3">Factures par statut</h6>
            <?php
            $labelsStatut = [
                'brouillon'           => 'Brouillon',
                'emise'               => 'Émise',
                'partiellement_payee' => 'Part. payée',
                'payee'               => 'Payée',
                'annulee'             => 'Annulée',
                'en_retard'           => 'En retard',
            ];
            if (empty($facturesParStatut)):
            ?>
                <p class="text-muted mb-0">Aucune facture cette année.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($facturesParStatut as $row): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><?= esc($labelsStatut[$row['statut']] ?? $row['statut']) ?></span>
                            <span>
                                <span class="badge text-bg-light border me-2"><?= (int)$row['nb'] ?></span>
                                <strong><?= number_format((float)($row['total'] ?? 0), 0, ',', ' ') ?></strong>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('chartCA'), {
    type: 'bar',
    data: {
        labels: ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Aoû','Sep','Oct','Nov','Déc'],
        datasets: [{
            label: 'Encaissements',
            data: <?= json_encode(array_values($caMensuel)) ?>,
            backgroundColor: '#4C5FD5',
            borderRadius: 6
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

<?= $this->endSection() ?>