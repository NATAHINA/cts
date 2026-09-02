<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?= $this->include('catalogue/filters') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <?php if (can('excursions.create')): ?>
        <a href="<?= site_url('excursions/new') ?>" class="btn lc-btn-primary"><i class="bi bi-plus-lg me-1"></i> Ajouter</a>
    <?php endif; ?>
</div>

<div class="lc-card">
    <div class="table-responsive p-4">
        <table id="excursionTable" class="table align-middle mb-0">
            <thead>
                <tr class="text-secondary small">
                    <th>Nom</th>
                    <th>Destination</th>
                    <th>Durée (h)</th>
                    <th>Prix</th>
                    <th>Fournisseur / tarifs</th>
                    <th>Disponibilité</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td><?= esc($it['nom'] ?? '—') ?></td>
                        <td><?= esc($it['destination_nom'] ?? '—') ?></td>
                        <td><?= esc($it['duree_heures'] ?? '—') ?></td>
                        <td><?= number_format((float) ($it['prix'] ?? 0), 0, ',', ' ') ?></td>
                        <td><div><?= esc($it['fournisseur_nom'] ?? '—') ?></div><small class="text-secondary">Ad: <?= esc($it['prix_adulte'] ?? '—') ?> · Enf: <?= esc($it['prix_enfant'] ?? '—') ?> · Grp: <?= esc($it['prix_groupe'] ?? '—') ?></small></td>
                        <td><span class="badge rounded-pill <?= ($it['disponibilite'] ?? 'disponible') === 'disponible' ? 'lc-badge-actif' : 'lc-badge-inactif' ?>"><?= esc($it['disponibilite'] ?? 'disponible') ?></span></td>
                        <td><span class="badge rounded-pill <?= $it['statut'] === 'actif' ? 'lc-badge-actif' : 'lc-badge-inactif' ?>"><?= esc($it['statut']) ?></span></td>
                        <td class="text-end">
                            <?php if (can('excursions.edit')): ?>
                                <a href="<?= site_url('excursions/' . $it['id'] . '/edit') ?>" class="btn btn-sm btn-light border"><i class="bi bi-pencil"></i></a>
                            <?php endif; ?>
                            <?php if (can('excursions.delete')): ?>
                                <a href="<?= site_url('excursions/' . $it['id'] . '/delete') ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Supprimer cet élément ?');"><i class="bi bi-trash"></i></a>
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

        $('#excursionTable').DataTable({
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
                lengthMenu: 'Afficher _MENU_ excursions',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ excursions',
                infoEmpty: 'Affichage de 0 à 0 sur 0 excursion',
                infoFiltered: '(filtré à partir de _MAX_ excursions au total)',
                infoPostFix: '',
                loadingRecords: 'Chargement en cours...',
                zeroRecords: 'Aucune excursion trouvée',
                emptyTable: 'Aucune excursion enregistrée',
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
