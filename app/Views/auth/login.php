<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion — CTS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
	<link rel="icon" type="image/x-icon" href="https://img.icons8.com/color/48/airplane-take-off.png">
    <style>
        :root{ --lc-accent:#4C5FD5; --lc-accent-light:#7C89E8; --lc-accent-ink:#37419E; --lc-accent-soft:rgba(76,95,213,0.10); --lc-ink:#1c1e2b; --lc-ink-soft:#6b6d7c; --lc-line:#e6e6ee; }
        *{ box-sizing:border-box; }
        html, body{ height:100%; margin:0; }
        body{
            font-family:"Plus Jakarta Sans",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
            color: var(--lc-ink);
        }

        .auth-split{ display:flex; min-height:100vh; }

        /* Panneau image — masqué sur tablette et mobile, visible seulement à partir du breakpoint lg (≥992px) */
        .auth-image-panel{
            display:none;
            flex:1 1 50%;
            position:relative;
            background:
                linear-gradient(180deg, rgba(28,30,43,0.15) 0%, rgba(28,30,43,0.55) 100%),
                url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=1400&auto=format&fit=crop') center/cover no-repeat;
        }
        @media (min-width: 992px){
            .auth-image-panel{ display:flex; align-items:flex-end; padding:48px; }
        }
        .auth-image-caption{
            color:#fff;
        }
        .auth-image-caption .brand{
            display:flex; align-items:center; gap:10px; font-weight:800; font-size:1.4rem; margin-bottom:14px;
        }
        .auth-image-caption .brand .dot{
            width:38px; height:38px; border-radius:10px; background:rgba(255,255,255,0.16);
            backdrop-filter: blur(4px); display:flex; align-items:center; justify-content:center;
        }
        .auth-image-caption p{ font-size:15px; opacity:0.9; max-width:360px; line-height:1.5; }

        /* Panneau formulaire */
        .auth-form-panel{
            flex:1 1 50%;
            display:flex; align-items:center; justify-content:center;
            background:
                radial-gradient(80% 60% at 15% 10%, var(--lc-accent-soft) 0%, transparent 60%),
                #fbfbfe;
            padding:24px;
        }

        .auth-card{ width:100%; max-width:400px; background:#fff; border:1px solid var(--lc-line); border-radius:18px; box-shadow:0 24px 55px -28px rgba(28,30,43,0.25); padding:34px 30px; }
        .auth-brand{ display:flex; align-items:center; gap:10px; font-weight:800; font-size:1.25rem; margin-bottom:26px; }
        .auth-brand .dot{ width:34px;height:34px;border-radius:10px;background:var(--lc-accent);color:#fff;display:flex;align-items:center;justify-content:center; }
        label{ font-size:14px; font-weight:600; color:var(--lc-ink-soft); }
        .form-control{ font-size:14px; padding:10px 12px; border-radius:9px; }
        .form-control:focus{ border-color:var(--lc-accent); box-shadow:0 0 0 3px var(--lc-accent-soft); }
        .btn-auth{ background:var(--lc-accent); border-color:var(--lc-accent); color:#fff; font-weight:700; border-radius:9px; padding:10px; }
        .btn-auth:hover{ background:var(--lc-accent-ink); border-color:var(--lc-accent-ink); color:#fff; }
        .auth-foot{ text-align:center; font-size:14px; color:var(--lc-ink-soft); margin-top:18px; }
        .auth-foot a{ color:var(--lc-accent-ink); font-weight:700; text-decoration:none; }
        .forgot-link{ font-size:13px; color:var(--lc-accent-ink); text-decoration:none; font-weight:600; }

        /* Toggle mot de passe */
        .password-wrapper{ position:relative; }
        .password-wrapper .form-control{ padding-right:42px; }
        .toggle-password{
            position:absolute;
            right:10px;
            top:50%;
            transform:translateY(-50%);
            background:none;
            border:none;
            color:var(--lc-ink-soft);
            padding:0;
            cursor:pointer;
            font-size:1.15rem;
            line-height:1;
            display:flex;
            align-items:center;
            justify-content:center;
        }
        .toggle-password:hover{ color:var(--lc-accent); }
        .toggle-password:focus{ outline:none; }
        .auth-copyright{
            text-align:center;
            font-size:11px;
            color:#9a9baa;
            margin-top:22px;
            padding-top:14px;
            border-top:1px solid var(--lc-line);
        }

        .auth-copyright strong{
            color:var(--lc-accent-ink);
            font-weight:700;
        }
    </style>
</head>
<body>
    <div class="auth-split">

        <div class="auth-image-panel">
            <div class="auth-image-caption">
                <div class="brand"><span class="dot"><i class="bi bi-airplane-fill"></i></span> CTS</div>
                <p>Gérez vos clients, cotations et catalogues de voyage depuis un seul espace agence.</p>
            </div>
        </div>

        <div class="auth-form-panel">
            <div class="auth-card">
                <div class="auth-brand"><span class="dot"><i class="bi bi-airplane-fill"></i></span> CTS</div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger py-2" style="font-size:14px;"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success py-2" style="font-size:14px;"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('login') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= esc(old('email')) ?>" required>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label mb-0">Mot de passe</label>
                            <a href="<?= site_url('forgot-password') ?>" class="forgot-link m-2">Mot de passe oublié ?</a>
                        </div>
                        <div class="password-wrapper">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button type="button" class="toggle-password" id="togglePassword" aria-label="Afficher le mot de passe">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-auth w-100 mt-3">Se connecter</button>
                </form>

                <div class="auth-foot">
                    Pas encore de compte agence&nbsp;? <a href="<?= site_url('register') ?>">Créer un compte</a>
                </div>

                <div class="auth-copyright">
                    © <?= date('Y') ?> <strong>NR CODE</strong> — Tous droits réservés.
                </div>
            </div>
        </div>

    </div>

    <script>
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleIcon.classList.toggle('bi-eye', !isPassword);
            toggleIcon.classList.toggle('bi-eye-slash', isPassword);
            toggleBtn.setAttribute('aria-label', isPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
        });
    </script>
</body>
</html>