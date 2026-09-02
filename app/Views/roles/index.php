<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Rôles</h1>
            <p class="text-muted mb-0">
                Gestion des rôles et des permissions de votre agence.
            </p>
        </div>

        <?php if (can('utilisateurs.permissions')): ?>
            <a href="<?= site_url('roles/new') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Nouveau rôle
            </a>
        <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= esc(session()->getFlashdata('success')) ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= esc(session()->getFlashdata('error')) ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table id="rolesTable"
                       class="table table-hover align-middle w-100">

                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Libellé</th>
                            <th>Description</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($roles as $role): ?>

                        <tr>

                            <td>
                                <span class="badge bg-secondary">
                                    <?= esc($role['code']) ?>
                                </span>
                            </td>

                            <td>
                                <strong>
                                    <?= esc($role['libelle']) ?>
                                </strong>
                            </td>

                            <td>
                                <?= esc($role['description'] ?? '—') ?>
                            </td>

                            <td class="text-center">

                                <div class="btn-group btn-group-sm">

                                    <?php if (can('utilisateurs.permissions')): ?>

                                        <a href="<?= site_url('roles/' . $role['id'] . '/permissions') ?>"
                                           class="btn btn-outline-primary"
                                           title="Gérer les permissions">

                                            <i class="bi bi-shield-check"></i>
                                        </a>

                                        <a href="<?= site_url('roles/' . $role['id'] . '/edit') ?>"
                                           class="btn btn-outline-secondary"
                                           title="Modifier">

                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a href="<?= site_url('roles/' . $role['id'] . '/delete') ?>"
                                           class="btn btn-outline-danger"
                                           title="Supprimer"
                                           onclick="return confirm('Voulez-vous vraiment supprimer ce rôle ?');">

                                            <i class="bi bi-trash"></i>
                                        </a>

                                    <?php endif; ?>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>

<script>
$(document).ready(function () {

    $('#rolesTable').DataTable({

        responsive: true,

        pageLength: 10,

        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, 'Tous']
        ],

        order: [[1, 'asc']],

        columnDefs: [
            {
                targets: 3,
                orderable: false,
                searchable: false
            }
        ],

        language: {
            processing: "Traitement en cours...",
            search: "Rechercher :",
            lengthMenu: "Afficher _MENU_ éléments",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
            infoEmpty: "Affichage de 0 à 0 sur 0 élément",
            infoFiltered: "(filtré de _MAX_ éléments au total)",
            loadingRecords: "Chargement...",
            zeroRecords: "Aucun rôle trouvé",
            emptyTable: "Aucun rôle enregistré",
            paginate: {
                first: "Premier",
                previous: "Précédent",
                next: "Suivant",
                last: "Dernier"
            }
        }

    });

});
</script>

<?= $this->endSection() ?>