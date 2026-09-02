<?php
$statutsLabel = [
    'brouillon'           => 'Brouillon',
    'emise'               => 'Émise',
    'partiellement_payee' => 'Partiellement payée',
    'payee'               => 'Payée',
    'annulee'             => 'Annulée',
    'en_retard'           => 'En retard',
];
$devise = $facture['devise'] ?? 'MGA';
$clientNom = trim(($facture['client_prenom'] ?? '') . ' ' . ($facture['client_nom'] ?? ''));
if ($clientNom === '') {
    $clientNom = $facture['client_entreprise'] ?? 'Client';
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= esc($title) ?></title>
    <style>
        :root { color: #202532; font-family: 'Inter', sans-serif; }
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
            padding-bottom: 26px; border-bottom: 1px solid #4c5fd5;
        }
        .brand { font-size: 24px; font-weight: 700; color: #37419e; }
        .muted { color: #687083; font-size: 13px; }
        h1 { margin: 0; font-size: 26px; text-align: right; }
        .meta { margin-top: 8px; text-align: right; font-size: 13px; line-height: 1.7; }
        .info { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 28px 0; }
        .info-block { padding: 14px; background: #f7f8fb; border-left: 3px solid #4c5fd5; }
        .label {
            margin-bottom: 6px; color: #687083; font-size: 11px;
            font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th {
            padding: 10px 8px; border-bottom: 1px solid #202532;
            font-size: 11px; text-align: left; text-transform: uppercase;
        }
        td { padding: 11px 8px; border-bottom: 1px solid #e3e6ec; font-size: 13px; }
        .right { text-align: right; }
        .totals { margin-top: 20px; margin-left: auto; width: 300px; }
        .totals .row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; }
        .totals .grand {
            margin-top: 8px; padding-top: 10px; border-top: 1px solid #202532;
            font-size: 17px; font-weight: 700;
        }
        .notes {
            margin-top: 36px; padding-top: 14px; border-top: 1px solid #e3e6ec;
            white-space: pre-line; font-size: 13px;
        }
        @media print {
            body { background: #fff; }
            .toolbar { display: none; }
            .sheet { margin: 0; max-width: none; padding: 15mm 12mm; }
        }
        .agency {
            margin-top: 8px;
            font-size: 12px;
            line-height: 1.6;
            color: #687083;
        }

        .agency-name {
            margin-bottom: 4px;
            font-size: 16px;
            font-weight: 700;
            color: #202532;
        }
        .agency-header {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .agency-logo {
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" onclick="window.print()">Imprimer / PDF</button>
        <a href="<?= site_url('factures/' . $facture['id']) ?>">Retour</a>
    </div>

    <main class="sheet">
        <header class="header">
            <div class="agency-header">

                <?php if (!empty($agence['logo'])): ?>
                    <img
                        src="<?= base_url('uploads/agences/' . $agence['logo']) ?>"
                        alt="<?= esc($agence['nom_agence'] ?? 'Logo') ?>"
                        class="agency-logo"
                    >
                <?php endif; ?>

                <div class="agency">
                    <div class="agency-name">
                        <?= esc($agence['nom_agence'] ?? '') ?>
                    </div>

                    <?php if (!empty($agence['adresse'])): ?>
                        <div><?= esc($agence['adresse']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($agence['telephone'])): ?>
                        <div>Tél. : <?= esc($agence['telephone']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($agence['email_contact'])): ?>
                        <div>Email : <?= esc($agence['email_contact']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($agence['nif'])): ?>
                        <div>NIF : <?= esc($agence['nif']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($agence['stat'])): ?>
                        <div>STAT : <?= esc($agence['stat']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($agence['rcs'])): ?>
                        <div>RCS : <?= esc($agence['rcs']) ?></div>
                    <?php endif; ?>
                </div>

            </div>
            <div>
                <h1><?= esc($facture['numero']) ?></h1>
                <div class="meta">
                    Statut : <?= esc($statutsLabel[$facture['statut'] ?? ''] ?? $facture['statut']) ?><br>
                    Date : <?= !empty($facture['date_facture']) ? date('d/m/Y', strtotime($facture['date_facture'])) : '—' ?><br>
                    <?php if (!empty($facture['date_echeance'])): ?>
                        Échéance : <?= date('d/m/Y', strtotime($facture['date_echeance'])) ?>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <section class="info">
            <div class="info-block">
                <div class="label">Client</div>
                <strong><?= esc($clientNom) ?></strong>
                <?php if (!empty($facture['client_entreprise'])): ?>
                    <div class="muted"><?= esc($facture['client_entreprise']) ?></div>
                <?php endif; ?>
                <?php if (!empty($facture['client_email'])): ?>
                    <div class="muted"><?= esc($facture['client_email']) ?></div>
                <?php endif; ?>
                <?php if (!empty($facture['client_telephone'])): ?>
                    <div class="muted"><?= esc($facture['client_telephone']) ?></div>
                <?php endif; ?>
                <?php if (!empty($facture['client_adresse'])): ?>
                    <div class="muted"><?= esc($facture['client_adresse']) ?></div>
                <?php endif; ?>
            </div>
            <div class="info-block">
                <div class="label">Devise</div>
                <strong><?= esc($devise) ?></strong>
            </div>
        </section>

        <table>
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th class="right">Qté</th>
                    <th class="right">Prix unit.</th>
                    <th class="right">Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lignes)): ?>
                    <tr><td colspan="4" style="text-align:center;color:#687083;padding:24px;">Aucune ligne</td></tr>
                <?php else: ?>
                    <?php foreach ($lignes as $l): ?>
                        <tr>
                            <td>
                                <strong><?= esc($l['designation']) ?></strong>
                                <?php if (!empty($l['description'])): ?>
                                    <div class="muted"><?= esc($l['description']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="right"><?= esc($l['quantite']) ?></td>
                            <td class="right"><?= number_format((float)$l['prix_unitaire'], 2, ',', ' ') ?></td>
                            <td class="right"><?= number_format((float)$l['montant'], 2, ',', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="totals">
            <div class="row">
                <span>Montant HT</span>
                <span><?= number_format((float)($facture['montant_ht'] ?? 0), 2, ',', ' ') ?> <?= esc($devise) ?></span>
            </div>
            <div class="row">
                <span>TVA</span>
                <span><?= number_format((float)($facture['montant_tva'] ?? 0), 2, ',', ' ') ?> <?= esc($devise) ?></span>
            </div>
            <div class="row grand">
                <span>Total TTC</span>
                <span><?= number_format((float)($facture['montant_ttc'] ?? 0), 2, ',', ' ') ?> <?= esc($devise) ?></span>
            </div>
            <?php if ((float)($facture['montant_paye'] ?? 0) > 0): ?>
                <div class="row" style="color:#198754;">
                    <span>Déjà payé</span>
                    <span><?= number_format((float)$facture['montant_paye'], 2, ',', ' ') ?> <?= esc($devise) ?></span>
                </div>
                <div class="row" style="font-weight:700;">
                    <span>Restant dû</span>
                    <span><?= number_format((float)($facture['montant_restant'] ?? 0), 2, ',', ' ') ?> <?= esc($devise) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($totauxParDevise)): ?>
            <div style="margin-top: 16px; padding-top: 12px; border-top: 1px dashed #ccc;">
                <div style="font-size: 11px; font-weight: 700; color: #687083; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .04em;">
                    Équivalent dans les autres devises
                </div>

                <?php foreach ($totauxParDevise as $code => $t): ?>
                    <?php if ($code === $devise) continue; ?>

                    <div class="row" style="font-size: 13px; padding: 3px 0;">
                        <span style="color:#687083;"><?= esc($code) ?></span>
                        <span style="font-weight: 600;">
                            <?= number_format((float)($t['montant_ttc'] ?? 0), 2, ',', ' ') ?>
                            <?= esc($t['symbole'] ?? $code) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($facture['notes_client'])): ?>
            <div class="notes">
                <strong>Notes</strong><br>
                <?= esc($facture['notes_client']) ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>