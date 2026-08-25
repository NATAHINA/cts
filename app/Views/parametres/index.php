<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3">
	<div class="col-lg-6">
		<div class="lc-card p-4">
			<h3 class="h6 fw-bold mb-3">Mon agence</h3>
			<dl class="row mb-0 small">
				<dt class="col-5 text-secondary fw-normal">Nom</dt>
				<dd class="col-7"><?= esc($tenant['nom_agence']) ?></dd>
				<dt class="col-5 text-secondary fw-normal">Email de contact</dt>
				<dd class="col-7"><?= esc($tenant['email_contact']) ?></dd>
				<dt class="col-5 text-secondary fw-normal">Plan</dt>
				<dd class="col-7 text-capitalize"><?= esc($tenant['plan']) ?></dd>
				<dt class="col-5 text-secondary fw-normal">Statut</dt>
				<dd class="col-7"><span class="badge rounded-pill lc-badge-actif text-capitalize"><?= esc($tenant['statut']) ?></span></dd>
			</dl>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="lc-card p-4">
			<h3 class="h6 fw-bold mb-3">Équipe</h3>
			<p class="small text-secondary">Gérez les comptes des membres de votre agence (agents, administrateurs).</p>
			<a href="<?= site_url('parametres/utilisateurs') ?>" class="btn lc-btn-primary btn-sm">Voir les utilisateurs</a>
		</div>
	</div>
</div>

<?= $this->endSection() ?>
