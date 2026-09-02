<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <?php if (can('destinations.create')): ?>
        <a href="<?= site_url('destinations/new') ?>" class="btn lc-btn-primary"><i class="bi bi-plus-lg me-1"></i> Ajouter</a>
    <?php endif; ?>
</div>

<div class="lc-card">
    <div class="table-responsive p-4">
        <table id="destinationTable" class="table align-middle mb-0">
            <thead>
                <tr class="text-secondary small">
                    <th>Nom</th>
                    <th>Pays</th>
                    <th>Région</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td><?= esc($it['nom'] ?? '—') ?></td>
                        <td><?= esc($it['pays'] ?? '—') ?></td>
                        <td><?= esc($it['region'] ?? '—') ?></td>
                        <td><span class="badge rounded-pill <?= $it['statut'] === 'actif' ? 'lc-badge-actif' : 'lc-badge-inactif' ?>"><?= esc($it['statut']) ?></span></td>
                        <td class="text-end">
                            <?php if (can('destinations.edit')): ?>
                                <a href="<?= site_url('destinations/' . $it['id'] . '/edit') ?>" class="btn btn-sm btn-light border"><i class="bi bi-pencil"></i></a>
                            <?php endif; ?>
                            <?php if (can('destinations.delete')): ?>
                                <a href="<?= site_url('destinations/' . $it['id'] . '/delete') ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Supprimer cet élément ?');"><i class="bi bi-trash"></i></a>
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

        $('#destinationTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'Tous']
            ],

            order: [
                [0, 'asc']
            ],

            columnDefs: [
                {
                    orderable: false,
                    searchable: false
                }
            ],

            language: {
                processing: 'Traitement en cours...',
                search: 'Rechercher :',
                lengthMenu: 'Afficher _MENU_ destinations',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ destinations',
                infoEmpty: 'Affichage de 0 à 0 sur 0 destination',
                infoFiltered: '(filtré à partir de _MAX_ destinations au total)',
                infoPostFix: '',
                loadingRecords: 'Chargement en cours...',
                zeroRecords: 'Aucune destination trouvée',
                emptyTable: 'Aucune destination enregistrée',
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
