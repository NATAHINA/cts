<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <div>
        <h5 class="mb-0">Liste des forfaits</h5>
        <small class="text-secondary">
            Gérez les forfaits et leurs tarifs.
        </small>
    </div>

    <?php if (can('forfaits.create')): ?>
        <a href="<?= site_url('forfaits/new') ?>" class="btn lc-btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Ajouter un forfait
        </a>
    <?php endif; ?>

</div>

<?php if (session('success')): ?>
    <div class="alert alert-success">
        <?= esc(session('success')) ?>
    </div>
<?php endif; ?>

<?php if (session('error')): ?>
    <div class="alert alert-danger">
        <?= esc(session('error')) ?>
    </div>
<?php endif; ?>

<div class="lc-card">

    <div class="table-responsive p-4">

        <table id="forfaitTable" class="table align-middle mb-0">

            <thead>
                <tr class="text-secondary small">
                    <th>Code</th>
                    <th>Forfait</th>
                    <th>Destination</th>
                    <th>Fournisseur</th>
                    <th>Durée</th>
                    <th>Prix</th>
                    <th>Disponibilité</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($items)): ?>

                <?php foreach ($items as $it): ?>

                    <tr>

                        <td>
                            <?= ! empty($it['code'])
                                ? esc($it['code'])
                                : '—' ?>
                        </td>

                        <td>
                            <div class="fw-semibold">
                                <?= esc($it['nom'] ?? '—') ?>
                            </div>

                            <?php if (! empty($it['description'])): ?>
                                <small class="text-secondary">
                                    <?= esc(
                                        mb_strimwidth(
                                            $it['description'],
                                            0,
                                            50,
                                            '...'
                                        )
                                    ) ?>
                                </small>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= esc($it['destination_nom'] ?? '—') ?>
                        </td>

                        <td>
                            <?= esc($it['fournisseur_nom'] ?? '—') ?>
                        </td>

                        <td>
                            <?php if (
                                ! empty($it['duree_jours']) ||
                                ! empty($it['duree_nuits'])
                            ): ?>

                                <?= (int) ($it['duree_jours'] ?? 0) ?> j

                                <?php if (
                                    isset($it['duree_nuits']) &&
                                    $it['duree_nuits'] !== null &&
                                    $it['duree_nuits'] !== ''
                                ): ?>
                                    / <?= (int) $it['duree_nuits'] ?> n
                                <?php endif; ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (
                                isset($it['prix']) &&
                                $it['prix'] !== null &&
                                $it['prix'] !== ''
                            ): ?>

                                <strong>
                                    <?= number_format(
                                        (float) $it['prix'],
                                        0,
                                        ',',
                                        ' '
                                    ) ?>
                                </strong>

                                <small class="text-secondary">
                                    <?= esc($it['devise_code'] ?? '') ?>
                                </small>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (
                                ($it['disponibilite'] ?? 'disponible') === 'disponible'
                            ): ?>
                                <span class="badge text-bg-success">
                                    Disponible
                                </span>
                            <?php else: ?>
                                <span class="badge text-bg-warning">
                                    Indisponible
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (($it['statut'] ?? '') === 'actif'): ?>
                                <span class="badge rounded-pill lc-badge-actif">
                                    Actif
                                </span>
                            <?php else: ?>
                                <span class="badge rounded-pill lc-badge-inactif">
                                    Inactif
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="text-end">
                            <?php if (can('forfaits.edit')): ?>
                            <a
                                href="<?= site_url(
                                    'forfaits/' . $it['id'] . '/edit'
                                ) ?>"
                                class="btn btn-sm btn-light border"
                                title="Modifier"
                            >
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (can('forfaits.delete')): ?>
                            <a
                                href="<?= site_url(
                                    'forfaits/' . $it['id'] . '/delete'
                                ) ?>"
                                class="btn btn-sm btn-light border text-danger"
                                title="Supprimer"
                                onclick="return confirm('Voulez-vous vraiment supprimer ce forfait ?');"
                            >
                                <i class="bi bi-trash"></i>
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

        $('#forfaitTable').DataTable({
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
                lengthMenu: 'Afficher _MENU_ forfaits',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ forfaits',
                infoEmpty: 'Affichage de 0 à 0 sur 0 forfait',
                infoFiltered: '(filtré à partir de _MAX_ forfaits au total)',
                infoPostFix: '',
                loadingRecords: 'Chargement en cours...',
                zeroRecords: 'Aucun forfait trouvé',
                emptyTable: 'Aucun forfait enregistré',
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