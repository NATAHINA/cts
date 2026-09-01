<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <div class="text-muted small">
            Cotation
        </div>

        <h4 class="mb-1">
            <?= esc($cotation['numero']) ?>
        </h4>

        <div class="text-muted">
            <?= esc(
                trim(
                    ($cotation['client_prenom'] ?? '')
                    . ' '
                    . ($cotation['client_nom'] ?? '')
                )
            ) ?>
        </div>

    </div>


    <div class="d-flex gap-2">

    <form
    method="post"
    action="<?= site_url(
        'cotations/' . $cotation['id'] . '/recalculer'
    ) ?>"
>
    <?= csrf_field() ?>

    <button
        type="submit"
        class="btn btn-light border"
    >
        <i class="bi bi-arrow-clockwise me-1"></i>
        Recalculer
    </button>
</form>

    <?php if (($cotation['statut'] ?? '') === 'acceptee'): ?>

        <?php if (! empty($reservationExistante)): ?>
            <a href="<?= site_url('reservations/' . $reservationExistante['id']) ?>"
            class="btn btn-outline-success">
                <i class="bi bi-calendar-check me-1"></i>
                Voir la réservation
                <?= esc($reservationExistante['numero'] ?? '') ?>
            </a>
        <?php else: ?>
            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#convertReservationModal"
            >
                <i class="bi bi-calendar-check me-1"></i>
                Convertir en réservation
            </button>
        <?php endif; ?>

    <?php endif; ?>


        <a
            href="<?= site_url(
                'cotations/' . $cotation['id'] . '/edit'
            ) ?>"
            class="btn btn-info border"
        >
            <i class="bi bi-pencil me-1"></i>
            Modifier
        </a>

        <a
            href="<?= site_url(
                'cotations/' . $cotation['id'] . '/print'
            ) ?>"
            target="_blank"
            class="btn lc-btn-primary"
        >
            <i class="bi bi-printer me-1"></i>
            Imprimer
        </a>

    </div>

</div>


<div class="row g-3">

    <div class="col-lg-8">


        <div class="lc-card p-4 mb-3">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h6 class="mb-0">
                    Prestations
                </h6>

                <button
                    type="button"
                    class="btn lc-btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAjouterPrestation"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Ajouter une prestation
                </button>

            </div>


            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Prestation</th>
                            <th>Fournisseur</th>
                            <th class="text-center">Qté</th>
                            <th class="text-end">Coût unit.</th>
                            <th class="text-end">Coût total</th>
                            <th class="text-end">Marge %</th>
                            <th class="text-end">Marge</th>
                            <th class="text-end">Prix unit.</th>
                            <th class="text-end">Prix total</th>
                            <th></th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php if (empty($lignes)): ?>

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-4"
                                >
                                    Aucune prestation ajoutée.

                                </td>

                            </tr>

                        <?php endif; ?>


                        <?php foreach ($lignes as $index => $ligne): ?>

                            <tr>

                            <td>
                                <?= $index + 1 ?>
                            </td>

                                <td>

                                    <div class="fw-semibold">
                                            <?= esc($ligne['designation'] ?? '-') ?>
                                        </div>

                                        <?php if (!empty($ligne['description'])): ?>

                                            <small class="text-muted">
                                                <?= esc($ligne['description']) ?>
                                            </small>

                                        <?php endif; ?>

                                    <?php if (!empty($ligne['type_prestation'])): ?>

                                            <div>
                                                <span class="badge bg-light text-dark border mt-1">

                                                    <?= esc(
                                                        ucfirst(
                                                            $ligne['type_prestation']
                                                        )
                                                    ) ?>

                                                </span>
                                            </div>

                                        <?php endif; ?>
                                </td>

                                <td>

                                    <?= esc(
                                        $ligne['fournisseur_nom'] ?? '-'
                                    ) ?>

                                </td>


                                <td class="text-end">

                                    <?= number_format(
                                        (float) $ligne['quantite'],
                                        2,
                                        ',',
                                        ' '
                                    ) ?>

                                </td>

                                <td class="text-end">

                                        <?= number_format(
                                            (float) ($ligne['cout_unitaire'] ?? 0),
                                            2,
                                            ',',
                                            ' '
                                        ) ?>

                                        <small class="text-muted">
                                            <?= esc(
                                                $ligne['devise']
                                                ?? $cotation['devise']
                                                ?? ''
                                            ) ?>
                                        </small>

                                    </td>
                                 <td class="text-end">

                                        <?= number_format(
                                            (float) ($ligne['cout_total'] ?? 0),
                                            2,
                                            ',',
                                            ' '
                                        ) ?>

                                    </td>

                                <td class="text-end">
                                    <?= number_format((float)($ligne['marge_pourcentage'] ?? 0), 1, ',', ' ') ?> %
                                </td>
                                <td class="text-end">
                                    <?= number_format((float)($ligne['marge_montant'] ?? 0), 2, ',', ' ') ?>
                                </td>


                                <td class="text-end">

                                        <?= number_format((float) ($ligne['prix_unitaire'] ?? 0),2,',',' ') ?>

                                    </td>


                                    <td class="text-end fw-semibold">

                                        <?= number_format(
                                            (float) ($ligne['prix_total'] ?? 0),
                                            2,
                                            ',',
                                            ' '
                                        ) ?>

                                        <small class="text-muted">
                                            <?= esc(
                                                $ligne['devise']
                                                ?? $cotation['devise']
                                                ?? ''
                                            ) ?>
                                        </small>

                                    </td>


                                <td class="text-end">

                                <!-- Bouton Dupliquer -->
                                <form
                                    method="post"
                                    action="<?= site_url(
                                        'cotations/' .
                                        $cotation['id'] .
                                        '/lignes/' .
                                        $ligne['id'] .
                                        '/duplicate'
                                    ) ?>"
                                    class="d-inline"
                                >
                                    <?= csrf_field() ?>

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-light border"
                                        title="Dupliquer"
                                    >
                                        <i class="bi bi-copy"></i>
                                    </button>

                                </form>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light border"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editLigneModal<?= $ligne['id'] ?>"
                                        title="Modifier"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <form
                                        method="post"
                                        action="<?= site_url(
                                            'cotations/' .
                                            $cotation['id'] .
                                            '/lignes/' .
                                            $ligne['id'] .
                                            '/delete'
                                        ) ?>"
                                        class="d-inline"
                                    >
                                        <?= csrf_field() ?>

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-light border text-danger"
                                            onclick="return confirm('Supprimer cette prestation ?')"
                                            title="Supprimer"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                    <?php if (!empty($lignes)): ?>

                        <tfoot class="table-light">

                            <tr>

                                <th colspan="5"
                                    class="text-end">

                                    TOTAL GÉNÉRAL

                                </th>


                                <th class="text-end">

                                    <?= number_format(
                                        (float) ($cotation['cout_total'] ?? 0),
                                        2,
                                        ',',
                                        ' '
                                    ) ?>

                                    <?= esc(
                                        $cotation['devise'] ?? ''
                                    ) ?>

                                </th>


                                <th></th>

                                <th></th>


                                <th class="text-end fs-6">

                                    <?= number_format(
                                        (float) ($cotation['prix_total'] ?? 0),
                                        2,
                                        ',',
                                        ' '
                                    ) ?>

                                    <?= esc(
                                        $cotation['devise'] ?? ''
                                    ) ?>

                                </th>


                                <th></th>

                            </tr>

                        </tfoot>

                    <?php endif; ?>


                </table>

            </div>

        </div>


    </div>




    <div class="col-lg-4">


        <?php
        $devise = $cotation['devise'] ?? 'EUR';
        $coutTotal   = (float) ($cotation['cout_total'] ?? 0);
        $margeMontant = (float) ($cotation['marge_montant'] ?? 0);
        $margePct    = (float) ($cotation['marge_pourcentage'] ?? 0);
        $prixTotal   = (float) ($cotation['prix_total'] ?? 0);
        $reduction   = (float) ($cotation['reduction_montant'] ?? 0);
        $taxe        = (float) ($cotation['taxe_montant'] ?? 0);
        ?>

        <div class="lc-card p-4">
            <h6 class="fw-bold mb-3">Récapitulatif</h6>

            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Coût total</span>
                <span class="fw-semibold">
                    <?= number_format($coutTotal, 2, ',', ' ') ?> <?= esc($devise) ?>
                </span>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Marge</span>
                <span class="fw-semibold">
                    <?= number_format($margeMontant, 2, ',', ' ') ?> <?= esc($devise) ?>
                    <small class="text-muted">(<?= number_format($margePct, 1, ',', ' ') ?> %)</small>
                </span>
            </div>

            <?php if ($reduction > 0): ?>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Réduction</span>
                <span class="text-danger">
                    − <?= number_format($reduction, 2, ',', ' ') ?> <?= esc($devise) ?>
                </span>
            </div>
            <?php endif; ?>

            <?php if ($taxe > 0): ?>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Taxe</span>
                <span><?= number_format($taxe, 2, ',', ' ') ?> <?= esc($devise) ?></span>
            </div>
            <?php endif; ?>

            <hr>

            <div class="d-flex justify-content-between">
                <span class="fw-bold">Prix total</span>
                <span class="fw-bold fs-5" style="color:var(--lc-accent);">
                    <?= number_format($prixTotal, 2, ',', ' ') ?> <?= esc($devise) ?>
                </span>
            </div>

            <?php if (!empty($cotation['prix_par_personne'])): ?>
            <div class="d-flex justify-content-between mt-2">
                <span class="text-muted">Par personne</span>
                <span>
                    <?= number_format((float)$cotation['prix_par_personne'], 2, ',', ' ') ?>
                    <?= esc($devise) ?>
                </span>
            </div>
            <?php endif; ?>
        </div>


        <div class="lc-card p-4">

            <h6 class="mb-3">
                Voyageurs
            </h6>

            <div class="d-flex justify-content-between">
                <span>Adultes</span>
                <strong><?= (int) $cotation['nb_adultes'] ?></strong>
            </div>

            <div class="d-flex justify-content-between">
                <span>Enfants</span>
                <strong><?= (int) $cotation['nb_enfants'] ?></strong>
            </div>

            <div class="d-flex justify-content-between">
                <span>Bébés</span>
                <strong><?= (int) $cotation['nb_bebes'] ?></strong>
            </div>

        </div>


    </div>

</div>


<div
    class="modal fade"
    id="modalAjouterPrestation"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <form
            method="post"
            action="<?= site_url('cotations/' . $cotation['id'] . '/lignes') ?>"
        >

            <?= csrf_field() ?>

            <div class="modal-content">

                <div class="modal-header">

                    <div>
                        <h5 class="modal-title mb-1">
                            Ajouter une prestation
                        </h5>

                        <div class="text-muted small">
                            Ajoutez une prestation à cette cotation.
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">

                        <!-- TYPE -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Type de prestation *
                            </label>

                            <select
                                name="type_prestation"
                                id="type_prestation"
                                class="form-select"
                                required
                            >

                                <option value="">Sélectionner</option>
                                <option value="hotel">Hôtel</option>
                                <option value="restaurant">Restaurant</option>

                                <option value="vol">Vol
                                </option>

                                <option value="excursion">
                                    Excursion
                                </option>

                                <option value="transfert">
                                    Transfert
                                </option>

                                <option value="croisiere">
                                    Croisière
                                </option>

                                <option value="forfait">
                                    Forfait
                                </option>

                                <option value="circuit">
                                    Circuit
                                </option>

                                <option value="autre">
                                    Autre prestation
                                </option>

                            </select>

                        </div>


                        <!-- PRESTATION -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Prestation du catalogue
                            </label>

                            <select
                                name="prestation_id"
                                id="prestation_id"
                                class="form-select"
                            >

                                <option value="">
                                    Sélectionner d'abord un type
                                </option>

                            </select>

                        </div>


                        <!-- DESIGNATION -->

                        <div class="col-12">

                            <label class="form-label">
                                Désignation *
                            </label>

                            <input
                                type="text"
                                name="designation"
                                id="designation"
                                class="form-control"
                                required
                            >

                        </div>


                        <!-- DESCRIPTION -->

                        <div class="col-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="2"
                            ></textarea>

                        </div>


                        <!-- QUANTITE -->

                        <div class="col-md-4">

                            <label class="form-label">
                                Quantité *
                            </label>

                            <input
                                type="number"
                                name="quantite"
                                id="quantite"
                                class="form-control"
                                min="1"
                                value="1"
                                required
                            >

                        </div>


                        <!-- COUT -->

                        <div class="col-md-4">

                            <label class="form-label">
                                Coût unitaire
                            </label>

                            <input
                                type="number"
                                name="cout_unitaire"
                                id="cout_unitaire"
                                class="form-control"
                                step="0.01"
                                min="0"
                                value="0"
                            >

                        </div>


                        <!-- MARGE -->

                        <div class="col-md-4">

                            <label class="form-label">
                                Marge (%)
                            </label>

                            <input
                                type="number"
                                name="marge_pourcentage"
                                id="marge_pourcentage"
                                class="form-control"
                                step="0.01"
                                min="0"
                                value="0"
                            >

                        </div>


                        <!-- PRIX -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Prix unitaire
                            </label>

                            <input
                                type="number"
                                name="prix_unitaire"
                                id="prix_unitaire"
                                class="form-control"
                                step="0.01"
                                min="0"
                                value="0"
                            >

                            <div class="form-text">
                                Calculé automatiquement selon le coût et la marge.
                            </div>

                        </div>


                        <!-- TOTAL -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Prix total
                            </label>

                            <input
                                type="number"
                                id="prix_total_affichage"
                                class="form-control"
                                readonly
                                value="0"
                            >

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal"
                    >
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="btn lc-btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Ajouter la prestation
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>



<!-- Modal Modification -->
 <?php foreach ($lignes as $ligne): ?>
    <div
        class="modal fade text-start"
        id="editLigneModal<?= $ligne['id'] ?>"
        tabindex="-1"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form
                    method="post"
                    action="<?= site_url(
                        'cotations/' .
                        $cotation['id'] .
                        '/lignes/' .
                        $ligne['id']
                    ) ?>"
                >

                    <?= csrf_field() ?>

                    <div class="modal-header">

                        <h5 class="modal-title">
                            <i class="bi bi-pencil-square me-2"></i>
                            Modifier la prestation
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>

                    </div>


                    <div class="modal-body">

                        <div class="row g-3">

                            <!-- Désignation -->
                            <div class="col-md-8">

                                <label class="form-label">
                                    Désignation
                                </label>

                                <input
                                    type="text"
                                    name="designation"
                                    class="form-control"
                                    value="<?= esc($ligne['designation'] ?? '') ?>"
                                    required
                                >

                            </div>


                            <!-- Quantité -->
                            <div class="col-md-4">

                                <label class="form-label">
                                    Quantité
                                </label>

                                <input
                                    type="number"
                                    name="quantite"
                                    class="form-control"
                                    min="0.01"
                                    step="0.01"
                                    value="<?= esc($ligne['quantite'] ?? 1) ?>"
                                    required
                                >

                            </div>


                            <!-- Type prestation -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Type de prestation
                                </label>

                                <select
                                    name="type_prestation"
                                    class="form-select"
                                >

                                    <?php
                                    $types = [
                                        'hotel' => 'Hôtel',
                                        'restaurant' => 'Restaurant',
                                        'vol' => 'Vol',
                                        'excursion' => 'Excursion',
                                        'transfert' => 'Transfert',
                                        'croisiere' => 'Croisière',
                                        'forfait' => 'Forfait',
                                        'circuit' => 'Circuit',
                                        'autre' => 'Autre',
                                    ];
                                    ?>

                                    <?php foreach ($types as $value => $label): ?>

                                        <option
                                            value="<?= $value ?>"
                                            <?= ($ligne['type_prestation'] ?? '') === $value
                                                ? 'selected'
                                                : '' ?>
                                        >
                                            <?= esc($label) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <!-- Devise -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Devise
                                </label>

                                <select
                                    name="devise"
                                    class="form-select"
                                >

                                    <option
                                        value="MGA"
                                        <?= ($ligne['devise'] ?? '') === 'MGA'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        MGA
                                    </option>

                                    <option
                                        value="EUR"
                                        <?= ($ligne['devise'] ?? '') === 'EUR'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        EUR
                                    </option>

                                    <option
                                        value="USD"
                                        <?= ($ligne['devise'] ?? '') === 'USD'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        USD
                                    </option>

                                </select>

                            </div>


                            <!-- Coût unitaire -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Coût unitaire
                                </label>

                                <input
                                    type="number"
                                    name="cout_unitaire"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    value="<?= esc($ligne['cout_unitaire'] ?? 0) ?>"
                                >

                            </div>


                            <!-- Prix unitaire -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Prix unitaire
                                </label>

                                <input
                                    type="number"
                                    name="prix_unitaire"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    value="<?= esc($ligne['prix_unitaire'] ?? 0) ?>"
                                    required
                                >

                            </div>


                            <!-- Marge -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Marge (%)
                                </label>

                                <input
                                    type="number"
                                    name="marge_pourcentage"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    value="<?= esc($ligne['marge_pourcentage'] ?? 0) ?>"
                                >

                            </div>


                            <!-- Ordre -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Ordre
                                </label>

                                <input
                                    type="number"
                                    name="ordre"
                                    class="form-control"
                                    min="1"
                                    value="<?= esc($ligne['ordre'] ?? 1) ?>"
                                >

                            </div>


                            <!-- Description -->
                            <div class="col-12">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="3"
                                ><?= esc($ligne['description'] ?? '') ?></textarea>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-light border"
                            data-bs-dismiss="modal"
                        >
                            Annuler
                        </button>

                        <button
                            type="submit"
                            class="btn lc-btn-primary"
                        >
                            <i class="bi bi-check-lg me-1"></i>
                            Enregistrer les modifications
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
<?php endforeach; ?>




    <div
    class="modal fade"
    id="convertReservationModal"
    tabindex="-1"
    aria-labelledby="convertReservationModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="convertReservationModalLabel"
                >
                    <i class="bi bi-calendar-check me-2"></i>
                    Convertir en réservation
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <p>
                    Voulez-vous convertir la cotation
                    <strong>
                        <?= esc($cotation['numero']) ?>
                    </strong>
                    en réservation ?
                </p>

                <div class="alert alert-info mb-0">

                    <i class="bi bi-info-circle me-1"></i>

                    Une réservation sera créée avec les informations
                    du client, du voyage et les prestations de cette
                    cotation.

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal"
                >
                    Annuler
                </button>


                <form
                    method="post"
                    action="<?= site_url(
                        'cotations/' .
                        $cotation['id'] .
                        '/convert-to-reservation'
                    ) ?>"
                >

                    <?= csrf_field() ?>

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Oui, créer la réservation
                    </button>

                </form>

            </div>

        </div>

    </div>
</div>

<script>

const catalogue = <?= json_encode(
    $catalogue,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
) ?>;


const typeSelect = document.getElementById('type_prestation');
const prestationSelect = document.getElementById('prestation_id');

const designationInput = document.getElementById('designation');
const coutInput = document.getElementById('cout_unitaire');
const margeInput = document.getElementById('marge_pourcentage');
const prixInput = document.getElementById('prix_unitaire');
const prixTotalInput = document.getElementById('prix_total_affichage');
const quantiteInput = document.getElementById('quantite');


typeSelect.addEventListener('change', function () {

    const type = this.value;

    prestationSelect.innerHTML =
        '<option value="">Sélectionner une prestation</option>';


    if (!catalogue[type]) {
        return;
    }


    catalogue[type].forEach(function (item) {

        const option = document.createElement('option');

        option.value = item.id;

        option.dataset.label = item.label;
        option.dataset.prix = item.prix;

        option.textContent =
            item.label + ' — ' +
            Number(item.prix).toLocaleString('fr-FR');

        prestationSelect.appendChild(option);

    });

});


prestationSelect.addEventListener('change', function () {

    const option =
        this.options[this.selectedIndex];


    if (!option || !option.value) {
        return;
    }


    const label = option.dataset.label || '';
    const prix = parseFloat(option.dataset.prix || 0);


    designationInput.value = label;

    coutInput.value = prix.toFixed(2);


    calculerPrix();

});


function calculerPrix() {

    const quantite =
        parseFloat(quantiteInput.value || 0);

    const cout =
        parseFloat(coutInput.value || 0);

    const marge =
        parseFloat(margeInput.value || 0);


    const prixUnitaire =
        cout + (cout * marge / 100);


    const prixTotal =
        prixUnitaire * quantite;


    prixInput.value =
        prixUnitaire.toFixed(2);

    prixTotalInput.value =
        prixTotal.toFixed(2);

}


quantiteInput.addEventListener('input', calculerPrix);
coutInput.addEventListener('input', calculerPrix);
margeInput.addEventListener('input', calculerPrix);

</script>

<?= $this->endSection() ?>