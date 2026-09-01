<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$statutsClass = [
    'nouvelle'   => 'bg-primary',
    'en_cours'   => 'bg-warning text-dark',
    'traitee'    => 'bg-info text-dark',
    'cotée'      => 'bg-info text-dark',
    'cotee'      => 'bg-info text-dark',
    'convertie'  => 'bg-success',
    'abandonnée' => 'bg-secondary',
    'abandonnee' => 'bg-secondary',
    'annulee'    => 'bg-danger',
    'annulée'    => 'bg-danger',
    'refusee'    => 'bg-danger',
    'refusée'    => 'bg-danger',
];

$statutsLabel = [
    'nouvelle'   => 'Nouvelle',
    'en_cours'   => 'En cours',
    'traitee'    => 'Traitée',
    'cotée'      => 'Cotée',
    'cotee'      => 'Cotée',
    'convertie'  => 'Convertie',
    'abandonnée' => 'Abandonnée',
    'abandonnee' => 'Abandonnée',
    'annulee'    => 'Annulée',
    'annulée'    => 'Annulée',
    'refusee'    => 'Refusée',
    'refusée'    => 'Refusée',
];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Demandes clients</h4>
        <p class="text-muted mb-0">
            Suivez les demandes avant leur transformation en cotation.
        </p>
    </div>

    <a href="<?= site_url('demandes/new') ?>" class="btn lc-btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Nouvelle demande
    </a>
</div>

<div class="lc-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Client</th>
                    <th>Destination</th>
                    <th>Départ</th>
                    <th>Budget</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($demandes)): ?>
                <?php foreach ($demandes as $demande): ?>
                    <?php
                        $statut = $demande['statut'] ?? 'nouvelle';
                        $badgeClass = $statutsClass[$statut] ?? 'bg-secondary';
                        $badgeLabel = $statutsLabel[$statut] ?? ucfirst(str_replace('_', ' ', $statut));
                    ?>
                    <tr>
                        <td>
                            <a href="<?= site_url('demandes/' . $demande['id']) ?>"
                               class="fw-semibold text-decoration-none">
                                <?= esc($demande['numero']) ?>
                            </a>
                        </td>

                        <td>
                            <?= esc(trim(($demande['client_prenom'] ?? '') . ' ' . ($demande['client_nom'] ?? ''))) ?>
                        </td>

                        <td>
                            <?= esc($demande['destination_nom'] ?? $demande['destination_nom'] ?? '—') ?>
                        </td>

                        <td>
                            <?= !empty($demande['date_depart'])
                                ? esc(date('d/m/Y', strtotime($demande['date_depart'])))
                                : '—' ?>
                        </td>

                        <td>
                            <?php if (isset($demande['budget']) && $demande['budget'] !== '' && $demande['budget'] !== null): ?>
                                <?= number_format((float) $demande['budget'], 0, ',', ' ') ?>
                                <?= esc($demande['devise'] ?? '') ?>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td>
                            <span class="badge <?= $badgeClass ?>">
                                <?= esc($badgeLabel) ?>
                            </span>
                        </td>

                        <td class="text-end">
                            <a href="<?= site_url('demandes/' . $demande['id']) ?>"
                               class="btn btn-sm btn-light border" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?= site_url('demandes/' . $demande['id'] . '/edit') ?>"
                               class="btn btn-sm btn-light border" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        Aucune demande enregistrée.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>