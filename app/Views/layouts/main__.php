<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title><?= isset($title) ? esc($title) . ' — Cts' : 'Cts' ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

	<link rel="icon" type="image/x-icon" href="https://img.icons8.com/color/48/airplane-take-off.ico">

	<style>
		:root{
			--lc-accent:       #4C5FD5;
			--lc-accent-light: #7C89E8;
			--lc-accent-ink:   #37419E;
			--lc-accent-soft:  rgba(76,95,213,0.10);
			--lc-ink:          #1c1e2b;
			--lc-ink-soft:     #6b6d7c;
			--lc-line:         #e6e6ee;
			--lc-bg:           #f6f6fb;
			--lc-sidebar-w:    252px;
		}

		*{ box-sizing: border-box; }

		body{
			font-family: "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
			background: var(--lc-bg);
			color: var(--lc-ink);
			font-size: 15px;
		}

		a{ text-decoration: none; }

		/* ---------- sidebar ---------- */

		.lc-sidebar{
			width: var(--lc-sidebar-w);
			background: #fff;
			border-right: 1px solid var(--lc-line);
			position: fixed;
			top: 0;
			bottom: 0;
			left: 0;
			display: flex;
			flex-direction: column;
			z-index: 1040;
			transition: transform 0.25s ease;
		}

		.lc-sidebar-brand{
			display: flex;
			align-items: center;
			gap: 10px;
			padding: 18px 20px;
			border-bottom: 1px solid var(--lc-line);
			font-weight: 800;
			font-size: 1.15rem;
			color: var(--lc-ink);
		}

		.lc-sidebar-brand .lc-logo-dot{
			width: 30px; height: 30px; border-radius: 9px;
			background: var(--lc-accent);
			color: #fff;
			display: flex; align-items: center; justify-content: center;
			font-size: 1rem;
			flex: 0 0 auto;
		}

		.lc-nav{ padding: 14px 12px; overflow-y: auto; flex: 1; }

		.lc-nav-section{
			font-size: 0.72rem;
			font-weight: 700;
			letter-spacing: 0.08em;
			text-transform: uppercase;
			color: var(--lc-ink-soft);
			margin: 16px 10px 6px;
		}

		.lc-nav-link{
			display: flex;
			align-items: center;
			gap: 10px;
			padding: 9px 12px;
			border-radius: 9px;
			color: var(--lc-ink-soft);
			font-weight: 600;
			font-size: 0.92rem;
			margin-bottom: 2px;
		}

		.lc-nav-link i{ font-size: 1rem; width: 18px; text-align: center; }

		.lc-nav-link:hover{ background: var(--lc-accent-soft); color: var(--lc-accent-ink); }

		.lc-nav-link.active{
			background: var(--lc-accent);
			color: #fff;
		}

		.lc-sidebar-foot{
			padding: 14px 16px;
			border-top: 1px solid var(--lc-line);
			font-size: 0.85rem;
			color: var(--lc-ink-soft);
		}

		/* ---------- main area ---------- */

		.lc-main{
			margin-left: var(--lc-sidebar-w);
			min-height: 100vh;
			display: flex;
			flex-direction: column;
		}

		.lc-topbar{
			background: #fff;
			border-bottom: 1px solid var(--lc-line);
			padding: 12px 20px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 12px;
			position: sticky;
			top: 0;
			z-index: 1020;
		}

		.lc-topbar h1{
			font-size: 1.05rem;
			font-weight: 700;
			margin: 0;
		}

		.lc-burger{
			display: none;
			border: 1px solid var(--lc-line);
			background: #fff;
			border-radius: 8px;
			width: 38px; height: 38px;
			align-items: center; justify-content: center;
			font-size: 1.1rem;
			color: var(--lc-ink);
		}

		.lc-content{
			padding: 22px;
			flex: 1;
		}

		/* ---------- shared UI helpers ---------- */

		.lc-card{
			background: #fff;
			border: 1px solid var(--lc-line);
			border-radius: 14px;
			box-shadow: 0 1px 3px rgba(28,30,43,0.05);
		}

		.lc-btn-primary{
			background: var(--lc-accent);
			border-color: var(--lc-accent);
			color: #fff;
			font-weight: 600;
			border-radius: 8px;
		}
		.lc-btn-primary:hover{ background: var(--lc-accent-ink); border-color: var(--lc-accent-ink); color: #fff; }

		.table{ font-size: 14px; }
		.form-label{ font-size: 14px; font-weight: 600; color: var(--lc-ink-soft); }
		.form-control, .form-select{ font-size: 14px; }

		.lc-badge-actif{ background: rgba(47,158,110,0.12); color: #227a55; font-weight: 600; }
		.lc-badge-inactif{ background: rgba(109,106,120,0.12); color: var(--lc-ink-soft); font-weight: 600; }

		.lc-overlay{
			display: none;
			position: fixed; inset: 0;
			background: rgba(28,30,43,0.35);
			z-index: 1030;
		}

		@media (max-width: 991.98px){
			.lc-sidebar{ transform: translateX(-100%); }
			body.lc-sidebar-open .lc-sidebar{ transform: translateX(0); }
			body.lc-sidebar-open .lc-overlay{ display: block; }
			.lc-main{ margin-left: 0; }
			.lc-burger{ display: inline-flex; }
		}
	</style>

	<?= $this->renderSection('styles') ?>
</head>
<body>

	<div class="lc-overlay" id="lcOverlay"></div>

	<aside class="lc-sidebar" id="lcSidebar">
		<div class="lc-sidebar-brand">
			<span class="lc-logo-dot"><i class="bi bi-airplane-fill"></i></span>
			Lamina Cotation
		</div>

		<nav class="lc-nav">
			<a href="<?= site_url('/') ?>" class="lc-nav-link <?= (uri_string() === '' || uri_string() === 'dashboard') ? 'active' : '' ?>">
				<i class="bi bi-speedometer2"></i> Tableau de bord
			</a>
			<a href="<?= site_url('cotations') ?>" class="lc-nav-link <?= str_starts_with(uri_string(), 'cotations') ? 'active' : '' ?>">
				<i class="bi bi-file-earmark-text"></i> Cotations
			</a>
			<a href="<?= site_url('clients') ?>" class="lc-nav-link <?= str_starts_with(uri_string(), 'clients') ? 'active' : '' ?>">
				<i class="bi bi-people"></i> Clients
			</a>

			<div class="lc-nav-section">Catalogue</div>
			<a href="<?= site_url('destinations') ?>" class="lc-nav-link <?= str_starts_with(uri_string(), 'destinations') ? 'active' : '' ?>">
				<i class="bi bi-geo-alt"></i> Destinations
			</a>
			<a href="<?= site_url('hotels') ?>" class="lc-nav-link <?= str_starts_with(uri_string(), 'hotels') ? 'active' : '' ?>">
				<i class="bi bi-building"></i> Hôtels
			</a>
			<a href="<?= site_url('vols') ?>" class="lc-nav-link <?= str_starts_with(uri_string(), 'vols') ? 'active' : '' ?>">
				<i class="bi bi-airplane"></i> Vols
			</a>
			<a href="<?= site_url('excursions') ?>" class="lc-nav-link <?= str_starts_with(uri_string(), 'excursions') ? 'active' : '' ?>">
				<i class="bi bi-binoculars"></i> Excursions
			</a>
			<a href="<?= site_url('transferts') ?>" class="lc-nav-link <?= str_starts_with(uri_string(), 'transferts') ? 'active' : '' ?>">
				<i class="bi bi-taxi-front"></i> Transferts
			</a>
			<a href="<?= site_url('croisieres') ?>" class="lc-nav-link <?= str_starts_with(uri_string(), 'croisieres') ? 'active' : '' ?>">
				<i class="bi bi-water"></i> Croisières
			</a>
			<a href="<?= site_url('forfaits') ?>" class="lc-nav-link <?= str_starts_with(uri_string(), 'forfaits') ? 'active' : '' ?>">
				<i class="bi bi-box-seam"></i> Forfaits
			</a>
			<a href="<?= site_url('circuits') ?>" class="lc-nav-link <?= str_starts_with(uri_string(), 'circuits') ? 'active' : '' ?>">
				<i class="bi bi-signpost-split"></i> Circuits
			</a>

			<div class="lc-nav-section">Compte</div>
			<a href="<?= site_url('parametres') ?>" class="lc-nav-link <?= str_starts_with(uri_string(), 'parametres') ? 'active' : '' ?>">
				<i class="bi bi-gear"></i> Paramètres
			</a>
		</nav>

		<div class="lc-sidebar-foot">
			<?= esc(session('tenant_nom')) ?>
		</div>
	</aside>

	<div class="lc-main">
		<header class="lc-topbar">
			<div class="d-flex align-items-center gap-3">
				<button class="lc-burger" id="lcBurger" type="button" aria-label="Ouvrir le menu">
					<i class="bi bi-list"></i>
				</button>
				<h1><?= isset($title) ? esc($title) : 'Tableau de bord' ?></h1>
			</div>

			<div class="dropdown">
				<button class="btn btn-light border d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
					<i class="bi bi-person-circle"></i>
					<span class="d-none d-sm-inline"><?= esc(session('user_nom')) ?></span>
				</button>
				<ul class="dropdown-menu dropdown-menu-end">
					<li><a class="dropdown-item" href="<?= site_url('parametres') ?>"><i class="bi bi-gear me-2"></i>Paramètres</a></li>
					<li><hr class="dropdown-divider"></li>
					<li><a class="dropdown-item text-danger" href="<?= site_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
				</ul>
			</div>
		</header>

		<main class="lc-content">
			<?php if (session()->getFlashdata('success')): ?>
				<div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
			<?php endif; ?>
			<?php if (session()->getFlashdata('error')): ?>
				<div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
			<?php endif; ?>

			<?= $this->renderSection('content') ?>
		</main>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		const lcBurger = document.getElementById('lcBurger');
		const lcOverlay = document.getElementById('lcOverlay');
		if (lcBurger) {
			lcBurger.addEventListener('click', () => document.body.classList.add('lc-sidebar-open'));
			lcOverlay.addEventListener('click', () => document.body.classList.remove('lc-sidebar-open'));
		}
	</script>

	<?= $this->renderSection('scripts') ?>
</body>
</html>
