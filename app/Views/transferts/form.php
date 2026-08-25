<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="lc-card p-4" style="max-width: 760px;">
    <form method="post" action="<?= $item ? site_url('transferts/' . $item['id']) : site_url('transferts') ?>">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Destination</label>
                <select class="form-select" name="destination_id">
                    <option value="">—</option>
                    <?php foreach ($destinations as $d): ?>
                        <option value="<?= $d['id'] ?>" <?= ((int)($item['destination_id'] ?? 0) === (int) $d['id']) ? 'selected' : '' ?>><?= esc($d['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" value="<?= esc(old('nom', $item['nom'] ?? '')) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Type de transfert</label>
                <input type="text" class="form-control" name="type" value="<?= esc(old('type', $item['type'] ?? '')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Véhicule</label>
                <input type="text" class="form-control" name="vehicule" value="<?= esc(old('vehicule', $item['vehicule'] ?? '')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Capacité (passagers)</label>
                <input type="number" min="0" class="form-control" name="capacite" value="<?= esc(old('capacite', $item['capacite'] ?? '')) ?>">
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
            <a href="<?= site_url('transferts') ?>" class="btn btn-light border">Annuler</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
