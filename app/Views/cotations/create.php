<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="lc-card p-4" style="max-width: 640px;">
	<form method="post" action="<?= site_url('cotations') ?>">
		<?= csrf_field() ?>

		<div class="mb-3">
			<label class="form-label">Client</label>
			<select class="form-select" name="client_id" required>
				<option value="">Sélectionner un client…</option>
				<?php foreach ($clients as $c): ?>
					<option value="<?= $c['id'] ?>"><?= esc($c['nom']) ?></option>
				<?php endforeach; ?>
			</select>
			<div class="form-text">Pas encore de client ? <a href="<?= site_url('clients/new') ?>">Créez-en un</a> puis revenez ici.</div>
		</div>

		<div class="row">
			<div class="col-md-6 mb-3">
				<label class="form-label">Devise</label>
				<select class="form-select" name="devise_id" required>
					<?php foreach ($devises as $d): ?>
						<option value="<?= $d['id'] ?>"><?= esc($d['code']) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-6 mb-3">
				<label class="form-label">Valable jusqu'au</label>
				<input type="date" class="form-control" name="date_validite">
			</div>
		</div>

		<div class="mb-3">
			<label class="form-label">Notes</label>
			<textarea class="form-control" name="notes" rows="3"></textarea>
		</div>

		<div class="d-flex gap-2 mt-2">
			<button type="submit" class="btn lc-btn-primary px-4">Créer la cotation</button>
			<a href="<?= site_url('cotations') ?>" class="btn btn-light border">Annuler</a>
		</div>
	</form>
</div>

<?= $this->endSection() ?>
