<div class="col-md-6 mb-3">
    <label class="form-label">
        Fournisseur
    </label>

    <div class="input-group">
        <select
            class="form-select"
            name="fournisseur_id"
            id="fournisseur_id"
        >
            <option value="">
                — Sélectionner un fournisseur —
            </option>

            <?php foreach ($fournisseurs as $fournisseur): ?>
                <option
                    value="<?= $fournisseur['id'] ?>"
                    <?= ((int) ($item['fournisseur_id'] ?? 0) === (int) $fournisseur['id'])
                        ? 'selected'
                        : '' ?>
                >
                    <?= esc($fournisseur['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if (can('fournisseurs.create')): ?>
        <a
            href="<?= site_url('fournisseurs/new') ?>"
            class="btn btn-outline-primary"
            title="Créer un nouveau fournisseur"
        >
            <i class="bi bi-plus-lg"></i>
            Nouveau
        </a>
        <?php endif; ?>
    </div>

    <div class="form-text">
        Le fournisseur n'existe pas ?
        Cliquez sur « Nouveau » pour l'ajouter.
    </div>
</div>
<div class="col-md-6 mb-3">
    <label class="form-label">Disponibilité</label>
    <select class="form-select" name="disponibilite">
        <option value="disponible" <?= (($item['disponibilite'] ?? 'disponible') === 'disponible') ? 'selected' : '' ?>>Disponible</option>
        <option value="indisponible" <?= (($item['disponibilite'] ?? '') === 'indisponible') ? 'selected' : '' ?>>Indisponible</option>
    </select>
</div>
<div class="col-12"><div class="small text-secondary fw-semibold mb-2">Tarifs optionnels par profil</div></div>
<div class="col-md-4 mb-3">
    <label class="form-label">Prix adulte</label>
    <input type="number" step="0.01" min="0" class="form-control" name="prix_adulte" value="<?= esc(old('prix_adulte', $item['prix_adulte'] ?? '')) ?>">
</div>
<div class="col-md-4 mb-3">
    <label class="form-label">Prix enfant</label>
    <input type="number" step="0.01" min="0" class="form-control" name="prix_enfant" value="<?= esc(old('prix_enfant', $item['prix_enfant'] ?? '')) ?>">
</div>
<div class="col-md-4 mb-3">
    <label class="form-label">Prix groupe</label>
    <input type="number" step="0.01" min="0" class="form-control" name="prix_groupe" value="<?= esc(old('prix_groupe', $item['prix_groupe'] ?? '')) ?>">
</div>
