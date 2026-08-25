<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="lc-card p-4" style="max-width: 760px;">
    <form method="post" action="<?= $item ? site_url('croisieres/' . $item['id']) : site_url('croisieres') ?>">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Compagnie</label>
                <input type="text" class="form-control" name="compagnie" value="<?= esc(old('compagnie', $item['compagnie'] ?? '')) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" value="<?= esc(old('nom', $item['nom'] ?? '')) ?>" required>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Itinéraire</label>
                <textarea class="form-control" name="itineraire" rows="3"><?= esc(old('itineraire', $item['itineraire'] ?? '')) ?></textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Durée (jours)</label>
                <input type="number" min="0" class="form-control" name="duree_jours" value="<?= esc(old('duree_jours', $item['duree_jours'] ?? '')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Date de départ</label>
                <input type="date" class="form-control" name="date_depart" value="<?= esc(old('date_depart', $item['date_depart'] ?? '')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Prix</label>
                <input type="number" step="0.01" min="0" class="form-control" name="prix" value="<?= esc(old('prix', $item['prix'] ?? '')) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Devise</label>
                <select class="form-select" name="devise_id" required>
                    <?php foreach ($devises as $d): ?>
                        <option value="<?= $d['id'] ?>" <?= ((int)($item['devise_id'] ?? 0) === (int) $d['id']) ? 'selected' : '' ?>><?= esc($d['code']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Statut</label>
                <select class="form-select" name="statut">
                    <option value="actif" <?= (($item['statut'] ?? 'actif') === 'actif') ? 'selected' : '' ?>>Actif</option>
                    <option value="inactif" <?= (($item['statut'] ?? '') === 'inactif') ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        </div>

        <?= $this->include('catalogue/pricing_fields') ?>

        <div class="d-flex gap-2 mt-2">
            <button type="submit" class="btn lc-btn-primary px-4">Enregistrer</button>
            <a href="<?= site_url('croisieres') ?>" class="btn btn-light border">Annuler</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
