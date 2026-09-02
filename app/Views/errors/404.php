<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Page introuvable - 404</title>

    <!-- Bootstrap Icons -->
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

            display: flex;

            align-items: center;

            justify-content: center;

            min-height: 100vh;

            padding: 30px;
        }

        .error-wrapper {

            width: 100%;

            max-width: 620px;

            text-align: center;
        }

        .error-card {

            background: #ffffff;

            border: 1px solid #e9ecef;

            border-radius: 18px;

            padding: 48px 40px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, .05);
        }

        .error-icon {

            width: 86px;
            height: 86px;

            margin: 0 auto 24px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #e9ecef;

            color: #495057;

            font-size: 38px;
        }

        .error-code {

            font-size: 76px;

            line-height: 1;

            font-weight: 800;

            letter-spacing: -3px;

            color: #212529;

            margin-bottom: 12px;
        }

        .error-title {

            font-size: 24px;

            font-weight: 700;

            margin-bottom: 12px;
        }

        .error-message {

            max-width: 460px;

            margin: 0 auto 28px;

            color: #6c757d;

            font-size: 15px;

            line-height: 1.7;
        }

        .error-actions {

            display: flex;

            justify-content: center;

            align-items: center;

            gap: 10px;

            flex-wrap: wrap;
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

            font-size: 14px;

            font-weight: 600;

            transition:
                background-color .2s ease,
                border-color .2s ease,
                transform .2s ease;
        }

        .btn:hover {

            transform: translateY(-1px);
        }

        .btn-primary {

            background: #212529;

            border: 1px solid #212529;

            color: #ffffff;
        }

        .btn-primary:hover {

            background: #000000;

            border-color: #000000;
        }

        .btn-light {

            background: #ffffff;

            border: 1px solid #dee2e6;

            color: #495057;
        }

        .btn-light:hover {

            background: #f8f9fa;
        }

        .error-footer {

            margin-top: 20px;

            color: #adb5bd;

            font-size: 12px;
        }

        @media (max-width: 576px) {

            body {
                padding: 15px;
            }

            .error-card {

                padding: 38px 22px;

                border-radius: 14px;
            }

            .error-code {

                font-size: 60px;
            }

            .error-title {

                font-size: 21px;
            }

            .error-icon {

                width: 72px;

                height: 72px;

                font-size: 32px;
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

        <div class="error-icon">

            <i class="bi bi-compass"></i>

        </div>


        <div class="error-code">
            404
        </div>


        <h1 class="error-title">
            Page introuvable
        </h1>


        <p class="error-message">

            Désolé, la page que vous recherchez n'existe pas,
            a été déplacée ou l'adresse saisie est incorrecte.

            <?php if (! empty($message)): ?>

                <br>

                <span>
                    <?= esc($message) ?>
                </span>

            <?php endif; ?>

        </p>


        <div class="error-actions">

            <a
                href="<?= site_url('/') ?>"
                class="btn btn-primary"
            >

                <i class="bi bi-house"></i>

                Tableau de bord

            </a>


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

        Code d'erreur : 404 · Page introuvable

    </div>

</div>

</body>

</html>