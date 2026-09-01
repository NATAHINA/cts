<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$isEdit = !empty($devise);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">
            <?= $isEdit ? 'Modifier la devise' : 'Nouvelle devise' ?>
        </h4>
        <p class="text-muted mb-0">
            <?= $isEdit ? 'Modifiez les informations de la devise' : 'Ajoutez une nouvelle devise' ?>
        </p>
    </div>

    <a href="<?= site_url('devises') ?>" class="btn btn-light border">
        Annuler
    </a>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="lc-card p-4" style="max-width: 640px;">

    <form method="post"
          action="<?= $isEdit
              ? site_url('devises/' . $devise['id'])
              : site_url('devises') ?>">

        <?= csrf_field() ?>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Code *</label>
                <input type="text"
                       name="code"
                       class="form-control text-uppercase"
                       maxlength="5"
                       value="<?= esc(old('code', $devise['code'] ?? '')) ?>"
                       required
                       <?= $isEdit ? '' : '' ?>>
                <div class="form-text">Ex : MGA, EUR, USD</div>
            </div>

            <div class="col-md-8">
                <label class="form-label">Nom *</label>
                <input type="text"
                       name="nom"
                       class="form-control"
                       value="<?= esc(old('nom', $devise['nom'] ?? '')) ?>"
                       required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Symbole *</label>
                <input type="text"
                       name="symbole"
                       class="form-control"
                       maxlength="10"
                       value="<?= esc(old('symbole', $devise['symbole'] ?? '')) ?>"
                       required>
                <div class="form-text">Ex : Ar, €, $</div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Taux de change *</label>
                <input type="number"
                       name="taux_change"
                       class="form-control"
                       step="0.0001"
                       min="0"
                       value="<?= esc(old('taux_change', $devise['taux_change'] ?? '1')) ?>"
                       required>
                <div class="form-text">
                    1 unité = ? de la devise par défaut
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label d-block">Par défaut</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input"
                           type="checkbox"
                           name="is_default"
                           value="1"
                           id="is_default"
                           <?= old('is_default', $devise['is_default'] ?? 0) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_default">
                        Devise principale
                    </label>
                </div>
            </div>

        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn lc-btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>
                <?= $isEdit ? 'Enregistrer' : 'Créer la devise' ?>
            </button>
        </div>

    </form>
</div>

<?= $this->endSection() ?>