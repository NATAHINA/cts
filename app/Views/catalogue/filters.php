<form method="get" class="row g-2 align-items-end mb-3">
    <div class="col-md-6">
        <label class="form-label" for="catalogue-search">Rechercher</label>
        <input id="catalogue-search" type="search" name="q" class="form-control" value="<?= esc($q ?? '') ?>" placeholder="Nom, destination, fournisseur...">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="catalogue-status">Filtrer par état</label>
        <select id="catalogue-status" name="statut" class="form-select">
            <option value="">Tous les états</option>
            <option value="actif" <?= ($statutFiltre ?? '') === 'actif' ? 'selected' : '' ?>>Actif</option>
            <option value="inactif" <?= ($statutFiltre ?? '') === 'inactif' ? 'selected' : '' ?>>Inactif</option>
            <option value="disponible" <?= ($statutFiltre ?? '') === 'disponible' ? 'selected' : '' ?>>Disponible</option>
            <option value="indisponible" <?= ($statutFiltre ?? '') === 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn lc-btn-primary"><i class="bi bi-search"></i> Rechercher</button>
        <a href="<?= current_url() ?>" class="btn btn-light border">Réinitialiser</a>
    </div>
</form>
