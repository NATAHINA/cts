<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$statutsClass = [
    'brouillon'            => 'bg-secondary',
    'emise'                => 'bg-primary',
    'partiellement_payee'  => 'bg-warning text-dark',
    'payee'                => 'bg-success',
    'annulee'              => 'bg-danger',
    'en_retard'            => 'bg-danger',
];
$statutsLabel = [
    'brouillon'            => 'Brouillon',
    'emise'                => 'Émise',
    'partiellement_payee'  => 'Partiellement payée',
    'payee'                => 'Payée',
    'annulee'              => 'Annulée',
    'en_retard'            => 'En retard',
];
?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Factures</h4>
        <p class="text-muted mb-0">Suivi de la facturation et des encaissements</p>
    </div>
    <?php if (can('factures.create')): ?>
    <a href="<?= site_url('factures/create') ?>" class="btn lc-btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nouvelle facture
    </a>
    <?php endif; ?>
</div>

<div class="lc-card">
    <div class="table-responsive p-4">
        <table id="facturesTable" class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>N°</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th class="text-end">TTC</th>
                    <th class="text-end">Payé</th>
                    <th class="text-end">Restant</th>
                    <th>Statut</th>
                    <th class="text-end" style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                            $client = trim(($item['client_prenom'] ?? '') . ' ' . ($item['client_nom'] ?? ''));
                            if ($client === '') {
                                $client = $item['client_entreprise'] ?? '—';
                            }
                            $statut = $item['statut'] ?? 'brouillon';
                        ?>
                        <tr>
                            <td>
                                <a href="<?= site_url('factures/' . $item['id']) ?>" class="fw-semibold text-decoration-none">
                                    <?= esc($item['numero']) ?>
                                </a>
                            </td>
                            <td>
                                <?= !empty($item['date_facture'])
                                    ? date('d/m/Y', strtotime($item['date_facture']))
                                    : '—' ?>
                            </td>
                            <td><?= esc($client) ?></td>
                            <td class="text-end">
                                <?= number_format((float)($item['montant_ttc'] ?? 0), 0, ',', ' ') ?>
                                <small class="text-muted"><?= esc($item['devise'] ?? '') ?></small>
                            </td>
                            <td class="text-end text-success">
                                <?= number_format((float)($item['montant_paye'] ?? 0), 0, ',', ' ') ?>
                            </td>
                            <td class="text-end <?= ((float)($item['montant_restant'] ?? 0) > 0) ? 'text-danger fw-semibold' : '' ?>">
                                <?= number_format((float)($item['montant_restant'] ?? 0), 0, ',', ' ') ?>
                            </td>
                            <td>
                                <span class="badge <?= $statutsClass[$statut] ?? 'bg-secondary' ?>">
                                    <?= esc($statutsLabel[$statut] ?? $statut) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <?php if (can('factures.view')): ?>
                                    <a href="<?= site_url('factures/' . $item['id']) ?>"
                                       class="btn btn-sm btn-light border" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if (can('factures.print')): ?>
                                <a href="<?= site_url('factures/' . $item['id'] . '/print') ?>"
                                   class="btn btn-sm btn-light border" title="Imprimer" target="_blank">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
        document.addEventListener('DOMContentLoaded', function () {

        $('#facturesTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'Tous']
            ],

            order: [
                [0, 'desc']
            ],

            columnDefs: [
                {
                    targets: 6,
                    orderable: false,
                    searchable: false
                }
            ],

            language: {
                processing: 'Traitement en cours...',
                search: 'Rechercher :',
                lengthMenu: 'Afficher _MENU_ factures',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ factures',
                infoEmpty: 'Affichage de 0 à 0 sur 0 facture',
                infoFiltered: '(filtré à partir de _MAX_ factures au total)',
                infoPostFix: '',
                loadingRecords: 'Chargement en cours...',
                zeroRecords: 'Aucune facture trouvée',
                emptyTable: 'Aucune facture enregistrée',
                paginate: {
                    first: 'Premier',
                    previous: 'Précédent',
                    next: 'Suivant',
                    last: 'Dernier'
                },
                aria: {
                    sortAscending: ': activer pour trier la colonne par ordre croissant',
                    sortDescending: ': activer pour trier la colonne par ordre décroissant'
                }
            }
        });

    });
</script>


<?= $this->endSection() ?>