<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
	<div></div>
	<a href="<?= site_url('cotations/new') ?>" class="btn lc-btn-primary"><i class="bi bi-plus-lg me-1"></i> Nouvelle cotation</a>
</div>

<div class="lc-card p-3">
	<div class="table-responsive">
		<table class="table align-middle mb-0">
			<thead>
				<tr class="text-secondary small">
					<th>Référence</th>
					<th>Client</th>
					<th>Statut</th>
					<th>Montant</th>
					<th>Créée le</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($items)): ?>
					<tr><td colspan="6" class="text-secondary text-center py-4">Aucune cotation pour le moment.</td></tr>
				<?php endif; ?>
				<?php foreach ($items as $it): ?>
					<?php
						$badgeMap = [
							'brouillon' => 'lc-badge-inactif',
							'envoyee'   => 'lc-badge-actif',
							'acceptee'  => 'lc-badge-actif',
							'refusee'   => 'lc-badge-inactif',
							'expiree'   => 'lc-badge-inactif',
						];
					?>
					<tr>
						<td class="fw-semibold"><a href="<?= site_url('cotations/' . $it['id']) ?>"><?= esc($it['reference']) ?></a></td>
						<td><?= esc($it['client_nom'] ?? '—') ?></td>
						<td><span class="badge rounded-pill <?= $badgeMap[$it['statut']] ?? 'lc-badge-inactif' ?>"><?= esc($it['statut']) ?></span></td>
						<td><?= number_format((float) $it['montant_total'], 0, ',', ' ') ?></td>
						<td><?= esc(date('d/m/Y', strtotime($it['created_at']))) ?></td>
						<td class="text-end">
							<a href="<?= site_url('cotations/' . $it['id']) ?>" class="btn btn-sm btn-light border">Voir</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?= $this->endSection() ?>
