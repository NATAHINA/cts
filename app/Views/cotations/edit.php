<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="lc-card p-4" style="max-width: 640px;">
	<form method="post" action="<?= site_url('cotations/' . $cotation['id']) ?>">
		<?= csrf_field() ?>

		<div class="mb-3">
			<label class="form-label">Client</label>
			<select class="form-select" name="client_id" required>
				<?php foreach ($clients as $c): ?>
					<option value="<?= $c['id'] ?>" <?= ((int) $cotation['client_id'] === (int) $c['id']) ? 'selected' : '' ?>><?= esc($c['nom']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="row">
			<div class="col-md-6 mb-3">
				<label class="form-label">Devise</label>
				<select class="form-select" name="devise_id" required>
					<?php foreach ($devises as $d): ?>
						<option value="<?= $d['id'] ?>" <?= ((int) $cotation['devise_id'] === (int) $d['id']) ? 'selected' : '' ?>><?= esc($d['code']) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-6 mb-3">
				<label class="form-label">Valable jusqu'au</label>
				<input type="date" class="form-control" name="date_validite" value="<?= esc($cotation['date_validite']) ?>">
			</div>
		</div>

		<div class="mb-3">
			<label class="form-label">Notes</label>
			<textarea class="form-control" name="notes" rows="3"><?= esc($cotation['notes']) ?></textarea>
		</div>

		<div class="d-flex gap-2 mt-2">
			<button type="submit" class="btn lc-btn-primary px-4">Enregistrer</button>
			<a href="<?= site_url('cotations/' . $cotation['id']) ?>" class="btn btn-light border">Annuler</a>
		</div>
	</form>
</div>

<?= $this->endSection() ?>
