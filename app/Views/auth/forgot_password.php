<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mot de passe oublié — CTS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root{ --lc-accent:#4C5FD5; --lc-accent-ink:#37419E; --lc-accent-soft:rgba(76,95,213,0.10); --lc-ink:#1c1e2b; --lc-ink-soft:#6b6d7c; --lc-line:#e6e6ee; }
        *{ box-sizing:border-box; }
        body{
            font-family:"Plus Jakarta Sans",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
            min-height:100vh; display:flex; align-items:center; justify-content:center;
            background:
                radial-gradient(80% 60% at 15% 10%, var(--lc-accent-soft) 0%, transparent 60%),
                #fbfbfe;
            padding:24px; color:var(--lc-ink);
        }
        .auth-card{ width:100%; max-width:420px; background:#fff; border:1px solid var(--lc-line); border-radius:18px; box-shadow:0 24px 55px -28px rgba(28,30,43,0.25); padding:34px 30px; }
        .auth-brand{ display:flex; align-items:center; gap:10px; font-weight:800; font-size:1.25rem; margin-bottom:8px; }
        .auth-brand .dot{ width:34px;height:34px;border-radius:10px;background:var(--lc-accent);color:#fff;display:flex;align-items:center;justify-content:center; }
        .auth-sub{ color:var(--lc-ink-soft); font-size:14px; margin-bottom:24px; }
        label{ font-size:14px; font-weight:600; color:var(--lc-ink-soft); }
        .form-control{ font-size:14px; padding:10px 12px; border-radius:9px; }
        .form-control:focus{ border-color:var(--lc-accent); box-shadow:0 0 0 3px var(--lc-accent-soft); }
        .btn-auth{ background:var(--lc-accent); border-color:var(--lc-accent); color:#fff; font-weight:700; border-radius:9px; padding:10px; }
        .btn-auth:hover{ background:var(--lc-accent-ink); border-color:var(--lc-accent-ink); color:#fff; }
        .auth-foot{ text-align:center; font-size:14px; color:var(--lc-ink-soft); margin-top:18px; }
        .auth-foot a{ color:var(--lc-accent-ink); font-weight:700; text-decoration:none; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-brand"><span class="dot"><i class="bi bi-airplane-fill"></i></span> CTS</div>
        <div class="auth-sub">Recevez un lien pour réinitialiser votre mot de passe.</div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2" style="font-size:14px;"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('forgot-password') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= esc(old('email')) ?>" required autofocus>
            </div>
            <button type="submit" class="btn btn-auth w-100">Envoyer le lien</button>
        </form>

        <div class="auth-foot">
            <a href="<?= site_url('login') ?>"><i class="bi bi-arrow-left"></i> Retour à la connexion</a>
        </div>
    </div>
</body>
</html>