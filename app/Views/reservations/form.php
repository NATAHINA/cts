<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php

$isEdit = !empty($reservation);

$deviseActuelle = old(
    'devise',
    $reservation['devise'] ?? 'MGA'
);

$statutActuel = old(
    'statut',
    $reservation['statut'] ?? 'en_attente'
);

$planningActuel = old(
    'planning_id',
    $reservation['planning_id'] ?? ''
);

$lignesExistantes = $lignes ?? [];

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">
            <?= $isEdit
                ? 'Modifier la réservation'
                : 'Nouvelle réservation' ?>
        </h4>
        <p class="text-muted mb-0">
            Informations générales de la réservation.
        </p>
    </div>

    <a href="<?= site_url('reservations') ?>" class="btn btn-light border">
        Annuler
    </a>

</div>

<form
    method="post"
    action="<?= $isEdit
        ? site_url('reservations/' . $reservation['id'])
        : site_url('reservations') ?>"
    id="reservation-form"
>
    <?= csrf_field() ?>

    <!-- =========================================================
         INFORMATIONS GENERALES
    ========================================================= -->
    <div class="lc-card p-4 mb-3">

        <h6 class="mb-3">Informations générales</h6>

        <div class="row g-3">

            <!-- CLIENT -->
            <div class="col-md-6">
                <label class="form-label">Client *</label>
                <select name="client_id" class="form-select" required>
                    <option value="">Sélectionner un client</option>
                    <?php foreach ($clients ?? [] as $client): ?>
                        <option
                            value="<?= $client['id'] ?>"
                            <?= old('client_id', $reservation['client_id'] ?? '') == $client['id'] ? 'selected' : '' ?>
                        >
                            <?= esc(trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''))) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- COTATION -->
            <div class="col-md-6">
                <label class="form-label">Cotation d'origine</label>
                <select name="cotation_id" class="form-select">
                    <option value="">Sans cotation</option>
                    <?php foreach ($cotations ?? [] as $cotation): ?>
                        <option
                            value="<?= $cotation['id'] ?>"
                            <?= old('cotation_id', $reservation['cotation_id'] ?? '') == $cotation['id'] ? 'selected' : '' ?>
                        >
                            <?= esc($cotation['numero']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- DESTINATION -->
            <div class="col-md-6">
                <label class="form-label">Destination *</label>
                <select name="destination_id" id="destination_id" class="form-select" required>
                    <option value="">Sélectionner une destination</option>
                    <?php foreach ($destinations ?? [] as $destination): ?>
                        <option
                            value="<?= $destination['id'] ?>"
                            <?= old('destination_id', $reservation['destination_id'] ?? '') == $destination['id'] ? 'selected' : '' ?>
                        >
                            <?= esc($destination['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- PLANNING -->
            <div class="col-md-6">
                <label class="form-label">Planning de départ *</label>
                <select name="planning_id" id="planning_id" class="form-select" required>
                    <option value="">Sélectionner d'abord une destination</option>
                </select>
                <div class="form-text">
                    Les informations du voyage seront automatiquement récupérées depuis le planning.
                </div>
            </div>

            <!-- INFORMATIONS DU PLANNING -->
            <div class="col-md-6">
                <div id="planning-info" class="alert alert-light border d-none mb-0">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <strong>Date départ :</strong>
                            <span id="planning-date-depart">—</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Heure départ :</strong>
                            <span id="planning-heure-depart">—</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Date retour :</strong>
                            <span id="planning-date-retour">—</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Heure retour :</strong>
                            <span id="planning-heure-retour">—</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Places disponibles :</strong>
                            <span id="planning-places">—</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Prix :</strong>
                            <span id="planning-prix">—</span>
                            <span id="planning-devise">MGA</span>
                        </div>
                        <div class="col-md-12">
                            <strong>Statut :</strong>
                            <span id="planning-statut">—</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATES CACHÉES -->
            <input
                type="hidden"
                name="date_depart"
                id="date_depart"
                value="<?= esc(old('date_depart', $reservation['date_depart'] ?? '')) ?>"
            >
            <input
                type="hidden"
                name="date_retour"
                id="date_retour"
                value="<?= esc(old('date_retour', $reservation['date_retour'] ?? '')) ?>"
            >

            <!-- DEVISE -->
            <div class="col-md-3">
                <label class="form-label">Devise</label>
                <select name="devise" id="devise" class="form-select">
                    <option value="MGA" <?= $deviseActuelle === 'MGA' ? 'selected' : '' ?>>MGA</option>
                    <option value="EUR" <?= $deviseActuelle === 'EUR' ? 'selected' : '' ?>>EUR</option>
                    <option value="USD" <?= $deviseActuelle === 'USD' ? 'selected' : '' ?>>USD</option>
                </select>
            </div>

            <!-- STATUT -->
            <div class="col-md-3">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="en_attente" <?= $statutActuel === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                    <option value="confirmée"  <?= $statutActuel === 'confirmée'  ? 'selected' : '' ?>>Confirmée</option>
                    <option value="annulée"    <?= $statutActuel === 'annulée'    ? 'selected' : '' ?>>Annulée</option>
                    <option value="terminée"   <?= $statutActuel === 'terminée'   ? 'selected' : '' ?>>Terminée</option>
                </select>
            </div>

        </div>
    </div>

    <!-- =========================================================
         VOYAGE
    ========================================================= -->
    <div class="lc-card p-4 mb-3">

        <h6 class="mb-3">Informations du voyage</h6>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Adultes</label>
                <input
                    type="number"
                    min="0"
                    name="nb_adultes"
                    id="nb_adultes"
                    class="form-control"
                    value="<?= old('nb_adultes', $reservation['nb_adultes'] ?? 1) ?>"
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Enfants</label>
                <input
                    type="number"
                    min="0"
                    name="nb_enfants"
                    id="nb_enfants"
                    class="form-control"
                    value="<?= old('nb_enfants', $reservation['nb_enfants'] ?? 0) ?>"
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Bébés</label>
                <input
                    type="number"
                    min="0"
                    name="nb_bebes"
                    id="nb_bebes"
                    class="form-control"
                    value="<?= old('nb_bebes', $reservation['nb_bebes'] ?? 0) ?>"
                >
            </div>
        </div>
    </div>

    <!-- =========================================================
         LIGNES PRESTATIONS
    ========================================================= -->
    <div class="lc-card p-4 mb-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h6 class="mb-1">Prestations de la réservation</h6>
                <small class="text-muted">
                    Choisissez le type de prestation puis le catalogue correspondant.
                </small>
            </div>

            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-ligne">
                <i class="bi bi-plus-lg me-1"></i>
                Ajouter une ligne
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th style="width: 15%">Type</th>
                        <th style="width: 35%">Catalogue</th>
                        <th style="width: 10%">Qté</th>
                        <th style="width: 15%">P.U.</th>
                        <th style="width: 15%">Total</th>
                        <th style="width: 10%"></th>
                    </tr>
                </thead>

                <tbody id="lignes-body">

                    <?php if (empty($lignesExistantes)): ?>

                        <!-- Ligne vide (création) -->
                        <tr class="ligne-row">
                            <td>
                                <select name="lignes[0][type_prestation]" class="form-select form-select-sm type-prestation">
                                    <option value="">Type...</option>
                                    <option value="hotel">Hôtel</option>
                                    <option value="vol">Vol</option>
                                    <option value="excursion">Excursion</option>
                                    <option value="transfert">Transfert</option>
                                    <option value="restaurant">Restaurant</option>
                                    <option value="croisiere">Croisière</option>
                                    <option value="forfait">Forfait</option>
                                    <option value="circuit">Circuit</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </td>
                            <td>
                                <select name="lignes[0][prestation_id]" class="form-select form-select-sm catalogue-prestation" disabled>
                                    <option value="">Choisir d'abord un type</option>
                                </select>
                                <input type="hidden" name="lignes[0][designation]" class="designation" value="">
                            </td>
                            <td>
                                <input type="number" name="lignes[0][quantite]" class="form-control form-control-sm quantite" min="1" value="1">
                            </td>
                            <td>
                                <input type="number" name="lignes[0][prix_unitaire]" class="form-control form-control-sm prix-unitaire" step="0.01" min="0" value="0">
                            </td>
                            <td>
                                <span class="prix-total fw-semibold">0</span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-ligne">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                    <?php else: ?>

                        <!-- Lignes existantes (édition) -->
                        <?php foreach ($lignesExistantes as $index => $ligne): ?>
                            <tr class="ligne-row">
                                <td>
                                    <select name="lignes[<?= $index ?>][type_prestation]" class="form-select form-select-sm type-prestation">
                                        <option value="">Type...</option>
                                        <?php
                                        $types = [
                                            'hotel'      => 'Hôtel',
                                            'vol'        => 'Vol',
                                            'excursion'  => 'Excursion',
                                            'transfert'  => 'Transfert',
                                            'restaurant' => 'Restaurant',
                                            'croisiere'  => 'Croisière',
                                            'forfait'    => 'Forfait',
                                            'circuit'    => 'Circuit',
                                            'autre'      => 'Autre',
                                        ];
                                        foreach ($types as $value => $label):
                                        ?>
                                            <option value="<?= $value ?>"
                                                <?= ($ligne['type_prestation'] ?? '') === $value ? 'selected' : '' ?>>
                                                <?= $label ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <td>
                                    <select
                                        name="lignes[<?= $index ?>][prestation_id]"
                                        class="form-select form-select-sm catalogue-prestation"
                                        data-selected="<?= esc($ligne['prestation_id'] ?? '') ?>"
                                    >
                                        <option value="">Charger le catalogue...</option>
                                    </select>

                                    <input
                                        type="hidden"
                                        name="lignes[<?= $index ?>][designation]"
                                        class="designation"
                                        value="<?= esc($ligne['designation'] ?? '') ?>"
                                    >
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        name="lignes[<?= $index ?>][quantite]"
                                        class="form-control form-control-sm quantite"
                                        min="1"
                                        value="<?= (int)($ligne['quantite'] ?? 1) ?>"
                                    >
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        name="lignes[<?= $index ?>][prix_unitaire]"
                                        class="form-control form-control-sm prix-unitaire"
                                        step="0.01"
                                        min="0"
                                        value="<?= number_format((float)($ligne['prix_unitaire'] ?? 0), 2, '.', '') ?>"
                                    >
                                </td>

                                <td>
                                    <span class="prix-total fw-semibold">
                                        <?= number_format(
                                            (float)($ligne['quantite'] ?? 1) * (float)($ligne['prix_unitaire'] ?? 0),
                                            0, ',', ' '
                                        ) ?>
                                    </span>
                                </td>

                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-ligne">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>
            </table>
        </div>

        <div class="text-end mt-3">
            <strong>Total prestations :</strong>
            <span id="total-lignes" class="fs-5 fw-bold">0</span>
            <span id="total-devise"><?= esc($deviseActuelle) ?></span>
        </div>
    </div>

    <!-- =========================================================
         MONTANT
    ========================================================= -->
    <div class="lc-card p-4 mb-3">
        <h6 class="mb-3">Montant</h6>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Montant total</label>
                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="montant_total"
                    id="montant_total"
                    class="form-control"
                    value="<?= old('montant_total', $reservation['montant_total'] ?? 0) ?>"
                    readonly
                >
                <div class="form-text">
                    Calculé automatiquement à partir des prestations.
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================
         NOTES
    ========================================================= -->
    <div class="lc-card p-4 mb-3">
        <h6 class="mb-3">Notes pour le client</h6>
        <textarea name="notes_client" rows="4" class="form-control"><?= esc(old('notes_client', $reservation['notes_client'] ?? '')) ?></textarea>
    </div>

    <div class="lc-card p-4 mb-3">
        <h6 class="mb-3">Notes internes</h6>
        <textarea name="notes_interne" rows="4" class="form-control"><?= esc(old('notes_interne', $reservation['notes_interne'] ?? '')) ?></textarea>
    </div>

    <!-- SUBMIT -->
    <div class="text-end">
        <button type="submit" class="btn lc-btn-primary px-4">
            <i class="bi bi-check-lg me-1"></i>
            <?= $isEdit ? 'Enregistrer les modifications' : 'Créer la réservation' ?>
        </button>
    </div>

</form>

<!-- =========================================================
     JAVASCRIPT - PLANNING
========================================================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const destinationSelect = document.getElementById('destination_id');
    const planningSelect    = document.getElementById('planning_id');
    const planningInfo      = document.getElementById('planning-info');
    const dateDepartInput   = document.getElementById('date_depart');
    const dateRetourInput   = document.getElementById('date_retour');
    const dateDepartDisplay = document.getElementById('planning-date-depart');
    const heureDepartDisplay= document.getElementById('planning-heure-depart');
    const dateRetourDisplay = document.getElementById('planning-date-retour');
    const heureRetourDisplay= document.getElementById('planning-heure-retour');
    const placesDisplay     = document.getElementById('planning-places');
    const prixDisplay       = document.getElementById('planning-prix');
    const deviseDisplay     = document.getElementById('planning-devise');
    const statutDisplay     = document.getElementById('planning-statut');

    if (!destinationSelect || !planningSelect || !planningInfo) {
        return;
    }

    const planningActuel = <?= json_encode(old('planning_id', $reservation['planning_id'] ?? '')) ?>;

    function resetPlanningInfo() {
        planningInfo.classList.add('d-none');
        if (dateDepartDisplay)  dateDepartDisplay.textContent  = '—';
        if (heureDepartDisplay) heureDepartDisplay.textContent = '—';
        if (dateRetourDisplay)  dateRetourDisplay.textContent  = '—';
        if (heureRetourDisplay) heureRetourDisplay.textContent = '—';
        if (placesDisplay)      placesDisplay.textContent      = '—';
        if (prixDisplay)        prixDisplay.textContent        = '—';
        if (deviseDisplay)      deviseDisplay.textContent      = 'MGA';
        if (statutDisplay)      statutDisplay.textContent      = '—';
    }

    function formaterDate(date) {
        if (!date) return '—';
        const parts = date.substring(0, 10).split('-');
        if (parts.length !== 3) return date;
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }

    function chargerDetailsPlanning(planningId) {
        if (!planningId) {
            resetPlanningInfo();
            return;
        }

        fetch('<?= site_url('reservations/planning') ?>/' + encodeURIComponent(planningId))
            .then(response => {
                if (!response.ok) throw new Error('Erreur HTTP');
                return response.json();
            })
            .then(result => {
                if (!result.success || !result.data) {
                    resetPlanningInfo();
                    return;
                }

                const p = result.data;

                if (dateDepartDisplay)  dateDepartDisplay.textContent  = formaterDate(p.date_depart);
                if (dateRetourDisplay)  dateRetourDisplay.textContent  = formaterDate(p.date_retour);
                if (heureDepartDisplay) heureDepartDisplay.textContent = p.heure_depart ? p.heure_depart.substring(0, 5) : '—';
                if (heureRetourDisplay) heureRetourDisplay.textContent = p.heure_retour ? p.heure_retour.substring(0, 5) : '—';

                if (dateDepartInput) dateDepartInput.value = p.date_depart || '';
                if (dateRetourInput) dateRetourInput.value = p.date_retour || '';

                if (placesDisplay) placesDisplay.textContent = (p.places_disponibles ?? 0) + ' place(s)';
                if (prixDisplay)   prixDisplay.textContent   = Number(p.prix || 0).toLocaleString('fr-FR');
                if (deviseDisplay) deviseDisplay.textContent = p.devise || 'MGA';
                if (statutDisplay) statutDisplay.textContent = p.statut || '—';

                planningInfo.classList.remove('d-none');

                const devise = document.querySelector('[name="devise"]');
                if (devise && p.devise) {
                    devise.value = p.devise;
                }
            })
            .catch(error => {
                console.error('Erreur chargement planning :', error);
                resetPlanningInfo();
            });
    }

    function chargerPlannings(destinationId) {
        planningSelect.innerHTML = `<option value="">Chargement...</option>`;
        resetPlanningInfo();

        if (!destinationId) {
            planningSelect.innerHTML = `<option value="">Sélectionner une destination</option>`;
            return;
        }

        let url = '<?= site_url('plannings-depart/available') ?>'
                + '?destination_id=' + encodeURIComponent(destinationId);

        if (planningActuel) {
            url += '&planning_id=' + encodeURIComponent(planningActuel);
        }

        fetch(url)
            .then(r => r.json())
            .then(result => {
                planningSelect.innerHTML = `<option value="">Sélectionner un départ</option>`;

                let trouve = false;
                const liste = (result.success && Array.isArray(result.data)) ? result.data : [];

                liste.forEach(planning => {
                    const option = document.createElement('option');
                    option.value = planning.id;

                    let texte = '';
                    if (planning.date_depart) {
                        texte = formaterDate(planning.date_depart);
                    }
                    if (planning.heure_depart) {
                        texte += ' à ' + planning.heure_depart.substring(0, 5);
                    }
                    texte += ' — ' + (planning.places_disponibles ?? 0) + ' place(s)';

                    option.textContent = texte;

                    if (String(planning.id) === String(planningActuel)) {
                        option.selected = true;
                        trouve = true;
                    }

                    planningSelect.appendChild(option);
                });

                // Si le planning actuel n'est pas dans la liste → on le force
                if (planningActuel && !trouve) {
                    fetch('<?= site_url('reservations/planning') ?>/' + encodeURIComponent(planningActuel))
                        .then(r => r.json())
                        .then(res => {
                            if (res.success && res.data) {
                                const p = res.data;
                                const option = document.createElement('option');
                                option.value = p.id;

                                let texte = formaterDate(p.date_depart);
                                if (p.heure_depart) {
                                    texte += ' à ' + p.heure_depart.substring(0, 5);
                                }
                                texte += ' — (planning actuel)';

                                option.textContent = texte;
                                option.selected = true;

                                // Insertion juste après l'option vide
                                if (planningSelect.children.length > 0) {
                                    planningSelect.insertBefore(option, planningSelect.children[1]);
                                } else {
                                    planningSelect.appendChild(option);
                                }

                                chargerDetailsPlanning(planningActuel);
                            }
                        })
                        .catch(err => console.error('Impossible de récupérer le planning actuel', err));
                }
                else if (planningActuel && trouve) {
                    planningSelect.value = planningActuel;
                    chargerDetailsPlanning(planningActuel);
                }
            })
            .catch(err => {
                console.error(err);
                planningSelect.innerHTML = `<option value="">Erreur de chargement</option>`;
            });
    }

    destinationSelect.addEventListener('change', function () {
        chargerPlannings(this.value);
    });

    planningSelect.addEventListener('change', function () {
        chargerDetailsPlanning(this.value);
    });

    // Chargement initial
    if (destinationSelect.value) {
        chargerPlannings(destinationSelect.value);
    } else {
        resetPlanningInfo();
    }
});
</script>

<!-- =========================================================
     JAVASCRIPT - LIGNES / CATALOGUES
========================================================= -->
<script>
const catalogues = <?= json_encode(
    $catalogues ?? [],
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
) ?>;

document.addEventListener('DOMContentLoaded', function () {

    const lignesBody  = document.getElementById('lignes-body');
    const btnAddLigne = document.getElementById('btn-add-ligne');

    let ligneIndex = lignesBody
        ? lignesBody.querySelectorAll('.ligne-row').length
        : 0;

    // ------------------------------------------------------------------
    // Remplir le select catalogue
    // ------------------------------------------------------------------
    function chargerCatalogue(row) {
        const typeSelect      = row.querySelector('.type-prestation');
        const catalogueSelect = row.querySelector('.catalogue-prestation');

        if (!typeSelect || !catalogueSelect) return;

        const type = typeSelect.value;
        catalogueSelect.innerHTML = '';

        if (!type) {
            catalogueSelect.innerHTML = `<option value="">Choisir d'abord un type</option>`;
            catalogueSelect.disabled = true;
            return;
        }

        if (type === 'autre') {
            catalogueSelect.innerHTML = `<option value="">Prestation personnalisée</option>`;
            catalogueSelect.disabled = true;
            return;
        }

        const liste = catalogues[type] || [];

        if (liste.length === 0) {
            catalogueSelect.innerHTML = `<option value="">Aucun catalogue disponible</option>`;
            catalogueSelect.disabled = true;
            return;
        }

        catalogueSelect.innerHTML = `<option value="">Sélectionner un catalogue</option>`;

        liste.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.label + ' — ' + Number(item.prix || 0).toLocaleString('fr-FR');
            option.dataset.label = item.label || '';
            option.dataset.prix  = item.prix || 0;
            catalogueSelect.appendChild(option);
        });

        catalogueSelect.disabled = false;

        // Re-sélection de la valeur existante (mode édition)
        const selectedId = catalogueSelect.dataset.selected;
        if (selectedId) {
            catalogueSelect.value = selectedId;
            // On ne force PAS la mise à jour du prix/désignation
            // car on veut conserver les valeurs déjà enregistrées
        }
    }

    // ------------------------------------------------------------------
    // Quand on change de catalogue
    // ------------------------------------------------------------------
    function selectionnerCatalogue(row) {
        const catalogueSelect = row.querySelector('.catalogue-prestation');
        const designation     = row.querySelector('.designation');
        const prix            = row.querySelector('.prix-unitaire');

        if (!catalogueSelect || !designation || !prix) return;

        const option = catalogueSelect.options[catalogueSelect.selectedIndex];

        if (!option || !catalogueSelect.value) {
            // On ne vide pas forcément en édition
            calculerLigne(row);
            return;
        }

        designation.value = option.dataset.label || '';
        prix.value        = parseFloat(option.dataset.prix || 0).toFixed(2);

        calculerLigne(row);
    }

    // ------------------------------------------------------------------
    // Calcul d'une ligne
    // ------------------------------------------------------------------
    function calculerLigne(row) {
        const quantite = parseFloat(row.querySelector('.quantite')?.value || 0);
        const prix     = parseFloat(row.querySelector('.prix-unitaire')?.value || 0);
        const total    = quantite * prix;

        const totalElement = row.querySelector('.prix-total');
        if (totalElement) {
            totalElement.textContent = total.toLocaleString('fr-FR', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }

        calculerTotal();
    }

    // ------------------------------------------------------------------
    // Total général
    // ------------------------------------------------------------------
    function calculerTotal() {
        let total = 0;

        lignesBody.querySelectorAll('.ligne-row').forEach(row => {
            const quantite = parseFloat(row.querySelector('.quantite')?.value || 0);
            const prix     = parseFloat(row.querySelector('.prix-unitaire')?.value || 0);
            total += quantite * prix;
        });

        const totalElement = document.getElementById('total-lignes');
        const montantInput = document.getElementById('montant_total');

        if (totalElement) {
            totalElement.textContent = total.toLocaleString('fr-FR', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }

        if (montantInput) {
            montantInput.value = total.toFixed(2);
        }
    }

    // ------------------------------------------------------------------
    // Initialiser une ligne (événements)
    // ------------------------------------------------------------------
    function initialiserLigne(row) {
        const typeSelect      = row.querySelector('.type-prestation');
        const catalogueSelect = row.querySelector('.catalogue-prestation');
        const quantite        = row.querySelector('.quantite');
        const prix            = row.querySelector('.prix-unitaire');
        const removeButton    = row.querySelector('.btn-remove-ligne');

        if (typeSelect) {
            typeSelect.addEventListener('change', function () {
                // On efface data-selected car l'utilisateur change de type
                if (catalogueSelect) {
                    delete catalogueSelect.dataset.selected;
                }
                chargerCatalogue(row);
            });
        }

        if (catalogueSelect) {
            catalogueSelect.addEventListener('change', function () {
                selectionnerCatalogue(row);
            });
        }

        if (quantite) {
            quantite.addEventListener('input', function () {
                calculerLigne(row);
            });
        }

        if (prix) {
            prix.addEventListener('input', function () {
                calculerLigne(row);
            });
        }

        if (removeButton) {
            removeButton.addEventListener('click', function () {
                row.remove();
                calculerTotal();
            });
        }

        // Initialisation
        chargerCatalogue(row);
        calculerLigne(row);
    }

    // ------------------------------------------------------------------
    // Ajouter une nouvelle ligne
    // ------------------------------------------------------------------
    if (btnAddLigne) {
        btnAddLigne.addEventListener('click', function () {
            const index = ligneIndex++;

            const row = document.createElement('tr');
            row.className = 'ligne-row';

            row.innerHTML = `
                <td>
                    <select name="lignes[${index}][type_prestation]" class="form-select form-select-sm type-prestation">
                        <option value="">Type...</option>
                        <option value="hotel">Hôtel</option>
                        <option value="vol">Vol</option>
                        <option value="excursion">Excursion</option>
                        <option value="transfert">Transfert</option>
                        <option value="restaurant">Restaurant</option>
                        <option value="croisiere">Croisière</option>
                        <option value="forfait">Forfait</option>
                        <option value="circuit">Circuit</option>
                        <option value="autre">Autre</option>
                    </select>
                </td>
                <td>
                    <select name="lignes[${index}][prestation_id]" class="form-select form-select-sm catalogue-prestation" disabled>
                        <option value="">Choisir d'abord un type</option>
                    </select>
                    <input type="hidden" name="lignes[${index}][designation]" class="designation" value="">
                </td>
                <td>
                    <input type="number" name="lignes[${index}][quantite]" class="form-control form-control-sm quantite" min="1" value="1">
                </td>
                <td>
                    <input type="number" name="lignes[${index}][prix_unitaire]" class="form-control form-control-sm prix-unitaire" step="0.01" min="0" value="0">
                </td>
                <td>
                    <span class="prix-total fw-semibold">0</span>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-ligne">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;

            lignesBody.appendChild(row);
            initialiserLigne(row);
        });
    }

    // ------------------------------------------------------------------
    // Initialiser toutes les lignes existantes
    // ------------------------------------------------------------------
    lignesBody.querySelectorAll('.ligne-row').forEach(function (row) {
        initialiserLigne(row);
    });

    // Premier calcul
    calculerTotal();
});
</script>

<?= $this->endSection() ?>