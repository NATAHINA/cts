<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1"><?= esc($title) ?></h4>

        <p class="text-muted mb-0">
            Gestion des devis et cotations clients.
        </p>
    </div>

    <a href="<?= site_url('cotations/new') ?>"
       class="btn lc-btn-primary">

        <i class="bi bi-plus-lg me-1"></i>
        Nouvelle cotation

    </a>

</div>


<div class="lc-card">

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead>

                <tr>

                    <th>N° Cotation</th>
                    <th>Client</th>
                    <th>Départ</th>
                    <th>Voyageurs</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>

                </tr>

            </thead>


            <tbody>

                <?php if (!empty($items)): ?>

                    <?php foreach ($items as $item): ?>

                        <?php
                        $client = trim(
                            ($item['client_prenom'] ?? '')
                            . ' '
                            . ($item['client_nom'] ?? '')
                        );

                        if (empty($client)) {
                            $client = $item['client_entreprise'] ?? '—';
                        }
                        ?>

                        <tr>

                            <td class="fw-semibold">

                                <a href="<?= site_url(
                                    'cotations/' . $item['id']
                                ) ?>">

                                    <?= esc($item['numero']) ?>

                                </a>

                            </td>


                            <td>

                                <?= esc($client) ?>

                            </td>


                            <td>

                                <?= !empty($item['date_depart'])
                                    ? date(
                                        'd/m/Y',
                                        strtotime($item['date_depart'])
                                    )
                                    : '—' ?>

                            </td>


                            <td>

                                <?= (int) ($item['nb_adultes'] ?? 0) ?>
                                adultes

                            </td>


                            <td class="fw-semibold">

                                <?= number_format(
                                    (float) ($item['prix_total'] ?? 0),
                                    0,
                                    ',',
                                    ' '
                                ) ?>

                                <?= esc($item['devise'] ?? '') ?>

                            </td>


                            <td>

                                <?php
                                $badge = match ($item['statut']) {
                                    'brouillon' => 'bg-secondary',
                                    'envoyee'  => 'bg-primary',
                                    'acceptee' => 'bg-success',
                                    'refusee'  => 'bg-danger',
                                    'expiree'  => 'bg-warning text-dark',
                                    default    => 'bg-secondary',
                                };
                                ?>

                                <span class="badge <?= $badge ?>">

                                    <?= esc(
                                        ucfirst($item['statut'])
                                    ) ?>

                                </span>

                            </td>


                            <td class="text-end">

                                <a
                                    href="<?= site_url(
                                        'cotations/' . $item['id']
                                    ) ?>"
                                    class="btn btn-sm btn-light border"
                                    title="Voir"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>


                                <a
                                    href="<?= site_url(
                                        'cotations/' . $item['id'] . '/edit'
                                    ) ?>"
                                    class="btn btn-sm btn-light border"
                                    title="Modifier"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="7"
                            class="text-center text-muted py-5"
                        >

                            <i class="bi bi-file-earmark-text fs-3 d-block mb-2"></i>

                            Aucune cotation enregistrée.

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?= $this->endSection() ?>