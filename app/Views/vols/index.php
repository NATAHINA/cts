<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?= $this->include('catalogue/filters') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <?php if (can('vols.create')): ?>
        <a href="<?= site_url('vols/new') ?>" class="btn lc-btn-primary"><i class="bi bi-plus-lg me-1"></i> Ajouter</a>
    <?php endif; ?>
</div>

<div class="lc-card">
    <div class="table-responsive p-4">
        <table id="volTable" class="table align-middle mb-0">
            <thead>
                <tr class="text-secondary small">
                    <th>Compagnie</th>
                    <th>Départ</th>
                    <th>Arrivée</th>
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
                        <td><?= esc($it['compagnie'] ?? '—') ?></td>
                        <td><?= esc($it['aeroport_depart'] ?? '—') ?></td>
                        <td><?= esc($it['aeroport_arrivee'] ?? '—') ?></td>
                        <td><?= number_format((float) ($it['prix'] ?? 0), 0, ',', ' ') ?></td>
                        <td><div><?= esc($it['fournisseur_nom'] ?? '—') ?></div><small class="text-secondary">Ad: <?= esc($it['prix_adulte'] ?? '—') ?> · Enf: <?= esc($it['prix_enfant'] ?? '—') ?> · Grp: <?= esc($it['prix_groupe'] ?? '—') ?></small></td>
                        <td><span class="badge rounded-pill <?= ($it['disponibilite'] ?? 'disponible') === 'disponible' ? 'lc-badge-actif' : 'lc-badge-inactif' ?>"><?= esc($it['disponibilite'] ?? 'disponible') ?></span></td>
                        <td><span class="badge rounded-pill <?= $it['statut'] === 'actif' ? 'lc-badge-actif' : 'lc-badge-inactif' ?>"><?= esc($it['statut']) ?></span></td>
                        <td class="text-end">
                            <?php if (can('vols.edit')): ?>
                                <a href="<?= site_url('vols/' . $it['id'] . '/edit') ?>" class="btn btn-sm btn-light border"><i class="bi bi-pencil"></i></a>
                            <?php endif; ?>
                            <?php if (can('vols.delete')): ?>
                                <a href="<?= site_url('vols/' . $it['id'] . '/delete') ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Supprimer cet élément ?');"><i class="bi bi-trash"></i></a>
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

        $('#volTable').DataTable({
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
                lengthMenu: 'Afficher _MENU_ vols',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ vols',
                infoEmpty: 'Affichage de 0 à 0 sur 0 vol',
                infoFiltered: '(filtré à partir de _MAX_ vols au total)',
                infoPostFix: '',
                loadingRecords: 'Chargement en cours...',
                zeroRecords: 'Aucun vol trouvé',
                emptyTable: 'Aucun vol enregistré',
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
