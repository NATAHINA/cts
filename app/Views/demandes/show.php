<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
    $statut = $demande['statut'] ?? 'nouvelle';

    $statutsClass = [
        'nouvelle'   => 'bg-primary',
        'en_cours'   => 'bg-warning text-dark',
        'traitee'    => 'bg-info text-dark',
        'cotée'      => 'bg-info text-dark',
        'convertie'  => 'bg-success',
        'annulee'    => 'bg-danger',
        'abandonnée' => 'bg-secondary',
        'refusee'    => 'bg-danger',
    ];

    $statutClass = $statutsClass[$statut] ?? 'bg-secondary';

    $statutLabels = [
        'nouvelle'   => 'Nouvelle',
        'en_cours'   => 'En cours',
        'traitee'    => 'Traitée',
        'cotée'      => 'Cotée',
        'convertie'  => 'Convertie en cotation',
        'annulee'    => 'Annulée',
        'abandonnée' => 'Abandonnée',
        'refusee'    => 'Refusée',
    ];

    $statutLabel = $statutLabels[$statut] ?? ucfirst(str_replace('_', ' ', $statut));

    $clientNom = trim(($demande['client_prenom'] ?? '') . ' ' . ($demande['client_nom'] ?? ''));
    if ($clientNom === '') {
        $clientNom = '-';
    }

    $adultes = (int) ($demande['nb_adultes'] ?? 0);
    $enfants = (int) ($demande['nb_enfants'] ?? 0);
    $bebes   = (int) ($demande['nb_bebes'] ?? 0);
    $totalPax = $adultes + $enfants + $bebes;

    $canEditLignes = ! in_array($statut, ['convertie', 'annulee', 'refusee'], true);
?>

<!-- ============================================================
     EN-TÊTE
============================================================ -->
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="mb-0 fw-bold">Détails de la demande</h4>
            <span class="badge <?= $statutClass ?>"><?= esc($statutLabel) ?></span>
        </div>

        <p class="text-muted mb-0">
            Référence :
            <strong>
                <?= esc($demande['numero'] ?? ('DEM-' . str_pad((string)($demande['id'] ?? 0), 5, '0', STR_PAD_LEFT))) ?>
            </strong>
        </p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="<?= site_url('demandes') ?>" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>

        <?php if (!empty($demande['id'])): ?>
            <a href="<?= site_url('demandes/' . $demande['id'] . '/edit') ?>" class="btn btn-light border">
                <i class="bi bi-pencil-square me-1"></i> Modifier
            </a>

            <?php if ($canEditLignes): ?>
                <a href="<?= site_url('demandes/' . $demande['id'] . '/convertir') ?>" class="btn lc-btn-primary">
                    <i class="bi bi-file-earmark-plus me-1"></i> Créer une cotation
                </a>
            <?php endif; ?>

            <?php if (in_array($statut, ['nouvelle', 'abandonnée', 'abandonnee'], true)): ?>
                <button type="button"
                        class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#modalSupprimerDemande">
                    <i class="bi bi-trash me-1"></i>
                    Supprimer
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>


<div class="row g-4">

    <!-- ========================================================
         COLONNE GAUCHE
    ========================================================= -->
    <div class="col-lg-8">

        <!-- Client -->
        <div class="lc-card p-4 mb-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;background:var(--lc-accent-soft);color:var(--lc-accent);">
                    <i class="bi bi-person fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Informations du client</h6>
                    <small class="text-muted">Coordonnées du demandeur</small>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Nom complet</small>
                    <div class="fw-semibold"><?= esc($clientNom) ?></div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Entreprise</small>
                    <div class="fw-semibold"><?= esc($demande['client_entreprise'] ?? '-') ?></div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1"><i class="bi bi-envelope me-1"></i> E-mail</small>
                    <?php if (!empty($demande['client_email'])): ?>
                        <a href="mailto:<?= esc($demande['client_email']) ?>"><?= esc($demande['client_email']) ?></a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1"><i class="bi bi-telephone me-1"></i> Téléphone</small>
                    <div><?= esc($demande['client_telephone'] ?? '-') ?></div>
                </div>
            </div>
        </div>


        <!-- Projet de voyage -->
        <div class="lc-card p-4 mb-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;background:var(--lc-accent-soft);color:var(--lc-accent);">
                    <i class="bi bi-airplane fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Projet de voyage</h6>
                    <small class="text-muted">Informations sur le séjour demandé</small>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Destination</small>
                    <div class="fw-semibold">
                        <i class="bi bi-geo-alt me-1 text-muted"></i>
                        <?= esc($demande['destination_nom'] ?? $demande['destination'] ?? '-') ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Source</small>
                    <div class="fw-semibold"><?= esc($demande['source'] ?? '-') ?></div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Date de départ</small>
                    <div class="fw-semibold">
                        <?php if (!empty($demande['date_depart'])): ?>
                            <i class="bi bi-calendar-event me-1 text-muted"></i>
                            <?= esc(date('d/m/Y', strtotime($demande['date_depart']))) ?>
                        <?php else: ?>-<?php endif; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Date de retour</small>
                    <div class="fw-semibold">
                        <?php if (!empty($demande['date_retour'])): ?>
                            <i class="bi bi-calendar-check me-1 text-muted"></i>
                            <?= esc(date('d/m/Y', strtotime($demande['date_retour']))) ?>
                        <?php else: ?>-<?php endif; ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block mb-1">Adultes</small>
                    <div class="fw-semibold"><i class="bi bi-person me-1 text-muted"></i> <?= $adultes ?></div>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block mb-1">Enfants</small>
                    <div class="fw-semibold"><i class="bi bi-people me-1 text-muted"></i> <?= $enfants ?></div>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block mb-1">Bébés</small>
                    <div class="fw-semibold"><i class="bi bi-emoji-smile me-1 text-muted"></i> <?= $bebes ?></div>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block mb-1">Total</small>
                    <div class="fw-semibold"><?= $totalPax ?> voyageur(s)</div>
                </div>
            </div>
        </div>


        <!-- ====================================================
             LIGNES DE PRESTATIONS
        ===================================================== -->
        <div class="lc-card p-4 mb-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:40px;height:40px;background:var(--lc-accent-soft);color:var(--lc-accent);">
                        <i class="bi bi-list-check fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Prestations demandées</h6>
                        <small class="text-muted">Services à intégrer dans la future cotation</small>
                    </div>
                </div>
            </div>

            <?php if (!empty($lignes)): ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Désignation</th>
                                <th class="text-end">Qté</th>
                                <th class="text-end">Coût unit.</th>
                                <th class="text-end">Marge %</th>
                                <th class="text-end">Prix total</th>
                                <?php if ($canEditLignes): ?>
                                    <th class="text-end" style="width:60px;"></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lignes as $i => $ligne): ?>
                                <tr>
                                    <td class="text-muted"><?= $i + 1 ?></td>
                                    <td>
                                        <span class="badge text-bg-light border text-capitalize">
                                            <?= esc($ligne['type_prestation'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= esc($ligne['designation'] ?? '-') ?></div>
                                        <?php if (!empty($ligne['description'])): ?>
                                            <small class="text-muted"><?= esc($ligne['description']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end"><?= esc($ligne['quantite'] ?? 1) ?></td>
                                    <td class="text-end">
                                        <?= number_format((float)($ligne['cout_unitaire'] ?? 0), 2, ',', ' ') ?>
                                    </td>
                                    <td class="text-end">
                                        <?= number_format((float)($ligne['marge_pourcentage'] ?? 0), 1, ',', ' ') ?> %
                                    </td>
                                    <td class="text-end fw-semibold">
                                        <?= number_format((float)($ligne['prix_total'] ?? 0), 2, ',', ' ') ?>
                                        <small class="text-muted"><?= esc($ligne['devise'] ?? $demande['devise'] ?? '') ?></small>
                                    </td>
                                    <?php if ($canEditLignes): ?>
                                        <td class="text-end">
                                            <form method="post"
                                                  action="<?= site_url('demandes/' . $demande['id'] . '/lignes/' . $ligne['id'] . '/delete') ?>"
                                                  onsubmit="return confirm('Supprimer cette prestation ?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-muted mb-3">
                    Aucune prestation ajoutée pour le moment.
                </div>
            <?php endif; ?>

            <?php
    $devise = $demande['devise'] ?? 'MGA';
    $modeTarif = $demande['mode_tarif'] ?? 'lignes';
    $prixForfait = $demande['prix_forfait'] ?? null;
    $budget = isset($demande['budget']) ? (float) $demande['budget'] : null;

    // Prix final selon le mode
    $prixFinal = ($modeTarif === 'forfait' && $prixForfait !== null && $prixForfait !== '')
        ? (float) $prixForfait
        : (float) ($totalPrix ?? 0);
?>

<!-- RÉCAPITULATIF MONTANTS -->
<div class="border rounded-3 p-3 mt-4 bg-light">
    <div class="row g-3 align-items-center">
                
        <div class="col-md-3">
            <small class="text-muted d-block">Coût total</small>
            <div class="fw-bold">
                <?= number_format((float)($totalCout ?? 0), 2, ',', ' ') ?>
                <span class="text-muted fs-6"><?= esc($devise) ?></span>
            </div>
        </div>

        <div class="col-md-3">
            <small class="text-muted d-block">Marge</small>
            <div class="fw-bold">
                <?= number_format((float)($totalMarge ?? 0), 2, ',', ' ') ?>
                <span class="text-muted fs-6">
                    (<?= number_format((float)($margePourcentage ?? 0), 1, ',', ' ') ?> %)
                </span>
            </div>
        </div>

        <div class="col-md-3">
            <small class="text-muted d-block">Total prestations</small>
            <div class="fw-bold" style="color:var(--lc-accent);">
                <?= number_format((float)($totalPrix ?? 0), 2, ',', ' ') ?>
                <span class="text-muted fs-6"><?= esc($devise) ?></span>
            </div>
        </div>

        <div class="col-md-3">
            <small class="text-muted d-block">
                Prix <?= $modeTarif === 'forfait' ? 'forfait' : 'proposé' ?>
            </small>
            <div class="fs-5 fw-bold" style="color:var(--lc-accent);">
                <?= number_format($prixFinal, 2, ',', ' ') ?>
                <span class="fs-6 text-muted"><?= esc($devise) ?></span>
            </div>
        </div>
    </div>

    <?php if ($budget !== null && $budget > 0): ?>
        <?php
            $ecart = $prixFinal - $budget;
            $ecartClass = $ecart > 0 ? 'text-danger' : 'text-success';
        ?>
        <hr class="my-3">
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <div>
                <small class="text-muted">Budget client</small>
                <div class="fw-semibold">
                    <?= number_format($budget, 0, ',', ' ') ?> <?= esc($devise) ?>
                </div>
            </div>
            <div>
                <small class="text-muted">Écart</small>
                <div class="fw-semibold <?= $ecartClass ?>">
                    <?= $ecart >= 0 ? '+' : '' ?><?= number_format($ecart, 0, ',', ' ') ?> <?= esc($devise) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- =====================================================
     ÉQUIVALENTS DANS LES AUTRES DEVISES
===================================================== -->
<?php if (!empty($totauxParDevise)): ?>
    <div class="border rounded-3 p-3 mt-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-currency-exchange" style="color:var(--lc-accent);"></i>
            <h6 class="mb-0 fw-bold">Équivalent dans les autres devises</h6>
        </div>

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Devise</th>
                        <th class="text-end">Coût total</th>
                        <th class="text-end">Marge</th>
                        <th class="text-end">Total prestations</th>
                        <th class="text-end">Prix proposé</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($totauxParDevise as $code => $t): ?>
                        <?php
                            // On met en évidence la devise de la demande
                            $isCurrent = ($code === ($deviseDemande ?? $devise ?? 'MGA'));
                        ?>
                        <tr class="<?= $isCurrent ? 'table-primary' : '' ?>">
                            <td>
                                <span class="fw-semibold"><?= esc($code) ?></span>
                                <small class="text-muted"><?= esc($t['symbole'] ?? '') ?></small>
                                <?php if ($isCurrent): ?>
                                    <span class="badge bg-primary ms-1">Actuelle</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?= number_format((float)($t['total_cout'] ?? 0), 2, ',', ' ') ?>
                            </td>
                            <td class="text-end">
                                <?= number_format((float)($t['total_marge'] ?? 0), 2, ',', ' ') ?>
                            </td>
                            <td class="text-end">
                                <?= number_format((float)($t['total_prix'] ?? 0), 2, ',', ' ') ?>
                            </td>
                            <td class="text-end fw-semibold">
                                <?= number_format((float)($t['prix_affiche'] ?? 0), 2, ',', ' ') ?>
                                <small class="text-muted"><?= esc($t['symbole'] ?? $code) ?></small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="form-text mt-2">
            Les montants sont convertis à partir de la devise de la demande
            (<strong><?= esc($deviseDemande ?? $devise ?? 'MGA') ?></strong>)
            avec les taux enregistrés dans les paramètres.
        </div>
    </div>
<?php endif; ?>



<?php if ($canEditLignes): ?>
    <!-- MODE TARIF / FORFAIT -->
    <div class="border rounded-3 p-3 mt-3">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-tag me-1"></i> Mode de tarification
        </h6>

        <form method="post" action="<?= site_url('demandes/' . $demande['id'] . '/tarif') ?>">
            <?= csrf_field() ?>

            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Mode</label>
                    <select name="mode_tarif" id="mode_tarif" class="form-select">
                        <option value="lignes"  <?= $modeTarif === 'lignes'  ? 'selected' : '' ?>>
                            Somme des lignes
                        </option>
                        <option value="forfait" <?= $modeTarif === 'forfait' ? 'selected' : '' ?>>
                            Forfait global
                        </option>
                    </select>
                </div>

                <div class="col-md-4" id="bloc_prix_forfait"
                     style="<?= $modeTarif === 'forfait' ? '' : 'display:none;' ?>">
                    <label class="form-label">Prix forfait (<?= esc($devise) ?>)</label>
                    <input type="number"
                           name="prix_forfait"
                           class="form-control"
                           step="0.01"
                           min="0"
                           value="<?= esc($prixForfait ?? '') ?>"
                           placeholder="Ex : 2500000">
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn lc-btn-primary w-100">
                        Enregistrer le tarif
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('mode_tarif')?.addEventListener('change', function () {
            document.getElementById('bloc_prix_forfait').style.display =
                this.value === 'forfait' ? '' : 'none';
        });
    </script>
<?php endif; ?>


            <?php if ($canEditLignes): ?>
                <hr class="my-4">

                <h6 class="fw-bold mb-3">Ajouter une prestation</h6>

                <form method="post" action="<?= site_url('demandes/' . $demande['id'] . '/lignes') ?>">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Type de prestation *</label>
                            <select name="type_prestation" id="type_prestation" class="form-select" required>
                                <option value="">Choisir…</option>
                                <option value="hotel">Hôtel</option>
                                <option value="restaurant">Restaurant</option>
                                <option value="vol">Vol</option>
                                <option value="excursion">Excursion</option>
                                <option value="transfert">Transfert</option>
                                <option value="croisiere">Croisière</option>
                                <option value="forfait">Forfait</option>
                                <option value="circuit">Circuit</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Prestation du catalogue</label>
                            <select name="prestation_id" id="prestation_id" class="form-select">
                                <option value="">— Saisie manuelle —</option>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Désignation *</label>
                            <input type="text" name="designation" id="designation" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Quantité</label>
                            <input type="number" name="quantite" class="form-control" value="1" min="0.01" step="0.01">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Coût unitaire</label>
                            <input type="number" name="cout_unitaire" id="cout_unitaire" class="form-control" value="0" min="0" step="0.01">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Marge %</label>
                            <input type="number" name="marge_pourcentage" class="form-control" value="0" min="0" step="0.1">
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn lc-btn-primary w-100">
                                <i class="bi bi-plus-lg me-1"></i> Ajouter
                            </button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>


        <!-- Notes client -->
        <div class="lc-card p-4 mb-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;background:var(--lc-accent-soft);color:var(--lc-accent);">
                    <i class="bi bi-chat-left-text fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Demande du client</h6>
                    <small class="text-muted">Notes / besoins exprimés</small>
                </div>
            </div>

            <?php if (!empty($demande['notes_client'])): ?>
                <div class="border rounded-3 p-3 bg-light" style="white-space:pre-line;line-height:1.7;">
                    <?= esc($demande['notes_client']) ?>
                </div>
            <?php else: ?>
                <div class="text-muted">Aucun commentaire renseigné.</div>
            <?php endif; ?>
        </div>


        <!-- Notes internes -->
        <div class="lc-card p-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;background:var(--lc-accent-soft);color:var(--lc-accent);">
                    <i class="bi bi-lock fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Notes internes</h6>
                    <small class="text-muted">Visible uniquement par l’agence</small>
                </div>
            </div>

            <?php if (!empty($demande['notes_interne'])): ?>
                <div class="border rounded-3 p-3 bg-light" style="white-space:pre-line;line-height:1.7;">
                    <?= esc($demande['notes_interne']) ?>
                </div>
            <?php else: ?>
                <div class="text-muted">Aucune note interne.</div>
            <?php endif; ?>
        </div>

    </div>


    <!-- ========================================================
         COLONNE DROITE
    ========================================================= -->
    <div class="col-lg-4">

        <!-- Suivi -->
        <div class="lc-card p-4 mb-4">
            <h6 class="fw-bold mb-4">
                <i class="bi bi-activity me-2"></i> Suivi de la demande
            </h6>

            <div class="mb-4">
                <small class="text-muted d-block mb-2">Statut actuel</small>
                <span class="badge <?= $statutClass ?> px-3 py-2"><?= esc($statutLabel) ?></span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block mb-1">Créée le</small>
                <div class="fw-semibold">
                    <?= !empty($demande['created_at'])
                        ? esc(date('d/m/Y à H:i', strtotime($demande['created_at'])))
                        : '-' ?>
                </div>
            </div>

            <div>
                <small class="text-muted d-block mb-1">Dernière modification</small>
                <div class="fw-semibold">
                    <?= !empty($demande['updated_at'])
                        ? esc(date('d/m/Y à H:i', strtotime($demande['updated_at'])))
                        : '-' ?>
                </div>
            </div>
        </div>


        <!-- Budget -->
        <div class="lc-card p-4 mb-4">
            <h6 class="fw-bold mb-4">
                <i class="bi bi-wallet2 me-2"></i> Budget
            </h6>

            <?php
                $budget = $demande['budget'] ?? null;
                $devise = $demande['devise'] ?? 'MGA';
            ?>

            <?php if ($budget !== null && $budget !== ''): ?>
                <div class="fs-4 fw-bold" style="color:var(--lc-accent);">
                    <?= number_format((float)$budget, 0, ',', ' ') ?>
                    <span class="fs-6 text-muted"><?= esc($devise) ?></span>
                </div>
                <small class="text-muted">Budget indicatif communiqué par le client</small>
            <?php else: ?>
                <div class="text-muted">Aucun budget renseigné.</div>
            <?php endif; ?>
        </div>


        <!-- Actions -->
        <div class="lc-card p-4">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-lightning-charge me-2"></i> Actions rapides
            </h6>

            <div class="d-grid gap-2">
                <?php if ($canEditLignes): ?>
                    <a href="<?= site_url('demandes/' . $demande['id'] . '/convertir') ?>"
                       class="btn lc-btn-primary">
                        <i class="bi bi-file-earmark-plus me-2"></i>
                        Convertir en cotation
                    </a>
                <?php endif; ?>

                <?php if (!empty($demande['client_email'])): ?>
                    <a href="mailto:<?= esc($demande['client_email']) ?>" class="btn btn-light border">
                        <i class="bi bi-envelope me-2"></i> Contacter le client
                    </a>
                <?php endif; ?>

                <?php if (!empty($demande['client_telephone'])): ?>
                    <a href="tel:<?= esc($demande['client_telephone']) ?>" class="btn btn-light border">
                        <i class="bi bi-telephone me-2"></i> Appeler le client
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- =========================================================
     MODAL CONFIRMATION SUPPRESSION DEMANDE
========================================================= -->
<div class="modal fade" id="modalSupprimerDemande" tabindex="-1" aria-labelledby="modalSupprimerDemandeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger" id="modalSupprimerDemandeLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Supprimer la demande
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <p class="mb-2">
                    Voulez-vous vraiment supprimer définitivement la demande
                    <strong><?= esc($demande['numero'] ?? '') ?></strong> ?
                </p>
                <p class="text-muted small mb-0">
                    Cette action est irréversible. Toutes les informations liées à cette demande seront perdues.
                </p>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                    Annuler
                </button>

                <form method="post"
                      action="<?= site_url('demandes/' . $demande['id'] . '/delete') ?>"
                      class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Oui, supprimer
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<?php if ($canEditLignes && !empty($catalogue)): ?>
<script>
(function () {
    const catalogue = <?= json_encode($catalogue ?? []) ?>;

    const typeSelect   = document.getElementById('type_prestation');
    const prestaSelect = document.getElementById('prestation_id');
    const designation  = document.getElementById('designation');
    const coutInput    = document.getElementById('cout_unitaire');

    function fillPrestations() {
        const type = typeSelect.value;
        prestaSelect.innerHTML = '<option value="">— Saisie manuelle —</option>';

        if (!type || !catalogue[type]) return;

        catalogue[type].forEach(function (item) {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.label + (item.prix ? ' — ' + item.prix : '');
            opt.dataset.label = item.label;
            opt.dataset.prix  = item.prix || 0;
            prestaSelect.appendChild(opt);
        });
    }

    typeSelect.addEventListener('change', fillPrestations);

    prestaSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) return;

        if (opt.dataset.label) {
            designation.value = opt.dataset.label;
        }
        if (opt.dataset.prix) {
            coutInput.value = opt.dataset.prix;
        }
    });
})();
</script>
<?php endif; ?>

<?= $this->endSection() ?>