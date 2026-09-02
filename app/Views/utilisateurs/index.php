<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Utilisateurs & rôles</h4>
        <p class="text-muted mb-0">Gestion des comptes de l'agence.</p>
    </div>
    <div class="d-flex gap-2">

        <?php if (can('utilisateurs.permissions')): ?>
            <a href="<?= site_url('roles') ?>" class="btn btn-light border">
                <i class="bi bi-shield-lock me-1"></i>
                Gérer les rôles
            </a>
        <?php endif; ?>

        <?php if (can('utilisateurs.create')): ?>
            <a href="<?= site_url('utilisateurs/new') ?>" class="btn lc-btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Nouvel utilisateur
            </a>
        <?php endif; ?>

    </div>
</div>

<div class="lc-card">
    <div class="table-responsive p-4">
        <table id="userTable" class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            Aucun utilisateur.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="fw-semibold">
                                <?= esc(trim(($u['prenom'] ?? '') . ' ' . ($u['nom'] ?? ''))) ?>
                            </td>
                            <td><?= esc($u['email']) ?></td>
                            <td><?= esc($u['role_libelle'] ?? '—') ?></td>
                            <td>
                                <?php if ($u['actif']): ?>
                                    <span class="badge lc-badge-actif">Actif</span>
                                <?php else: ?>
                                    <span class="badge lc-badge-inactif">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <?php if (can('utilisateurs.edit')): ?>
                                    <a href="<?= site_url('utilisateurs/' . $u['id'] . '/edit') ?>"
                                       class="btn btn-sm btn-light border">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (can('utilisateurs.delete')): ?>
                                    <a href="<?= site_url('utilisateurs/' . $u['id'] . '/delete') ?>"
                                       class="btn btn-sm btn-light border text-danger"
                                       onclick="return confirm('Supprimer cet utilisateur ?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
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

        $('#userTable').DataTable({
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
                lengthMenu: 'Afficher _MENU_ utilisateurs',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ utilisateurs',
                infoEmpty: 'Affichage de 0 à 0 sur 0 utilisateur',
                infoFiltered: '(filtré à partir de _MAX_ utilisateurs au total)',
                infoPostFix: '',
                loadingRecords: 'Chargement en cours...',
                zeroRecords: 'Aucun utilisateur trouvé',
                emptyTable: 'Aucun utilisateur enregistré',
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