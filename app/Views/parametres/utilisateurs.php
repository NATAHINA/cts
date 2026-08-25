<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3">
	<div class="col-lg-7">
		<div class="lc-card p-3">
			<h3 class="h6 fw-bold mb-3 px-2 pt-1">Membres de l'agence</h3>
			<div class="table-responsive">
				<table class="table align-middle mb-0">
					<thead>
						<tr class="text-secondary small">
							<th>Nom</th>
							<th>Email</th>
							<th>Rôle</th>
							<th>Statut</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($users as $u): ?>
							<tr>
								<td><?= esc(trim($u['prenom'] . ' ' . $u['nom'])) ?></td>
								<td><?= esc($u['email']) ?></td>
								<td class="text-capitalize"><?= esc($u['role']) ?></td>
								<td><span class="badge rounded-pill <?= $u['statut'] === 'actif' ? 'lc-badge-actif' : 'lc-badge-inactif' ?>"><?= esc($u['statut']) ?></span></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-lg-5">
		<div class="lc-card p-4">
			<h3 class="h6 fw-bold mb-3">Ajouter un membre</h3>
			<form method="post" action="<?= site_url('parametres/utilisateurs') ?>">
				<?= csrf_field() ?>
				<div class="row">
					<div class="col-6 mb-3">
						<label class="form-label">Prénom</label>
						<input type="text" class="form-control" name="prenom">
					</div>
					<div class="col-6 mb-3">
						<label class="form-label">Nom</label>
						<input type="text" class="form-control" name="nom" required>
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label">Email</label>
					<input type="email" class="form-control" name="email" required>
				</div>
				<div class="mb-3">
					<label class="form-label">Mot de passe temporaire</label>
					<input type="password" class="form-control" name="password" minlength="6" required>
				</div>
				<div class="mb-3">
					<label class="form-label">Rôle</label>
					<select class="form-select" name="role">
						<option value="agent">Agent</option>
						<option value="admin">Administrateur</option>
					</select>
				</div>
				<button type="submit" class="btn lc-btn-primary w-100">Ajouter</button>
			</form>
		</div>
	</div>
</div>

<?= $this->endSection() ?>
