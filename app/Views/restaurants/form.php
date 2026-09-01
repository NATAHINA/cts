<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = ! empty($restaurant); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?= $isEdit ? 'Modifier le restaurant' : 'Nouveau restaurant' ?></h4>
        <p class="text-muted mb-0">Informations du restaurant.</p>
    </div>
    <a href="<?= site_url('restaurants') ?>" class="btn btn-light border">Annuler</a>
</div>

<form method="post"
      action="<?= $isEdit
            ? site_url('restaurants/' . $restaurant['id'])
            : site_url('restaurants') ?>">

    <?= csrf_field() ?>

    <div class="lc-card p-4 mb-3">
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Nom *</label>
                <input type="text" name="nom" class="form-control" required
                       value="<?= esc(old('nom', $restaurant['nom'] ?? '')) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Type de cuisine</label>
                <input type="text" name="type_cuisine" class="form-control"
                       placeholder="Ex : Malgache, Française, Asiatique..."
                       value="<?= esc(old('type_cuisine', $restaurant['type_cuisine'] ?? '')) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Destination</label>
                <select name="destination_id" class="form-select">
                    <option value="">— Aucune —</option>
                    <?php foreach ($destinations ?? [] as $d): ?>
                        <option value="<?= $d['id'] ?>"
                            <?= old('destination_id', $restaurant['destination_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
                            <?= esc($d['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Fournisseur</label>
                <select name="fournisseur_id" class="form-select">
                    <option value="">— Aucun —</option>
                    <?php foreach ($fournisseurs ?? [] as $f): ?>
                        <option value="<?= $f['id'] ?>"
                            <?= old('fournisseur_id', $restaurant['fournisseur_id'] ?? '') == $f['id'] ? 'selected' : '' ?>>
                            <?= esc($f['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Prix moyen / pers.</label>
                <input type="number" step="0.01" min="0" name="prix_moyen" class="form-control"
                       value="<?= old('prix_moyen', $restaurant['prix_moyen'] ?? 0) ?>">
            </div>

            <div class="col-md-2">
                <label class="form-label">Devise</label>
                <select name="devise" class="form-select">
                    <?php foreach (['MGA', 'EUR', 'USD'] as $dev): ?>
                        <option value="<?= $dev ?>"
                            <?= old('devise', $restaurant['devise'] ?? 'MGA') === $dev ? 'selected' : '' ?>>
                            <?= $dev ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Note (1-5)</label>
                <input type="number" step="0.1" min="0" max="5" name="note" class="form-control"
                       value="<?= old('note', $restaurant['note'] ?? '') ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label">Statut</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="actif" value="1"
                           <?= old('actif', $restaurant['actif'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label">Actif</label>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control"
                       value="<?= esc(old('telephone', $restaurant['telephone'] ?? '')) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= esc(old('email', $restaurant['email'] ?? '')) ?>">
            </div>

            <div class="col-12">
                <label class="form-label">Adresse</label>
                <textarea name="adresse" rows="2" class="form-control"><?= esc(old('adresse', $restaurant['adresse'] ?? '')) ?></textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control"><?= esc(old('description', $restaurant['description'] ?? '')) ?></textarea>
            </div>

        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn lc-btn-primary px-4">
            <i class="bi bi-check-lg me-1"></i>
            <?= $isEdit ? 'Enregistrer les modifications' : 'Créer le restaurant' ?>
        </button>
    </div>

</form>

<?= $this->endSection() ?>