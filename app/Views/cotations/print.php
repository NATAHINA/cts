<?php
$statutLabels = [
    'brouillon' => 'Brouillon',
    'envoyee'   => 'Envoyée',
    'acceptee'  => 'Acceptée',
    'refusee'   => 'Refusée',
    'expiree'   => 'Expirée',
];

$deviseCode    = $cotation['devise'] ?? 'EUR';
$deviseSymbole = $deviseCode;

$clientNom = trim(
    ($cotation['client_prenom'] ?? '') . ' ' .
    ($cotation['client_nom'] ?? '')
);
if ($clientNom === '') {
    $clientNom = $cotation['client_entreprise'] ?? 'Client non renseigné';
}

$numero = $cotation['numero']
    ?? $cotation['reference']
    ?? ('COT-' . ($cotation['id'] ?? ''));
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        :root { color: #202532; font-family: Arial, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #eef0f5; }
        .toolbar { padding: 16px; text-align: center; }
        .toolbar button, .toolbar a {
            border: 1px solid #d3d7e0; background: #fff; color: #202532;
            padding: 9px 14px; border-radius: 6px; text-decoration: none;
            cursor: pointer; font-size: 14px; margin: 0 4px;
        }
        .sheet {
            max-width: 900px; min-height: 297mm; margin: 0 auto 28px;
            padding: 34px 42px; background: #fff;
        }
        .header {
            display: flex; justify-content: space-between; gap: 24px;
            padding-bottom: 26px; border-bottom: 3px solid #4c5fd5;
        }
        .brand { font-size: 24px; font-weight: 700; color: #37419e; }
        .muted { color: #687083; font-size: 13px; }
        h1 { margin: 0; font-size: 28px; text-align: right; }
        .meta { margin-top: 8px; text-align: right; font-size: 13px; line-height: 1.7; }
        .info { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 30px 0; }
        .info-block { padding: 15px; background: #f7f8fb; border-left: 3px solid #4c5fd5; }
        .label {
            margin-bottom: 6px; color: #687083; font-size: 11px;
            font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
        }
        .info-block strong { font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th {
            padding: 11px 8px; border-bottom: 2px solid #202532;
            font-size: 11px; text-align: left; text-transform: uppercase;
        }
        td { padding: 12px 8px; border-bottom: 1px solid #e3e6ec; font-size: 13px; vertical-align: top; }
        .right { text-align: right; }
        .total {
            margin-top: 18px; margin-left: auto; width: 280px;
            display: flex; justify-content: space-between;
            padding: 14px 0; border-top: 2px solid #202532;
            font-size: 17px; font-weight: 700;
        }
        .notes {
            margin-top: 38px; padding-top: 15px;
            border-top: 1px solid #e3e6ec; white-space: pre-line; font-size: 13px;
        }
        .empty { padding: 24px 8px; color: #687083; text-align: center; }
        @media print {
            body { background: #fff; }
            .toolbar { display: none; }
            .sheet { margin: 0; max-width: none; padding: 15mm 12mm; }
        }
        @media (max-width: 640px) {
            .sheet { margin: 0; padding: 24px 18px; }
            .header, .info { grid-template-columns: 1fr; display: grid; }
            h1, .meta { text-align: left; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" onclick="window.print()">Imprimer / Enregistrer en PDF</button>
        <a href="<?= site_url('cotations/' . $cotation['id']) ?>">Retour à la cotation</a>
    </div>

    <main class="sheet">
        <header class="header">
            <div>
                <div class="brand">CTS</div>
                <div class="muted">Proposition de voyage</div>
            </div>
            <div>
                <h1><?= esc($numero) ?></h1>
                <div class="meta">
                    Statut : <?= esc($statutLabels[$cotation['statut'] ?? ''] ?? ($cotation['statut'] ?? '—')) ?><br>
                    Émise le <?= esc(date('d/m/Y', strtotime($cotation['created_at'] ?? 'now'))) ?><br>
                    <?php if (! empty($cotation['date_validite'])): ?>
                        Valable jusqu'au <?= esc(date('d/m/Y', strtotime($cotation['date_validite']))) ?>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <section class="info">
            <div class="info-block">
                <div class="label">Client</div>
                <strong><?= esc($clientNom) ?></strong>
                <?php if (! empty($cotation['client_email'])): ?>
                    <div class="muted"><?= esc($cotation['client_email']) ?></div>
                <?php endif; ?>
                <?php if (! empty($cotation['client_telephone'])): ?>
                    <div class="muted"><?= esc($cotation['client_telephone']) ?></div>
                <?php endif; ?>
                <?php if (! empty($cotation['client_adresse'])): ?>
                    <div class="muted"><?= esc($cotation['client_adresse']) ?></div>
                <?php endif; ?>
            </div>

            <div class="info-block">
                <div class="label">Voyage</div>
                <?php if (! empty($cotation['date_depart'])): ?>
                    <div><strong>Départ :</strong> <?= esc(date('d/m/Y', strtotime($cotation['date_depart']))) ?></div>
                <?php endif; ?>
                <?php if (! empty($cotation['date_retour'])): ?>
                    <div><strong>Retour :</strong> <?= esc(date('d/m/Y', strtotime($cotation['date_retour']))) ?></div>
                <?php endif; ?>
                <div class="muted" style="margin-top:6px;">
                    <?= (int)($cotation['nb_adultes'] ?? 0) ?> adulte(s),
                    <?= (int)($cotation['nb_enfants'] ?? 0) ?> enfant(s),
                    <?= (int)($cotation['nb_bebes'] ?? 0) ?> bébé(s)
                </div>
                <div style="margin-top:8px;">
                    <span class="label">Devise</span>
                    <strong><?= esc($deviseCode) ?></strong>
                </div>
            </div>
        </section>

        <table>
            <thead>
                <tr>
                    <th>Prestation</th>
                    <th>Type</th>
                    <th class="right">Qté</th>
                    <th class="right">Prix unitaire</th>
                    <th class="right">Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lignes)): ?>
                    <tr>
                        <td colspan="5" class="empty">Aucune prestation ajoutée.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lignes as $ligne): ?>
                        <tr>
                            <td>
                                <strong><?= esc($ligne['designation'] ?? $ligne['libelle'] ?? '-') ?></strong>
                                <?php if (! empty($ligne['description'])): ?>
                                    <div class="muted"><?= esc($ligne['description']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= esc(ucfirst($ligne['type_prestation'] ?? '—')) ?></td>
                            <td class="right">
                                <?= esc(rtrim(rtrim(number_format((float)($ligne['quantite'] ?? 1), 2, '.', ''), '0'), '.')) ?>
                            </td>
                            <td class="right">
                                <?= esc(number_format((float)($ligne['prix_unitaire'] ?? 0), 2, ',', ' ')) ?>
                                <?= esc($deviseSymbole) ?>
                            </td>
                            <td class="right">
                                <?= esc(number_format((float)($ligne['prix_total'] ?? $ligne['montant'] ?? 0), 2, ',', ' ')) ?>
                                <?= esc($deviseSymbole) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="total">
            <span>Total</span>
            <span>
                <?= esc(number_format((float)($cotation['prix_total'] ?? $cotation['montant_total'] ?? 0), 2, ',', ' ')) ?>
                <?= esc($deviseSymbole) ?>
            </span>
        </div>

        <?php if (! empty($cotation['notes_client'])): ?>
            <div class="notes">
                <strong>Notes</strong><br>
                <?= esc($cotation['notes_client']) ?>
            </div>
        <?php elseif (! empty($cotation['notes'])): ?>
            <div class="notes">
                <strong>Notes</strong><br>
                <?= esc($cotation['notes']) ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>