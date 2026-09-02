<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h4 class="mb-1">
            Réservations
        </h4>

        <p class="text-muted mb-0">
            Gestion et suivi des voyages réservés.
        </p>

    </div>

    <?php if (can('reservations.create')): ?>
    <a href="<?= site_url('reservations/new') ?>"
       class="btn lc-btn-primary">

        <i class="bi bi-plus-lg me-1"></i>
        Nouvelle réservation

    </a>
    <?php endif; ?>

</div>


<div class="lc-card">

    <div class="table-responsive p-4">

        <table id="reservationsTable" class="table align-middle mb-0">

            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Client</th>
                    <th>Destination</th>
                    <th>Départ</th>
                    <th>Retour</th>
                    <th class="text-end">Montant total</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($reservations)): ?>

                    <?php foreach ($reservations as $reservation): ?>

                        <?php

                        $statut = $reservation['statut'] ?? 'en_attente';

                        $statutClass = match ($statut) {
                            'confirmée', 'confirmee' => 'text-bg-success',
                            'en_attente'             => 'text-bg-warning',
                            'annulée', 'annulee'      => 'text-bg-danger',
                            'terminée', 'terminee'    => 'text-bg-secondary',
                            default                   => 'text-bg-primary',
                        };

                        ?>

                        <tr>

                            <!-- NUMERO -->
                            <td>

                                <a
                                    href="<?= site_url(
                                        'reservations/' .
                                        $reservation['id']
                                    ) ?>"
                                    class="fw-semibold text-decoration-none"
                                >

                                    <?= esc(
                                        $reservation['numero'] ?? '—'
                                    ) ?>

                                </a>

                            </td>


                            <!-- CLIENT -->
                            <td>

                                <?= esc(
                                    trim(
                                        ($reservation['client_prenom'] ?? '') .
                                        ' ' .
                                        ($reservation['client_nom'] ?? '')
                                    )
                                ) ?: '—' ?>

                            </td>


                            <!-- DESTINATION -->
                            <td>

                                <?= esc(
                                    $reservation['destination_nom'] ?? '—'
                                ) ?>

                            </td>


                            <!-- DEPART -->
                            <td>
                                

                                <?= !empty($reservation['date_depart'])
                                    ? date('d/m/Y', strtotime($reservation['date_depart']))
                                    : '—'
                                ?>

                            </td>


                            <!-- RETOUR -->
                            <td>
                                <?= !empty($reservation['date_retour'])
                                    ? date('d/m/Y', strtotime($reservation['date_retour']))
                                    : '—'
                                ?>

                            </td>


                            <!-- TOTAL -->
                            <td class="text-end fw-semibold">

                                <?= number_format(
                                    (float) (
                                        $reservation['montant_total'] ?? 0
                                    ),
                                    0,
                                    ',',
                                    ' '
                                ) ?>

                                <?= esc(
                                    $reservation['devise'] ?? ''
                                ) ?>

                            </td>


                            <!-- STATUT -->
                            <td>

                                <span class="badge <?= $statutClass ?>">

                                    <?= esc(
                                        ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $statut
                                            )
                                        )
                                    ) ?>

                                </span>

                            </td>


                            <!-- ACTIONS -->
                            <td class="text-end">

                                <div class="btn-group">
                                    <?php if (can('reservations.view')): ?>
                                    <a
                                        href="<?= site_url(
                                            'reservations/' .
                                            $reservation['id']
                                        ) ?>"
                                        class="btn btn-sm btn-light border"
                                        title="Voir"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>
                                    <?php endif; ?>

                                    <?php if (can('reservations.edit')): ?>
                                    <a
                                        href="<?= site_url(
                                            'reservations/' .
                                            $reservation['id'] .
                                            '/edit'
                                        ) ?>"
                                        class="btn btn-sm btn-light border"
                                        title="Modifier"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </a>
                                    <?php endif; ?>

                                    <?php if (can('reservations.delete')): ?>
                                    <a
                                        href="<?= site_url(
                                            'reservations/' .
                                            $reservation['id'] .
                                            '/delete'
                                        ) ?>"
                                        class="btn btn-sm btn-light border text-danger"
                                        title="Supprimer"
                                        onclick="return confirm(
                                            'Supprimer cette réservation ?'
                                        )"
                                    >

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

        $('#reservationsTable').DataTable({
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
                lengthMenu: 'Afficher _MENU_ réservations',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ réservations',
                infoEmpty: 'Affichage de 0 à 0 sur 0 réservation',
                infoFiltered: '(filtré à partir de _MAX_ réservations au total)',
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