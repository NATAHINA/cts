<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Paiements</h4>
        <p class="text-muted mb-0">Historique des encaissements</p>
    </div>
    <a href="<?= site_url('factures') ?>" class="btn btn-light border">
        Voir les factures
    </a>
</div>

<div class="lc-card p-0">
    <div class="table-responsive p-4">
        <table id="paiementsTable" class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>N°</th>
                    <th>Date</th>
                    <th>Facture</th>
                    <th>Client</th>
                    <th>Mode</th>
                    <th class="text-end">Montant</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Aucun paiement.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                            $client = trim(($item['client_prenom'] ?? '') . ' ' . ($item['client_nom'] ?? ''));
                            $annule = ($item['statut'] ?? '') === 'annule';
                        ?>
                        <tr class="<?= $annule ? 'table-secondary' : '' ?>">
                            <td class="fw-semibold"><?= esc($item['numero']) ?></td>
                            <td><?= date('d/m/Y', strtotime($item['date_paiement'])) ?></td>
                            <td>
                                <?php if (!empty($item['facture_id'])): ?>
                                    <a href="<?= site_url('factures/' . $item['facture_id']) ?>">
                                        <?= esc($item['facture_numero'] ?? '') ?>
                                    </a>
                                <?php else: ?>—<?php endif; ?>
                            </td>
                            <td><?= esc($client ?: '—') ?></td>
                            <td class="text-capitalize"><?= esc(str_replace('_', ' ', $item['mode_paiement'] ?? '')) ?></td>
                            <td class="text-end fw-semibold <?= $annule ? 'text-muted text-decoration-line-through' : 'text-success' ?>">
                                <?= number_format((float)$item['montant'], 2, ',', ' ') ?>
                                <small><?= esc($item['devise'] ?? '') ?></small>
                            </td>
                            <td>
                                <span class="badge <?= $annule ? 'bg-secondary' : 'bg-success' ?>">
                                    <?= $annule ? 'Annulé' : 'Validé' ?>
                                </span>
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

        $('#paiementsTable').DataTable({
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
                lengthMenu: 'Afficher _MENU_ paiements',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ paiements',
                infoEmpty: 'Affichage de 0 à 0 sur 0 paiement',
                infoFiltered: '(filtré à partir de _MAX_ paiements au total)',
                infoPostFix: '',
                loadingRecords: 'Chargement en cours...',
                zeroRecords: 'Aucun paiement trouvé',
                emptyTable: 'Aucun paiement enregistré',
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