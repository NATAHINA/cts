<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>
		<?= isset($title) && !empty($title)
			? esc($title) . ' — CPS'
			: 'CPS'
		?>
	</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
		rel="stylesheet"
	>
	<link
		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
		rel="stylesheet"
	>
	<link
		href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
		rel="stylesheet"
	>
	<link
		rel="icon"
		type="image/x-icon"
		href="https://img.icons8.com/color/48/airplane-take-off.png"
	>
	
	<link rel="stylesheet"
      href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

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

			--lc-sidebar-w:    260px;
			--lc-topbar-h:     64px;
		}
		*{
			box-sizing: border-box;
		}

		html,
		body{
			min-height: 100%;
		}

		body{
			margin: 0;
			font-family:
				"Plus Jakarta Sans",
				-apple-system,
				BlinkMacSystemFont,
				"Segoe UI",
				Roboto,
				sans-serif;

			background: var(--lc-bg);
			color: var(--lc-ink);
			font-size: 15px;
		}

		a{
			text-decoration: none;
		}

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


		/* ==========================================================
		   LOGO / BRAND
		========================================================== */

		.lc-sidebar-brand{
			min-height: var(--lc-topbar-h);

			display: flex;
			align-items: center;
			gap: 11px;

			padding: 14px 20px;

			border-bottom: 1px solid var(--lc-line);

			font-weight: 800;
			font-size: 1.10rem;

			color: var(--lc-ink);
		}

		.lc-logo-dot{
			width: 34px;
			height: 34px;

			border-radius: 10px;

			background: var(--lc-accent);
			color: #fff;

			display: flex;
			align-items: center;
			justify-content: center;

			font-size: 1rem;

			flex: 0 0 auto;

			box-shadow: 0 4px 12px rgba(76,95,213,0.25);
		}


		/* ==========================================================
		   NAVIGATION
		========================================================== */

		.lc-nav{
			padding: 12px;

			overflow-y: auto;
			overflow-x: hidden;

			flex: 1;

			scrollbar-width: thin;
		}


		.lc-nav-section{
			font-size: 0.68rem;

			font-weight: 800;

			letter-spacing: 0.09em;

			text-transform: uppercase;

			color: var(--lc-ink-soft);

			margin: 22px 10px 8px;
		}

		.lc-nav-section:first-child{
			margin-top: 8px;
		}


		/* ==========================================================
		   NAVIGATION LINK
		========================================================== */

		.lc-nav-link{
			display: flex;

			align-items: center;

			gap: 11px;

			padding: 10px 12px;

			border-radius: 9px;

			color: var(--lc-ink-soft);

			font-weight: 600;

			font-size: 0.90rem;

			margin-bottom: 3px;

			transition:
				background 0.18s ease,
				color 0.18s ease,
				transform 0.18s ease;
		}

		.lc-nav-link i{
			width: 20px;

			text-align: center;

			font-size: 1rem;

			flex: 0 0 auto;
		}


		/* Hover */

		.lc-nav-link:hover{
			background: var(--lc-accent-soft);

			color: var(--lc-accent-ink);
		}


		/* Active */

		.lc-nav-link.active{
			background: var(--lc-accent);

			color: #fff;

			box-shadow:
				0 4px 12px rgba(76,95,213,0.18);
		}


		/* ==========================================================
		   SIDEBAR FOOTER
		========================================================== */

		.lc-sidebar-foot{
			padding: 14px 16px;

			border-top: 1px solid var(--lc-line);

			font-size: 0.82rem;

			color: var(--lc-ink-soft);

			background: #fff;
		}

		.lc-tenant-info{
			display: flex;

			align-items: center;

			gap: 9px;
		}

		.lc-tenant-icon{
			width: 30px;
			height: 30px;

			border-radius: 8px;

			background: var(--lc-accent-soft);

			color: var(--lc-accent);

			display: flex;

			align-items: center;

			justify-content: center;
		}

		.lc-tenant-name{
			font-weight: 700;

			color: var(--lc-ink);

			white-space: nowrap;

			overflow: hidden;

			text-overflow: ellipsis;
		}


		/* ==========================================================
		   MAIN AREA
		========================================================== */

		.lc-main{
			margin-left: var(--lc-sidebar-w);

			min-height: 100vh;

			display: flex;

			flex-direction: column;
		}


		/* ==========================================================
		   TOPBAR
		========================================================== */

		.lc-topbar{
			min-height: var(--lc-topbar-h);

			background: #fff;

			border-bottom: 1px solid var(--lc-line);

			padding: 10px 22px;

			display: flex;

			align-items: center;

			justify-content: space-between;

			gap: 15px;

			position: sticky;

			top: 0;

			z-index: 1020;
		}


		.lc-topbar h1{
			font-size: 1.05rem;

			font-weight: 700;

			margin: 0;

			color: var(--lc-ink);
		}


		/* ==========================================================
		   BURGER
		========================================================== */

		.lc-burger{
			display: none;

			border: 1px solid var(--lc-line);

			background: #fff;

			border-radius: 8px;

			width: 40px;
			height: 40px;

			align-items: center;
			justify-content: center;

			font-size: 1.15rem;

			color: var(--lc-ink);
		}

		.lc-burger:hover{
			background: var(--lc-bg);
		}


		/* ==========================================================
		   USER BUTTON
		========================================================== */

		.lc-user-btn{
			border: 1px solid var(--lc-line);

			background: #fff;

			border-radius: 9px;

			padding: 7px 11px;

			display: flex;

			align-items: center;

			gap: 8px;

			font-size: 0.88rem;

			font-weight: 600;

			color: var(--lc-ink);
		}

		.lc-user-btn:hover{
			background: var(--lc-bg);
		}

		.lc-user-avatar{
			font-size: 1.25rem;

			color: var(--lc-accent);
		}


		/* ==========================================================
		   CONTENT
		========================================================== */

		.lc-content{
			padding: 24px;

			flex: 1;
		}


		/* ==========================================================
		   SHARED CARD
		========================================================== */

		.lc-card{
			background: #fff;

			border: 1px solid var(--lc-line);

			border-radius: 14px;

			box-shadow:
				0 1px 3px rgba(28,30,43,0.05);
		}


		/* ==========================================================
		   BUTTON
		========================================================== */

		.lc-btn-primary{
			background: var(--lc-accent);

			border-color: var(--lc-accent);

			color: #fff;

			font-weight: 600;

			border-radius: 8px;
		}

		.lc-btn-primary:hover,
		.lc-btn-primary:focus{
			background: var(--lc-accent-ink);

			border-color: var(--lc-accent-ink);

			color: #fff;
		}


		/* ==========================================================
		   FORM
		========================================================== */

		.form-label{
			font-size: 0.85rem;

			font-weight: 600;

			color: var(--lc-ink-soft);
		}

		.form-control,
		.form-select{
			font-size: 0.90rem;

			border-color: var(--lc-line);
		}

		.form-control:focus,
		.form-select:focus{
			border-color: var(--lc-accent);

			box-shadow:
				0 0 0 0.20rem rgba(76,95,213,0.12);
		}


		/* ==========================================================
		   TABLE
		========================================================== */

		.table{
			font-size: 0.88rem;
		}

		.table > :not(caption) > * > *{
			padding: 0.85rem 0.75rem;
		}


		/* ==========================================================
		   BADGES
		========================================================== */

		.lc-badge-actif{
			background: rgba(47,158,110,0.12);

			color: #227a55;

			font-weight: 700;
		}

		.lc-badge-inactif{
			background: rgba(109,106,120,0.12);

			color: var(--lc-ink-soft);

			font-weight: 700;
		}


		/* ==========================================================
		   OVERLAY MOBILE
		========================================================== */

		.lc-overlay{
			display: none;

			position: fixed;

			inset: 0;

			background: rgba(28,30,43,0.40);

			z-index: 1030;

			backdrop-filter: blur(2px);
		}


		/* ==========================================================
		   RESPONSIVE
		========================================================== */

		@media (max-width: 991.98px){

			.lc-sidebar{
				transform: translateX(-100%);
			}

			body.lc-sidebar-open .lc-sidebar{
				transform: translateX(0);
			}

			body.lc-sidebar-open .lc-overlay{
				display: block;
			}

			.lc-main{
				margin-left: 0;
			}

			.lc-burger{
				display: inline-flex;
			}

			.lc-content{
				padding: 18px;
			}

		}


		@media (max-width: 575.98px){

			.lc-topbar{
				padding: 10px 14px;
			}

			.lc-content{
				padding: 14px;
			}

			.lc-topbar h1{
				font-size: 0.95rem;
			}

		}

	</style>

	<?= $this->renderSection('styles') ?>

</head>

<body>

<?php
	$currentUri = trim(uri_string(), '/');

	$userNom = session('user_nom') ?? 'Utilisateur';
	$tenantNom = session('tenant_nom') ?? 'CPS';
?>

<!-- ==========================================================
	 OVERLAY MOBILE
========================================================== -->

<div class="lc-overlay" id="lcOverlay"></div>


<!-- ==========================================================
	 SIDEBAR
========================================================== -->

<aside class="lc-sidebar" id="lcSidebar">


	<!-- LOGO -->

	<div class="lc-sidebar-brand">

		<span class="lc-logo-dot">
			<i class="bi bi-airplane-fill"></i>
		</span>

		<span>CPS</span>

	</div>


	<!-- NAVIGATION -->

<nav class="lc-nav">

    <?php
    $menu = function_exists('get_filtered_app_menu')
        ? get_filtered_app_menu()
        : [];

    $currentUri = trim(uri_string(), '/');
    ?>

    <?php foreach ($menu as $item): ?>

        <?php if (($item['type'] ?? '') === 'section'): ?>

            <div class="lc-nav-section">
                <?= esc($item['label']) ?>
            </div>

        <?php elseif (($item['type'] ?? '') === 'link'): ?>

            <?php
            $isActive = false;

            foreach ($item['match'] ?? [] as $m) {

                if ($m === '' && $currentUri === '') {
                    $isActive = true;
                    break;
                }

                if (
                    $m !== '' &&
                    (
                        $currentUri === $m ||
                        str_starts_with($currentUri, $m . '/')
                    )
                ) {
                    $isActive = true;
                    break;
                }
            }
            ?>

            <a
                href="<?= site_url($item['url']) ?>"
                class="lc-nav-link <?= $isActive ? 'active' : '' ?>"
            >
                <i class="bi <?= esc($item['icon']) ?>"></i>

                <span>
                    <?= esc($item['label']) ?>
                </span>
            </a>

        <?php endif; ?>

    <?php endforeach; ?>

</nav>


	<!-- ==========================================================
		 SIDEBAR FOOTER
	========================================================== -->

	<div class="lc-sidebar-foot">

		<div class="lc-tenant-info">

			<div class="lc-tenant-icon">
				<i class="bi bi-buildings"></i>
			</div>

			<div
				class="lc-tenant-name"
				title="<?= esc($tenantNom) ?>"
			>
				<?= esc($tenantNom) ?>
			</div>

		</div>

	</div>


</aside>


<!-- ==========================================================
	 MAIN
========================================================== -->

<div class="lc-main">


	<!-- ==========================================================
		 TOPBAR
	========================================================== -->

	<header class="lc-topbar">


		<!-- LEFT -->

		<div class="d-flex align-items-center gap-3">

			<button
				class="lc-burger"
				id="lcBurger"
				type="button"
				aria-label="Ouvrir le menu"
			>
				<i class="bi bi-list"></i>
			</button>


			<h1>
				<?= isset($title) && !empty($title)
					? esc($title)
					: 'Tableau de bord'
				?>
			</h1>

		</div>


		<!-- USER -->

		<div class="dropdown">

			<button
				class="lc-user-btn"
				type="button"
				data-bs-toggle="dropdown"
				aria-expanded="false"
			>

				<i class="bi bi-person-circle lc-user-avatar"></i>

				<span class="d-none d-sm-inline">
					<?= esc($userNom) ?>
				</span>

				<i class="bi bi-chevron-down small"></i>

			</button>


			<ul class="dropdown-menu dropdown-menu-end shadow-sm">

				<li>

					<h6 class="dropdown-header">
						<?= esc($userNom) ?>
					</h6>

				</li>

				<?php if (can('parametres.view')): ?>
				<li>
					<a class="dropdown-item" href="<?= site_url('parametres') ?>">
						<i class="bi bi-gear me-2"></i>
						Paramètres
					</a>
				</li>
				<?php endif; ?>

				<li>

					<hr class="dropdown-divider">

				</li>


				<li>

					<a
						class="dropdown-item text-danger"
						href="<?= site_url('logout') ?>"
					>

						<i class="bi bi-box-arrow-right me-2"></i>
						Déconnexion

					</a>

				</li>

			</ul>

		</div>


	</header>


	<!-- ==========================================================
		 PAGE CONTENT
	========================================================== -->

	<main class="lc-content">


		<!-- SUCCESS MESSAGE -->

		<?php if (session()->getFlashdata('success')): ?>

			<div
				class="alert alert-success alert-dismissible fade show"
				role="alert"
			>

				<i class="bi bi-check-circle me-2"></i>

				<?= esc(session()->getFlashdata('success')) ?>

				<button
					type="button"
					class="btn-close"
					data-bs-dismiss="alert"
				></button>

			</div>

		<?php endif; ?>


		<!-- ERROR MESSAGE -->

		<?php if (session()->getFlashdata('error')): ?>

			<div
				class="alert alert-danger alert-dismissible fade show"
				role="alert"
			>

				<i class="bi bi-exclamation-triangle me-2"></i>

				<?= esc(session()->getFlashdata('error')) ?>

				<button
					type="button"
					class="btn-close"
					data-bs-dismiss="alert"
				></button>

			</div>

		<?php endif; ?>


		<!-- VALIDATION ERRORS -->

		<?php if (session()->getFlashdata('errors')): ?>

			<div class="alert alert-danger">

				<strong>
					<i class="bi bi-exclamation-triangle me-2"></i>
					Veuillez corriger les erreurs suivantes :
				</strong>

				<ul class="mb-0 mt-2">

					<?php foreach (session()->getFlashdata('errors') as $error): ?>

						<li><?= esc($error) ?></li>

					<?php endforeach; ?>

				</ul>

			</div>

		<?php endif; ?>


		<!-- CONTENT -->

		<?= $this->renderSection('content') ?>


	</main>


</div>


<!-- ==========================================================
	 BOOTSTRAP JAVASCRIPT
========================================================== -->

<script
	src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<!-- ==========================================================
	 SIDEBAR MOBILE JAVASCRIPT
========================================================== -->

<script>

	document.addEventListener('DOMContentLoaded', function () {

		const lcBurger  = document.getElementById('lcBurger');
		const lcOverlay = document.getElementById('lcOverlay');


		/**
		 * Ouvrir le sidebar mobile
		 */
		function openSidebar() {
			document.body.classList.add('lc-sidebar-open');
		}


		/**
		 * Fermer le sidebar mobile
		 */
		function closeSidebar() {
			document.body.classList.remove('lc-sidebar-open');
		}


		/**
		 * Bouton menu
		 */
		if (lcBurger) {

			lcBurger.addEventListener('click', function () {

				if (document.body.classList.contains('lc-sidebar-open')) {
					closeSidebar();
				} else {
					openSidebar();
				}

			});

		}


		/**
		 * Click sur overlay
		 */
		if (lcOverlay) {

			lcOverlay.addEventListener('click', function () {
				closeSidebar();
			});

		}


		/**
		 * Fermer avec Escape
		 */
		document.addEventListener('keydown', function (event) {

			if (event.key === 'Escape') {
				closeSidebar();
			}

		});


		/**
		 * Fermer le menu après clic sur un lien
		 * uniquement sur mobile
		 */
		document.querySelectorAll('.lc-nav-link').forEach(function (link) {

			link.addEventListener('click', function () {

				if (window.innerWidth < 992) {
					closeSidebar();
				}

			});

		});


		/**
		 * Si l'écran devient desktop,
		 * suppression de l'état mobile.
		 */
		window.addEventListener('resize', function () {

			if (window.innerWidth >= 992) {
				closeSidebar();
			}

		});

	});

</script>


<!-- Scripts spécifiques à chaque page -->

<?= $this->renderSection('scripts') ?>


</body>
</html>