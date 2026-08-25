<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
	$statutLabels = [
		'brouillon' => 'Brouillon', 'envoyee' => 'Envoyée', 'acceptee' => 'Acceptée',
		'refusee' => 'Refusée', 'expiree' => 'Expirée',
	];
	$typeLabels = [
		'hotel' => 'Hôtel', 'vol' => 'Vol', 'excursion' => 'Excursion', 'transfert' => 'Transfert',
		'croisiere' => 'Croisière', 'forfait' => 'Forfait', 'circuit' => 'Circuit', 'autre' => 'Autre',
	];
?>

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
	<div>
		<div class="d-flex align-items-center gap-2">
			<h2 class="h5 fw-bold mb-0"><?= esc($cotation['reference']) ?></h2>
			<span class="badge rounded-pill lc-badge-actif"><?= esc($statutLabels[$cotation['statut']] ?? $cotation['statut']) ?></span>
		</div>
		<div class="text-secondary small mt-1">
			Client : <strong><?= esc($client['nom'] ?? '—') ?></strong>
			<?php if (! empty($cotation['date_validite'])): ?>
				· Valable jusqu'au <?= esc(date('d/m/Y', strtotime($cotation['date_validite']))) ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="d-flex gap-2 flex-wrap">
		<form method="post" action="<?= site_url('cotations/' . $cotation['id'] . '/statut') ?>" class="d-flex gap-2">
			<?= csrf_field() ?>
			<select class="form-select form-select-sm" name="statut" onchange="this.form.submit()">
				<?php foreach ($statutLabels as $val => $lbl): ?>
					<option value="<?= $val ?>" <?= $cotation['statut'] === $val ? 'selected' : '' ?>><?= $lbl ?></option>
				<?php endforeach; ?>
			</select>
		</form>
		<a href="<?= site_url('cotations/' . $cotation['id'] . '/edit') ?>" class="btn btn-sm btn-light border"><i class="bi bi-pencil"></i> Modifier</a>
		<a href="<?= site_url('cotations/' . $cotation['id'] . '/print') ?>" target="_blank" rel="noopener" class="btn btn-sm btn-light border"><i class="bi bi-printer"></i> Imprimer</a>
	</div>
</div>

<div class="row g-3">
	<div class="col-lg-8">
		<div class="lc-card p-3 mb-3">
			<h3 class="h6 fw-bold mb-3">Prestations</h3>

			<div class="table-responsive">
				<table class="table align-middle mb-0">
					<thead>
						<tr class="text-secondary small">
							<th>Type</th>
							<th>Libellé</th>
							<th>Dates</th>
							<th class="text-end">Qté</th>
							<th class="text-end">P.U.</th>
							<th class="text-end">Montant</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($lignes)): ?>
							<tr><td colspan="7" class="text-secondary text-center py-4">Aucune prestation ajoutée pour l'instant.</td></tr>
						<?php endif; ?>
						<?php foreach ($lignes as $l): ?>
							<tr>
								<td><span class="badge bg-light text-dark border"><?= esc($typeLabels[$l['type_service']] ?? $l['type_service']) ?></span></td>
								<td><?= esc($l['libelle']) ?></td>
								<td class="small text-secondary">
									<?php if ($l['date_debut']): ?><?= esc(date('d/m/Y', strtotime($l['date_debut']))) ?><?php endif; ?>
									<?php if ($l['date_fin']): ?> → <?= esc(date('d/m/Y', strtotime($l['date_fin']))) ?><?php endif; ?>
								</td>
								<td class="text-end"><?= rtrim(rtrim(number_format((float) $l['quantite'], 2, '.', ''), '0'), '.') ?></td>
								<td class="text-end"><?= number_format((float) $l['prix_unitaire'], 0, ',', ' ') ?></td>
								<td class="text-end fw-semibold"><?= number_format((float) $l['montant'], 0, ',', ' ') ?></td>
								<td class="text-end">
									<a href="<?= site_url('cotations/' . $cotation['id'] . '/lignes/' . $l['id'] . '/delete') ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Retirer cette prestation ?');"><i class="bi bi-trash"></i></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="5" class="text-end">TOTAL</th>
							<th class="text-end"><?= number_format((float) $cotation['montant_total'], 0, ',', ' ') ?></th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<div class="lc-card p-3">
			<h3 class="h6 fw-bold mb-3">Ajouter une prestation</h3>

			<form method="post" action="<?= site_url('cotations/' . $cotation['id'] . '/lignes') ?>" id="ligneForm">
				<?= csrf_field() ?>
				<div class="row g-2 align-items-end">
					<div class="col-md-2">
						<label class="form-label">Type</label>
						<select class="form-select" name="type_service" id="typeService">
							<?php foreach ($typeLabels as $val => $lbl): ?>
								<option value="<?= $val ?>"><?= $lbl ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label class="form-label">Article du catalogue</label>
						<select class="form-select" id="serviceId" name="service_id">
							<option value="">— Saisie libre —</option>
						</select>
					</div>
					<div class="col-md-3">
						<label class="form-label">Libellé</label>
						<input type="text" class="form-control" name="libelle" id="libelle" required>
					</div>
					<div class="col-md-1">
						<label class="form-label">Qté</label>
						<input type="number" step="0.01" min="0.01" class="form-control" name="quantite" id="quantite" value="1">
					</div>
					<div class="col-md-2">
						<label class="form-label">Prix unitaire</label>
						<input type="number" step="0.01" min="0" class="form-control" name="prix_unitaire" id="prixUnitaire" value="0">
					</div>
					<div class="col-md-1">
						<button type="submit" class="btn lc-btn-primary w-100"><i class="bi bi-plus-lg"></i></button>
					</div>
				</div>
				<div class="row g-2 mt-1">
					<div class="col-md-3">
						<label class="form-label">Date début</label>
						<input type="date" class="form-control" name="date_debut">
					</div>
					<div class="col-md-3">
						<label class="form-label">Date fin</label>
						<input type="date" class="form-control" name="date_fin">
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="lc-card p-3">
			<h3 class="h6 fw-bold mb-3">Notes</h3>
			<p class="small text-secondary mb-0"><?= nl2br(esc($cotation['notes'] ?: 'Aucune note.')) ?></p>
		</div>
	</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
	const catalogue = <?= json_encode($catalogue, JSON_UNESCAPED_UNICODE) ?>;

	const typeSelect    = document.getElementById('typeService');
	const serviceSelect = document.getElementById('serviceId');
	const libelleInput  = document.getElementById('libelle');
	const prixInput     = document.getElementById('prixUnitaire');

	function refreshServiceOptions() {
		const type = typeSelect.value;
		serviceSelect.innerHTML = '<option value="">— Saisie libre —</option>';

		const items = catalogue[type] || [];
		items.forEach((item) => {
			const opt = document.createElement('option');
			opt.value = item.id;
			opt.textContent = item.label;
			opt.dataset.prix = item.prix;
			opt.dataset.label = item.label;
			serviceSelect.appendChild(opt);
		});
	}

	typeSelect.addEventListener('change', refreshServiceOptions);

	serviceSelect.addEventListener('change', () => {
		const opt = serviceSelect.options[serviceSelect.selectedIndex];
		if (opt && opt.value) {
			libelleInput.value = opt.dataset.label || '';
			prixInput.value = opt.dataset.prix || 0;
		}
	});

	refreshServiceOptions();
</script>
<?= $this->endSection() ?>
