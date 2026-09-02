<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Accès refusé - 403</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {

            font-family:
                Inter,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            background: #f6f8fb;

            color: #212529;

            min-height: 100vh;

            padding: 40px 20px;
        }

        .error-wrapper {

            width: 100%;

            max-width: 760px;

            margin: 0 auto;
        }

        .error-card {

            background: #fff;

            border: 1px solid #e9ecef;

            border-radius: 18px;

            padding: 42px;

            box-shadow:
                0 10px 35px rgba(0, 0, 0, .05);
        }

        .error-top {

            text-align: center;

            padding-bottom: 30px;

            border-bottom: 1px solid #edf0f2;
        }

        .error-icon {

            width: 78px;
            height: 78px;

            margin: 0 auto 20px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #fff3cd;

            color: #856404;

            font-size: 34px;
        }

        .error-code {

            font-size: 64px;

            line-height: 1;

            font-weight: 800;

            letter-spacing: -3px;

            margin-bottom: 10px;
        }

        .error-title {

            font-size: 25px;

            font-weight: 700;

            margin-bottom: 10px;
        }

        .error-description {

            color: #6c757d;

            font-size: 14px;

            line-height: 1.7;

            max-width: 560px;

            margin: 0 auto;
        }

        /* Page refusée */

        .denied-page {

            margin-top: 25px;

            padding: 15px 18px;

            border-radius: 10px;

            background: #f8f9fa;

            border: 1px solid #e9ecef;
        }

        .denied-page-label {

            font-size: 11px;

            text-transform: uppercase;

            letter-spacing: .5px;

            color: #868e96;

            margin-bottom: 5px;
        }

        .denied-page-title {

            display: flex;

            align-items: center;

            gap: 9px;

            font-size: 15px;

            font-weight: 600;
        }

        .denied-page-title i {

            color: #dc3545;

        }

        .permission-code {

            margin-top: 4px;

            font-size: 11px;

            color: #adb5bd;

        }

        /* Pages accessibles */

        .accessible-section {

            margin-top: 28px;
        }

        .accessible-title {

            font-size: 15px;

            font-weight: 600;

            margin-bottom: 12px;
        }

        .accessible-grid {

            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 10px;
        }

        .accessible-link {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 11px 12px;

            border: 1px solid #e9ecef;

            border-radius: 9px;

            background: #fff;

            color: #343a40;

            text-decoration: none;

            font-size: 13px;

            transition:
                background-color .2s ease,
                border-color .2s ease,
                transform .2s ease;
        }

        .accessible-link:hover {

            background: #f8f9fa;

            border-color: #ced4da;

            color: #212529;

            transform: translateY(-1px);
        }

        .accessible-icon {

            width: 30px;
            height: 30px;

            flex-shrink: 0;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 7px;

            background: #f1f3f5;

            color: #495057;

        }

        .error-actions {

            display: flex;

            justify-content: center;

            gap: 10px;

            margin-top: 28px;

            padding-top: 22px;

            border-top: 1px solid #edf0f2;

        }

        .btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            min-height: 42px;

            padding: 9px 18px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;
        }

        .btn-primary {

            background: #212529;

            border: 1px solid #212529;

            color: #fff;
        }

        .btn-primary:hover {

            background: #000;

            color: #fff;
        }

        .btn-light {

            background: #fff;

            border: 1px solid #dee2e6;

            color: #495057;
        }

        .btn-light:hover {

            background: #f8f9fa;

            color: #212529;
        }

        .error-footer {

            text-align: center;

            margin-top: 18px;

            font-size: 11px;

            color: #adb5bd;
        }

        @media (max-width: 700px) {

            body {
                padding: 20px 12px;
            }

            .error-card {
                padding: 30px 20px;
            }

            .accessible-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 480px) {

            .error-code {
                font-size: 54px;
            }

            .error-title {
                font-size: 21px;
            }

            .accessible-grid {
                grid-template-columns: 1fr;
            }

            .error-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

        }

    </style>

</head>


<body>

<div class="error-wrapper">

    <div class="error-card">

        <!-- ============================================
             EN-TÊTE
             ============================================ -->

        <div class="error-top">

            <div class="error-icon">

                <i class="bi bi-shield-lock"></i>

            </div>

            <div class="error-code">
                403
            </div>

            <h1 class="error-title">
                Accès refusé
            </h1>

            <p class="error-description">

                Votre compte est correctement connecté,
                mais votre rôle ne possède pas l'autorisation
                nécessaire pour accéder à cette fonctionnalité.

            </p>

        </div>


        <!-- ============================================
             PAGE DEMANDÉE
             ============================================ -->

        <?php if (! empty($requiredPermission)): ?>

            <div class="denied-page">

                <div class="denied-page-label">
                    Fonctionnalité demandée
                </div>

                <div class="denied-page-title">

                    <i class="bi bi-lock-fill"></i>

                    <?= esc(
                        $requiredPermission['libelle']
                        ?? 'Accès à cette fonctionnalité'
                    ) ?>

                </div>

            </div>

        <?php endif; ?>


        <!-- ============================================
             PAGES ACCESSIBLES
             ============================================ -->

        <?php if (! empty($accessibleLinks)): ?>

            <div class="accessible-section">

                <div class="accessible-title">

                    <i class="bi bi-check-circle me-1"></i>

                    Fonctionnalités auxquelles vous avez accès

                </div>


                <div class="accessible-grid">

                    <?php foreach ($accessibleLinks as $link): ?>

                        <a
                            href="<?= esc($link['url']) ?>"
                            class="accessible-link"
                        >

                            <span class="accessible-icon">

                                <i class="bi <?= esc($link['icon']) ?>"></i>

                            </span>

                            <span>
                                <?= esc($link['label']) ?>
                            </span>

                        </a>

                    <?php endforeach; ?>

                </div>

            </div>

        <?php endif; ?>


        <!-- ============================================
             ACTIONS
             ============================================ -->

        <div class="error-actions">

            <a
                href="javascript:history.back()"
                class="btn btn-light"
            >

                <i class="bi bi-arrow-left"></i>

                Retour

            </a>

        </div>

    </div>


    <div class="error-footer">

        Code d'erreur : 403 · Accès non autorisé

    </div>

</div>

</body>

</html>