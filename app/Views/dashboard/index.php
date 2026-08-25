<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3 mb-4">
	<div class="col-6 col-lg-3">
		<div class="lc-card p-3 h-100">
			<div class="text-secondary small fw-semibold mb-1">Cotations (total)</div>
			<div class="fs-3 fw-bold"><?= (int) $nbCotations ?></div>
		</div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="lc-card p-3 h-100">
			<div class="text-secondary small fw-semibold mb-1">Cotations ce mois</div>
			<div class="fs-3 fw-bold"><?= (int) $nbCotationsMois ?></div>
		</div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="lc-card p-3 h-100">
			<div class="text-secondary small fw-semibold mb-1">Clients</div>
			<div class="fs-3 fw-bold"><?= (int) $nbClients ?></div>
		</div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="lc-card p-3 h-100">
			<div class="text-secondary small fw-semibold mb-1">Montant accepté</div>
			<div class="fs-3 fw-bold"><?= number_format((float) $montantAccepte, 0, ',', ' ') ?></div>
		</div>
	</div>
</div>

<?php
	$statutLabels = [
		'brouillon' => 'Brouillons', 'envoyee' => 'Envoyées', 'acceptee' => 'Acceptées',
		'refusee' => 'Refusées', 'expiree' => 'Expirées',
	];
	$statutClasses = [
		'brouillon' => 'lc-badge-inactif', 'envoyee' => 'lc-badge-actif', 'acceptee' => 'lc-badge-actif',
		'refusee' => 'bg-danger-subtle text-danger', 'expiree' => 'bg-warning-subtle text-warning-emphasis',
	];
?>

<div class="lc-card p-3 mb-3">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h2 class="h6 fw-bold mb-0">Répartition des cotations</h2>
		<a href="<?= site_url('cotations') ?>" class="small">Gérer les cotations</a>
	</div>
	<div class="row g-2">
		<?php foreach ($statutLabels as $statut => $label): ?>
			<div class="col-6 col-md">
				<div class="border rounded-3 p-2 h-100">
					<div class="small text-secondary"><?= esc($label) ?></div>
					<div class="d-flex align-items-center gap-2 mt-1">
						<span class="badge rounded-pill <?= esc($statutClasses[$statut]) ?>"><?= (int) ($cotationsParStatut[$statut] ?? 0) ?></span>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<div class="lc-card p-3">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h2 class="h6 fw-bold mb-0">Dernières cotations</h2>
		<a href="<?= site_url('cotations') ?>" class="small">Voir tout</a>
	</div>

	<div class="table-responsive">
		<table class="table align-middle mb-0">
			<thead>
				<tr class="text-secondary small">
					<th>Référence</th>
					<th>Statut</th>
					<th>Client</th>
					<th>Montant</th>
					<th>Créée le</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($dernieresCotations)): ?>
					<tr><td colspan="6" class="text-secondary text-center py-4">Aucune cotation pour le moment.</td></tr>
				<?php endif; ?>
				<?php foreach ((array) $dernieresCotations as $c): ?>
					<tr>
						<td class="fw-semibold"><?= esc($c['reference']) ?></td>
						<td><span class="badge rounded-pill <?= esc($statutClasses[$c['statut']] ?? 'lc-badge-inactif') ?>"><?= esc($statutLabels[$c['statut']] ?? $c['statut']) ?></span></td>
						<td><?= esc($c['client_nom'] ?? 'Client non renseigné') ?></td>
						<td><?= number_format((float) $c['montant_total'], 0, ',', ' ') ?></td>
						<td><?= ! empty($c['created_at']) ? esc(date('d/m/Y', strtotime($c['created_at']))) : '—' ?></td>
						<td class="text-end"><a href="<?= site_url('cotations/' . $c['id']) ?>" class="btn btn-sm btn-light border">Voir</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?= $this->endSection() ?>
