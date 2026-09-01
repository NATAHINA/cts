<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php $isEdit = !empty($client); ?>

<div class="mb-4">
    <a href="<?= site_url('clients') ?>" class="text-muted small">
        <i class="bi bi-arrow-left"></i> Retour aux clients
    </a>
</div>

<form method="post"
      action="<?= $isEdit
        ? site_url('clients/' . $client['id'])
        : site_url('clients') ?>">

    <?= csrf_field() ?>

    <div class="lc-card p-4">

        <h5 class="mb-4">
            <?= $isEdit ? 'Modifier le client' : 'Informations du client' ?>
        </h5>

        <div class="row g-3">

            <div class="col-md-4">

                <label class="form-label">Type de client</label>

                <select name="type_client" class="form-select">

                    <option value="Particulier"
                        <?= ($client['type_client'] ?? '') === 'Particulier' ? 'selected' : '' ?>>
                        Particulier
                    </option>

                    <option value="Entreprise"
                        <?= ($client['type_client'] ?? '') === 'Entreprise' ? 'selected' : '' ?>>
                        Entreprise
                    </option>

                    <option value="Groupe"
                        <?= ($client['type_client'] ?? '') === 'Groupe' ? 'selected' : '' ?>>
                        Groupe
                    </option>

                </select>

            </div>

            <div class="col-md-4">
                <label class="form-label">Nom *</label>
                <input type="text"
                       name="nom"
                       required
                       class="form-control"
                       value="<?= old('nom', $client['nom'] ?? '') ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Prénom</label>
                <input type="text"
                       name="prenom"
                       class="form-control"
                       value="<?= old('prenom', $client['prenom'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Entreprise</label>
                <input type="text"
                       name="entreprise"
                       class="form-control"
                       value="<?= old('entreprise', $client['entreprise'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text"
                       name="telephone"
                       class="form-control"
                       value="<?= old('telephone', $client['telephone'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       value="<?= old('email', $client['email'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Nationalité</label>
                <input type="text"
                       name="nationalite"
                       class="form-control"
                       value="<?= old('nationalite', $client['nationalite'] ?? '') ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Ville</label>
                <input type="text"
                       name="ville"
                       class="form-control"
                       value="<?= old('ville', $client['ville'] ?? '') ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Pays</label>
                <input type="text"
                       name="pays"
                       class="form-control"
                       value="<?= old('pays', $client['pays'] ?? '') ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Statut</label>

                <select name="statut" class="form-select">
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
            </div>

            <div class="col-12">

                <label class="form-label">Adresse</label>

                <textarea name="adresse"
                          rows="2"
                          class="form-control"><?= old('adresse', $client['adresse'] ?? '') ?></textarea>

            </div>

            <div class="col-12">

                <label class="form-label">Notes internes</label>

                <textarea name="notes"
                          rows="4"
                          class="form-control"><?= old('notes', $client['notes'] ?? '') ?></textarea>

            </div>

        </div>

    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">

        <a href="<?= site_url('clients') ?>" class="btn btn-light border">
            Annuler
        </a>

        <button type="submit" class="btn lc-btn-primary">
            <i class="bi bi-check-lg me-1"></i>
            <?= $isEdit ? 'Enregistrer les modifications' : 'Créer le client' ?>
        </button>

    </div>

</form>

<?= $this->endSection() ?>