<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><?= esc($title) ?></h3>
            <p class="text-muted mb-0">
                Gestion des compagnies d'assurance
            </p>
        </div>

        <a href="<?= site_url('assurances/create') ?>"
           class="btn lc-btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Ajouter une assurance
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive p-4">

                <table id="assuranceTable" class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Adresse</th>
                            <th>Statut</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (! empty($assurances)) : ?>

                        <?php foreach ($assurances as $index => $assurance) : ?>

                            <tr>
                                <td><?= $index + 1 ?></td>

                                <td>
                                    <strong>
                                        <?= esc($assurance['nom']) ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= esc($assurance['telephone'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= esc($assurance['email'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= esc($assurance['adresse'] ?? '-') ?>
                                </td>

                                <td>
                                    <?php if ($assurance['statut'] === 'actif') : ?>
                                        <span class="badge bg-success">
                                            Actif
                                        </span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary">
                                            Inactif
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end">
                                    <!-- <a href="<?= site_url('assurances/' . $assurance['id']) ?>" class="btn btn-sm btn-light border"><i class="bi bi-eye"></i></a> -->
                                    <a href="<?= site_url('assurances/' . $assurance['id'] . '/edit') ?>" class="btn btn-sm btn-light border text-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="<?= site_url('assurances/' . $assurance['id'] . '/delete') ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Supprimer cet élément ?');"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <tr>
                            <td colspan="7"
                                class="text-center text-muted">
                                Aucune assurance enregistrée.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        $('#assuranceTable').DataTable({
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
                lengthMenu: 'Afficher _MENU_ assurances',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ assurances',
                infoEmpty: 'Affichage de 0 à 0 sur 0 assurance',
                infoFiltered: '(filtré à partir de _MAX_ assurances au total)',
                infoPostFix: '',
                loadingRecords: 'Chargement en cours...',
                zeroRecords: 'Aucune assurance trouvée',
                emptyTable: 'Aucune assurance enregistrée',
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