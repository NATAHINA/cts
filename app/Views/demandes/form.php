<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php $isEdit = !empty($demande); ?>

<form method="post"
      action="<?= $isEdit
          ? site_url('demandes/' . $demande['id'])
          : site_url('demandes') ?>">

    <?= csrf_field() ?>

    <div class="lc-card p-4">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Client *</label>
                <div class="input-group">
                    <select name="client_id" id="client_id" class="form-select" required>
                        <option value="">Sélectionner un client</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['id'] ?>"
                                <?= old('client_id', $demande['client_id'] ?? '') == $client['id'] ? 'selected' : '' ?>>
                                <?= esc(trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''))) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNouveauClient" title="Nouveau client">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Destination *</label>
                <div class="input-group">
                    <select name="destination_id" id="destination_id" class="form-select" required>
                        <option value="">Sélectionner une destination</option>
                        <?php foreach ($destinations as $dest): ?>
                            <option value="<?= $dest['id'] ?>"
                                <?= old('destination_id', $demande['destination_id'] ?? '') == $dest['id'] ? 'selected' : '' ?>>
                                <?= esc($dest['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNouvelleDestination" title="Nouvelle destination">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Date de demande</label>
                <input type="date"
                       name="date_demande"
                       class="form-control"
                       value="<?= old('date_demande', $demande['date_demande'] ?? date('Y-m-d')) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Date de départ</label>
                <input type="date"
                       name="date_depart"
                       class="form-control"
                       value="<?= old('date_depart', $demande['date_depart'] ?? '') ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Date de retour</label>
                <input type="date"
                       name="date_retour"
                       class="form-control"
                       value="<?= old('date_retour', $demande['date_retour'] ?? '') ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Adultes</label>
                <input type="number" name="nb_adultes"
                       class="form-control"
                       value="<?= old('nb_adultes', $demande['nb_adultes'] ?? 1) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Enfants</label>
                <input type="number" name="nb_enfants"
                       class="form-control"
                       value="<?= old('nb_enfants', $demande['nb_enfants'] ?? 0) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Bébés</label>
                <input type="number" name="nb_bebes"
                       class="form-control"
                       value="<?= old('nb_bebes', $demande['nb_bebes'] ?? 0) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Budget</label>
                <input type="number"
                       step="0.01"
                       name="budget"
                       class="form-control"
                       value="<?= old('budget', $demande['budget'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Devise</label>
                <select name="devise" class="form-select">
                    <?php
                    $devises = ['MGA' => 'MGA - Ariary', 'EUR' => 'EUR - Euro', 'USD' => 'USD - Dollar'];
                    $selectedDevise = old('devise', $demande['devise'] ?? 'MGA');
                    foreach ($devises as $code => $label):
                    ?>
                        <option value="<?= $code ?>" <?= $selectedDevise === $code ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Source</label>
                <select name="source" class="form-select">
                    <?php
                    $sources = ['Téléphone', 'Email', 'Site web', 'Facebook', 'WhatsApp', 'Agence'];
                    $selectedSource = old('source', $demande['source'] ?? 'Téléphone');
                    foreach ($sources as $s):
                    ?>
                        <option value="<?= $s ?>" <?= $selectedSource === $s ? 'selected' : '' ?>>
                            <?= $s ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <?php
                    $statuts = [
                        'nouvelle'   => 'Nouvelle',
                        'en_cours'   => 'En cours',
                        'cotée'      => 'Cotée',
                        'abandonnée' => 'Abandonnée',
                        'convertie'  => 'Convertie',
                    ];
                    $selectedStatut = old('statut', $demande['statut'] ?? 'nouvelle');
                    foreach ($statuts as $value => $label):
                    ?>
                        <option value="<?= $value ?>" <?= $selectedStatut === $value ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Demande du client</label>
                <textarea name="notes_client"
                          rows="4"
                          class="form-control"><?= old('notes_client', $demande['notes_client'] ?? '') ?></textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Notes internes</label>
                <textarea name="notes_interne"
                          rows="3"
                          class="form-control"><?= old('notes_interne', $demande['notes_interne'] ?? '') ?></textarea>
            </div>

        </div>

    </div>

    <div class="text-end mt-3">
        <a href="<?= site_url('demandes') ?>" class="btn btn-light border">
            Annuler
        </a>

        <button class="btn lc-btn-primary">
            Enregistrer
        </button>
    </div>

</form>


<!-- Modal nouveau client -->
<div class="modal fade" id="modalNouveauClient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="clientQuickError" class="alert alert-danger d-none py-2"></div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Type</label>
                        <select id="qc_type" class="form-select">
                            <option value="Particulier">Particulier</option>
                            <option value="Entreprise">Entreprise</option>
                            <option value="Groupe">Groupe</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nom *</label>
                        <input type="text" id="qc_nom" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" id="qc_prenom" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" id="qc_telephone" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">E-mail</label>
                        <input type="email" id="qc_email" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Entreprise</label>
                        <input type="text" id="qc_entreprise" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nationalité</label>
                        <input type="text" id="qc_nationalite" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ville</label>
                        <input type="text" id="qc_ville" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pays</label>
                        <input type="text" id="qc_pays" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Adresse</label>
                        <textarea id="qc_adresse" rows="2" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn lc-btn-primary" id="btnSaveClient">
                    Créer le client
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal nouvelle destination -->
<div class="modal fade" id="modalNouvelleDestination" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle destination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="destQuickError" class="alert alert-danger d-none py-2"></div>
                <div class="mb-3">
                    <label class="form-label">Nom *</label>
                    <input type="text" id="qd_nom" class="form-control" placeholder="Ex : Nosy Be" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pays</label>
                    <input type="text" id="qd_pays" class="form-control" placeholder="Ex : Madagascar">
                </div>
                <div class="mb-3">
                    <label class="form-label">Région</label>
                    <input type="text" id="qd_region" class="form-control" placeholder="">
                </div>
                <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="qd_description" rows="2" class="form-control"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn lc-btn-primary" id="btnSaveDestination">
                    Créer la destination
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const csrfName = '<?= csrf_token() ?>';
    const csrfHash = '<?= csrf_hash() ?>';

    function postJson(url, data) {
        const body = new FormData();
        body.append(csrfName, csrfHash);
        Object.keys(data).forEach(k => body.append(k, data[k] ?? ''));

        return fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body
        }).then(r => r.json());
    }

    // --- Client ---
    document.getElementById('btnSaveClient')?.addEventListener('click', function () {
        const err = document.getElementById('clientQuickError');
        err.classList.add('d-none');

        const nom = document.getElementById('qc_nom').value.trim();
        if (!nom) {
            err.textContent = 'Le nom est obligatoire.';
            err.classList.remove('d-none');
            return;
        }

        this.disabled = true;

        postJson('<?= site_url('clients/quick-store') ?>', {
            type_client: document.getElementById('qc_type').value.trim(),
            nom: nom,
            prenom: document.getElementById('qc_prenom').value.trim(),
            email: document.getElementById('qc_email').value.trim(),
            telephone: document.getElementById('qc_telephone').value.trim(),
            entreprise: document.getElementById('qc_entreprise').value.trim(),
            nationalite: document.getElementById('qc_nationalite').value.trim(),
            ville: document.getElementById('qc_ville').value.trim(),
            pays: document.getElementById('qc_pays').value.trim(),
            adresse: document.getElementById('qc_adresse').value.trim(),
        })
        .then(res => {
            if (!res.success) {
                err.textContent = res.message || 'Erreur lors de la création.';
                err.classList.remove('d-none');
                return;
            }
            const sel = document.getElementById('client_id');
            const opt = new Option(res.label, res.id, true, true);
            sel.add(opt);
            bootstrap.Modal.getInstance(document.getElementById('modalNouveauClient'))?.hide();
            // reset
            ['qc_prenom','qc_nom','qc_email','qc_telephone','qc_entreprise','qc_nationalite','qc_ville','qc_pays','qc_adresse'].forEach(id => {
                document.getElementById(id).value = '';
            });
        })
        .catch(() => {
            err.textContent = 'Erreur réseau.';
            err.classList.remove('d-none');
        })
        .finally(() => { this.disabled = false; });
    });

    // --- Destination ---
    document.getElementById('btnSaveDestination')?.addEventListener('click', function () {
        const err = document.getElementById('destQuickError');
        err.classList.add('d-none');

        const nom = document.getElementById('qd_nom').value.trim();
        if (!nom) {
            err.textContent = 'Le nom est obligatoire.';
            err.classList.remove('d-none');
            return;
        }

        this.disabled = true;

        postJson('<?= site_url('destinations/quick-store') ?>', {
            nom: nom,
            pays: document.getElementById('qd_pays').value.trim(),
            region: document.getElementById('qd_region').value.trim(),
            description: document.getElementById('qd_description').value.trim(),
        })
        .then(res => {
            if (!res.success) {
                err.textContent = res.message || 'Erreur lors de la création.';
                err.classList.remove('d-none');
                return;
            }
            const sel = document.getElementById('destination_id');
            const opt = new Option(res.label, res.id, true, true);
            sel.add(opt);
            bootstrap.Modal.getInstance(document.getElementById('modalNouvelleDestination'))?.hide();
            document.getElementById('qd_nom').value = '';
            document.getElementById('qd_pays').value = '';
            document.getElementById('qd_region').value = '';
            document.getElementById('qd_description').value = '';
        })
        .catch(() => {
            err.textContent = 'Erreur réseau.';
            err.classList.remove('d-none');
        })
        .finally(() => { this.disabled = false; });
    });
})();
</script>

<?= $this->endSection() ?>