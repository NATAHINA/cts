<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="lc-card p-4" style="max-width: 760px;">
    <form method="post" action="<?= $item ? site_url('clients/' . $item['id']) : site_url('clients') ?>">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Type</label>
                <select class="form-select" name="type">
                    <option value="particulier" <?= (($item['type'] ?? 'particulier') === 'particulier') ? 'selected' : '' ?>>Particulier</option>
                    <option value="entreprise" <?= (($item['type'] ?? '') === 'entreprise') ? 'selected' : '' ?>>Entreprise</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom / Raison sociale</label>
                <input type="text" class="form-control" name="nom" value="<?= esc(old('nom', $item['nom'] ?? '')) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" name="email" value="<?= esc(old('email', $item['email'] ?? '')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" class="form-control" name="telephone" value="<?= esc(old('telephone', $item['telephone'] ?? '')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Adresse</label>
                <input type="text" class="form-control" name="adresse" value="<?= esc(old('adresse', $item['adresse'] ?? '')) ?>">
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="3"><?= esc(old('notes', $item['notes'] ?? '')) ?></textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mt-2">
            <button type="submit" class="btn lc-btn-primary px-4">Enregistrer</button>
            <a href="<?= site_url('clients') ?>" class="btn btn-light border">Annuler</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
