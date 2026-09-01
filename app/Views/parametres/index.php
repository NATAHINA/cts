<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Paramètres de l'agence</h4>
        <p class="text-muted mb-0">Informations légales et configuration.</p>
    </div>
</div>

<form method="post" action="<?= site_url('parametres') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- IDENTITÉ -->
    <div class="lc-card p-4 mb-3">
        <h6 class="mb-3">Identité de l'agence</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nom de l'agence *</label>
                <input type="text" name="nom_agence" class="form-control" required
                       value="<?= esc(old('nom_agence', $tenant['nom_agence'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Site web</label>
                <input type="url" name="site_web" class="form-control"
                       placeholder="https://..."
                       value="<?= esc(old('site_web', $tenant['site_web'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email de contact</label>
                <input type="email" name="email_contact" class="form-control"
                       value="<?= esc(old('email_contact', $tenant['email_contact'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control"
                       value="<?= esc(old('telephone', $tenant['telephone'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Adresse</label>
                <textarea name="adresse" rows="2" class="form-control"><?= esc(old('adresse', $tenant['adresse'] ?? '')) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Logo</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                <?php if (! empty($tenant['logo'])): ?>
                    <div class="mt-2">
                        <img src="<?= base_url('uploads/logos/' . $tenant['logo']) ?>"
                             alt="Logo" style="max-height:60px; border-radius:8px;">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- INFORMATIONS LÉGALES -->
    <div class="lc-card p-4 mb-3">
        <h6 class="mb-3">Informations légales</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">NIF</label>
                <input type="text" name="nif" class="form-control"
                       value="<?= esc(old('nif', $tenant['nif'] ?? '')) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">STAT</label>
                <input type="text" name="stat" class="form-control"
                       value="<?= esc(old('stat', $tenant['stat'] ?? '')) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">RCS</label>
                <input type="text" name="rcs" class="form-control"
                       value="<?= esc(old('rcs', $tenant['rcs'] ?? '')) ?>">
            </div>
        </div>
    </div>

    <!-- FINANCE & NUMÉROTATION -->
    <div class="lc-card p-4 mb-3">
        <h6 class="mb-3">Finance & numérotation</h6>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Devise par défaut</label>
                <select name="devise_defaut" class="form-select">
                    <?php foreach (['MGA', 'EUR', 'USD'] as $d): ?>
                        <option value="<?= $d ?>"
                            <?= old('devise_defaut', $tenant['devise_defaut'] ?? 'MGA') === $d ? 'selected' : '' ?>>
                            <?= $d ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">TVA (%)</label>
                <input type="number" step="0.01" min="0" name="tva" class="form-control"
                       value="<?= old('tva', $tenant['tva'] ?? 0) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Préfixe cotation</label>
                <input type="text" name="prefixe_cotation" class="form-control"
                       value="<?= esc(old('prefixe_cotation', $tenant['prefixe_cotation'] ?? 'COT')) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Préfixe réservation</label>
                <input type="text" name="prefixe_reservation" class="form-control"
                       value="<?= esc(old('prefixe_reservation', $tenant['prefixe_reservation'] ?? 'RES')) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Préfixe facture</label>
                <input type="text" name="prefixe_facture" class="form-control"
                       value="<?= esc(old('prefixe_facture', $tenant['prefixe_facture'] ?? 'FAC')) ?>">
            </div>
        </div>
    </div>

    <!-- DOCUMENTS -->
    <div class="lc-card p-4 mb-3">
        <h6 class="mb-3">Documents</h6>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Conditions générales</label>
                <textarea name="conditions_generales" rows="4" class="form-control"><?= esc(old('conditions_generales', $tenant['conditions_generales'] ?? '')) ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Pied de page des documents</label>
                <textarea name="pied_page_document" rows="2" class="form-control"><?= esc(old('pied_page_document', $tenant['pied_page_document'] ?? '')) ?></textarea>
            </div>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn lc-btn-primary px-4">
            <i class="bi bi-check-lg me-1"></i> Enregistrer
        </button>
    </div>
</form>

<?= $this->endSection() ?>