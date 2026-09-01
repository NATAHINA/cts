<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$isEdit = !empty($fournisseur['id']);
$action = $isEdit
    ? site_url('fournisseurs/' . $fournisseur['id'])
    : site_url('fournisseurs');

$types = [
    'hotel'               => 'Hôtel',
    'compagnie_aerienne'  => 'Compagnie aérienne',
    'transport'           => 'Transport / Transfert',
    'excursion'           => 'Excursion / Activité',
    'croisiere'           => 'Croisière',
    'restaurant'          => 'Restaurant',
    'guide'               => 'Guide',
    'autre'               => 'Autre',
];

$statuts = [
    'actif'   => 'Actif',
    'inactif' => 'Inactif',
];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">
            <?= $isEdit ? 'Modifier le fournisseur' : 'Nouveau fournisseur' ?>
        </h4>
        <p class="text-muted mb-0">
            Renseignez les informations du prestataire.
        </p>
    </div>

    <a href="<?= site_url('fournisseurs') ?>"
       class="btn btn-light border">
        <i class="bi bi-arrow-left me-1"></i>
        Retour
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= $action ?>">

    <?= csrf_field() ?>

    <div class="lc-card p-4 mb-4">

        <h6 class="fw-bold mb-4">
            <i class="bi bi-building me-2"></i>
            Informations générales
        </h6>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">
                    Type de fournisseur <span class="text-danger">*</span>
                </label>

                <select name="type" class="form-select" required>
                    <option value="">Sélectionnez un type</option>

                    <?php foreach ($types as $value => $label): ?>
                        <option value="<?= esc($value) ?>"
                            <?= old('type', $fournisseur['type'] ?? '') === $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Nom du fournisseur <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="nom"
                       class="form-control"
                       value="<?= esc(old('nom', $fournisseur['nom'] ?? '')) ?>"
                       placeholder="Ex. Madagascar Tours"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Nom du contact
                </label>

                <input type="text"
                       name="contact_nom"
                       class="form-control"
                       value="<?= esc(old('contact_nom', $fournisseur['contact_nom'] ?? '')) ?>"
                       placeholder="Nom de la personne à contacter">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Devise
                </label>

                <select name="devise" class="form-select">
                    <?php
                    $devise = old('devise', $fournisseur['devise'] ?? 'EUR');

                    $devises = [
                        'EUR',
                        'USD',
                        'MGA',
                        'GBP',
                    ];
                    ?>

                    <?php foreach ($devises as $item): ?>
                        <option value="<?= $item ?>"
                            <?= $devise === $item ? 'selected' : '' ?>>
                            <?= $item ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

    </div>


    <div class="lc-card p-4 mb-4">

        <h6 class="fw-bold mb-4">
            <i class="bi bi-telephone me-2"></i>
            Coordonnées
        </h6>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Téléphone</label>

                <input type="text"
                       name="telephone"
                       class="form-control"
                       value="<?= esc(old('telephone', $fournisseur['telephone'] ?? '')) ?>"
                       placeholder="+261 ...">
            </div>

            <div class="col-md-6">
                <label class="form-label">E-mail</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="<?= esc(old('email', $fournisseur['email'] ?? '')) ?>"
                       placeholder="contact@example.com">
            </div>

            <div class="col-md-6">
                <label class="form-label">Pays</label>

                <input type="text"
                       name="pays"
                       class="form-control"
                       value="<?= esc(old('pays', $fournisseur['pays'] ?? '')) ?>"
                       placeholder="Madagascar">
            </div>

            <div class="col-md-6">
                <label class="form-label">Ville</label>

                <input type="text"
                       name="ville"
                       class="form-control"
                       value="<?= esc(old('ville', $fournisseur['ville'] ?? '')) ?>"
                       placeholder="Antananarivo">
            </div>

            <div class="col-12">
                <label class="form-label">Adresse</label>

                <textarea name="adresse"
                          class="form-control"
                          rows="3"
                          placeholder="Adresse complète"><?= esc(old('adresse', $fournisseur['adresse'] ?? '')) ?></textarea>
            </div>

        </div>

    </div>


    <div class="lc-card p-4 mb-4">

        <h6 class="fw-bold mb-4">
            <i class="bi bi-credit-card me-2"></i>
            Informations commerciales
        </h6>

        <div class="row g-3">

            <div class="col-12">
                <label class="form-label">
                    Conditions de paiement
                </label>

                <textarea name="conditions_paiement"
                          class="form-control"
                          rows="3"
                          placeholder="Ex. Paiement à 30 jours"><?= esc(old('conditions_paiement', $fournisseur['conditions_paiement'] ?? '')) ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Statut
                </label>

                <?php $statut = old('statut', $fournisseur['statut'] ?? 'actif'); ?>

                <select name="statut" class="form-select">

                    <?php foreach ($statuts as $value => $label): ?>
                        <option value="<?= $value ?>"
                            <?= $statut === $value ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

        </div>

    </div>


    <div class="lc-card p-4 mb-4">

        <h6 class="fw-bold mb-3">
            <i class="bi bi-journal-text me-2"></i>
            Notes
        </h6>

        <textarea name="notes"
                  class="form-control"
                  rows="4"
                  placeholder="Informations complémentaires..."><?= esc(old('notes', $fournisseur['notes'] ?? '')) ?></textarea>

    </div>


    <div class="d-flex justify-content-end gap-2">

        <a href="<?= site_url('fournisseurs') ?>"
           class="btn btn-light border">
            Annuler
        </a>

        <button type="submit" class="btn lc-btn-primary">
            <i class="bi bi-check-lg me-1"></i>

            <?= $isEdit
                ? 'Enregistrer les modifications'
                : 'Enregistrer le fournisseur' ?>
        </button>

    </div>

</form>

<?= $this->endSection() ?>