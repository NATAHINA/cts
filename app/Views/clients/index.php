<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">Clients</h4>
        <p class="text-muted mb-0">
            Gérez votre portefeuille clients.
        </p>
    </div>

    <?php if (can('clients.create')): ?>
        <a href="<?= site_url('clients/new') ?>" class="btn lc-btn-primary">
            <i class="bi bi-person-plus me-1"></i>
            Nouveau client
        </a>
    <?php endif; ?>

</div>

<div class="lc-card">
    <div class="table-responsive p-4">
        <table id="clientsTable" class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Pays</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($clients as $client): ?>

                    <tr>

                        <td>
                            <a href="<?= site_url('clients/' . $client['id']) ?>"
                               class="fw-semibold">
                                <?= esc(trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''))) ?>
                            </a>
                        </td>

                        <td><?= esc($client['type_client'] ?? 'Particulier') ?></td>
                        <td><?= esc($client['telephone'] ?? '-') ?></td>
                        <td><?= esc($client['email'] ?? '-') ?></td>
                        <td><?= esc($client['pays'] ?? '-') ?></td>

                        <td>
                            <span class="badge <?= ($client['statut'] ?? '') === 'actif'
                                ? 'lc-badge-actif'
                                : 'lc-badge-inactif' ?>">
                                <?= esc($client['statut'] ?? 'actif') ?>
                            </span>
                        </td>

                        <td class="text-end">
                            <?php if (can('clients.edit')): ?>
                                <a href="<?= site_url('clients/' . $client['id'] . '/edit') ?>"
                                   class="btn btn-sm btn-light border">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (can('clients.delete')): ?>
                                <a href="<?= site_url('clients/' . $client['id'] . '/delete') ?>"
                                   class="btn btn-sm btn-light border text-danger"
                                   onclick="return confirm('Supprimer ce client ?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>


<script>
        document.addEventListener('DOMContentLoaded', function () {

        $('#clientsTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'Tous']
            ],

            order: [
                [3, 'desc']
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
                lengthMenu: 'Afficher _MENU_ clients',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ clients',
                infoEmpty: 'Affichage de 0 à 0 sur 0 client',
                infoFiltered: '(filtré à partir de _MAX_ clients au total)',
                infoPostFix: '',
                loadingRecords: 'Chargement en cours...',
                zeroRecords: 'Aucun client trouvé',
                emptyTable: 'Aucun client enregistré',
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