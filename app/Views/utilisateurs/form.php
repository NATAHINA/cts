<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = ! empty($user); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?= $isEdit ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur' ?></h4>
    </div>
    <a href="<?= site_url('utilisateurs') ?>" class="btn btn-light border">Annuler</a>
</div>

<form method="post"
      action="<?= $isEdit
            ? site_url('utilisateurs/' . $user['id'])
            : site_url('utilisateurs') ?>">
    <?= csrf_field() ?>

    <div class="lc-card p-4 mb-3">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nom *</label>
                <input type="text" name="nom" class="form-control" required
                       value="<?= esc(old('nom', $user['nom'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control"
                       value="<?= esc(old('prenom', $user['prenom'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" required
                       value="<?= esc(old('email', $user['email'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control"
                       value="<?= esc(old('telephone', $user['telephone'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Rôle</label>
                <select name="role_id" class="form-select">
                    <option value="">— Aucun —</option>
                    <?php foreach ($roles ?? [] as $role): ?>
                        <option value="<?= $role['id'] ?>"
                            <?= old('role_id', $user['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                            <?= esc($role['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">
                    Mot de passe <?= $isEdit ? '(laisser vide pour ne pas changer)' : '*' ?>
                </label>
                <input type="password" name="password" class="form-control"
                       <?= $isEdit ? '' : 'required' ?>>
            </div>
            <div class="col-md-6">
                <label class="form-label">Statut</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="actif" value="1"
                           <?= old('actif', $user['actif'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label">Actif</label>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn lc-btn-primary px-4">
            <i class="bi bi-check-lg me-1"></i>
            <?= $isEdit ? 'Enregistrer' : 'Créer l\'utilisateur' ?>
        </button>
    </div>
</form>

<?= $this->endSection() ?>