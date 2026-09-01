<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($facture); ?>

<form method="post"
      action="<?= $isEdit
          ? site_url('factures/' . $facture['id'])
          : site_url('factures') ?>">
    <?= csrf_field() ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><?= $isEdit ? 'Modifier la facture' : 'Nouvelle facture' ?></h4>
            <p class="text-muted mb-0">Informations générales</p>
        </div>
        <a href="<?= site_url('factures') ?>" class="btn btn-light border">Annuler</a>
    </div>

    <div class="lc-card p-4 mb-3">
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Client *</label>
                <select name="client_id" class="form-select" required>
                    <option value="">Sélectionner un client</option>
                    <?php foreach ($clients ?? [] as $client): ?>
                        <option value="<?= $client['id'] ?>"
                            <?= old('client_id', $facture['client_id'] ?? '') == $client['id'] ? 'selected' : '' ?>>
                            <?= esc(trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''))) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Réservation liée</label>
                <select name="reservation_id" class="form-select">
                    <option value="">— Aucune —</option>
                    <?php foreach ($reservations ?? [] as $res): ?>
                        <option value="<?= $res['id'] ?>"
                            <?= old('reservation_id', $facture['reservation_id'] ?? '') == $res['id'] ? 'selected' : '' ?>>
                            <?= esc($res['numero'] ?? ('RES-' . $res['id'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Date facture *</label>
                <input type="date" name="date_facture" class="form-control" required
                       value="<?= old('date_facture', $facture['date_facture'] ?? date('Y-m-d')) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Date d'échéance</label>
                <input type="date" name="date_echeance" class="form-control"
                       value="<?= old('date_echeance', $facture['date_echeance'] ?? '') ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Devise</label>
                <?php $devise = old('devise', $facture['devise'] ?? 'MGA'); ?>
                <select name="devise" class="form-select">
                    <option value="MGA" <?= $devise === 'MGA' ? 'selected' : '' ?>>MGA - Ariary</option>
                    <option value="EUR" <?= $devise === 'EUR' ? 'selected' : '' ?>>EUR - Euro</option>
                    <option value="USD" <?= $devise === 'USD' ? 'selected' : '' ?>>USD - Dollar</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Statut</label>
                <?php $statut = old('statut', $facture['statut'] ?? 'brouillon'); ?>
                <select name="statut" class="form-select">
                    <option value="brouillon" <?= $statut === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="emise" <?= $statut === 'emise' ? 'selected' : '' ?>>Émise</option>
                    <option value="partiellement_payee" <?= $statut === 'partiellement_payee' ? 'selected' : '' ?>>Partiellement payée</option>
                    <option value="payee" <?= $statut === 'payee' ? 'selected' : '' ?>>Payée</option>
                    <option value="annulee" <?= $statut === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Notes client</label>
                <textarea name="notes_client" rows="3" class="form-control"><?= old('notes_client', $facture['notes_client'] ?? '') ?></textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Notes internes</label>
                <textarea name="notes_interne" rows="2" class="form-control"><?= old('notes_interne', $facture['notes_interne'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn lc-btn-primary px-4">
            <i class="bi bi-check-lg me-1"></i>
            <?= $isEdit ? 'Enregistrer' : 'Créer la facture' ?>
        </button>
    </div>
</form>

<?= $this->endSection() ?>