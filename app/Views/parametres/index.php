<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                <i class="bi bi-building fs-5"></i>
            </div>
            <h4 class="mb-0 fw-bold">Paramètres de l'agence</h4>
        </div>

        <p class="text-muted mb-0 ms-1">
            Gérez les informations, coordonnées et paramètres de votre agence.
        </p>
    </div>
</div>


<!-- Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4"
         role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4"
         role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <?= esc(session()->getFlashdata('error')) ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<form action="<?= site_url('parametres/update') ?>"
      method="post"
      enctype="multipart/form-data">

    <?= csrf_field() ?>


    <!-- ===================================================== -->
    <!-- INFORMATIONS GÉNÉRALES -->
    <!-- ===================================================== -->

    <div class="lc-card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex align-items-center mb-4">

                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3">
                    <i class="bi bi-info-circle fs-5"></i>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Informations générales
                    </h5>

                    <small class="text-muted">
                        Informations principales utilisées pour identifier votre agence.
                    </small>
                </div>

            </div>


            <div class="row g-4">

                <!-- Nom -->
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Nom de l'agence
                        <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-building text-muted"></i>
                        </span>

                        <input type="text"
                               name="nom_agence"
                               class="form-control border-start-0 ps-0"
                               value="<?= esc($agence['nom_agence'] ?? '') ?>"
                               placeholder="Ex. Agence Voyage Madagascar"
                               required>

                    </div>

                </div>

                <!-- Téléphone -->
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Téléphone
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-telephone text-muted"></i>
                        </span>

                        <input type="text"
                               name="telephone"
                               class="form-control border-start-0 ps-0"
                               value="<?= esc($agence['telephone'] ?? '') ?>"
                               placeholder="+261 XX XX XXX XX">

                    </div>

                </div>


                <!-- Email -->
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Adresse e-mail
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-envelope text-muted"></i>
                        </span>

                        <input type="email"
                               name="email_contact"
                               class="form-control border-start-0 ps-0"
                               value="<?= esc($agence['email_contact'] ?? '') ?>"
                               placeholder="contact@agence.com">

                    </div>

                </div>


                <!-- Site -->
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Site web
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-globe text-muted"></i>
                        </span>

                        <input type="url"
                               name="site_web"
                               class="form-control border-start-0 ps-0"
                               value="<?= esc($agence['site_web'] ?? '') ?>"
                               placeholder="https://...">

                    </div>

                </div>


                <!-- Adresse -->
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Adresse
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-light border-end-0 align-items-start pt-3">
                            <i class="bi bi-geo-alt text-muted"></i>
                        </span>

                        <textarea name="adresse"
                                  class="form-control border-start-0 ps-0"
                                  rows="3"
                                  placeholder="Adresse complète de l'agence"><?= esc($agence['adresse'] ?? '') ?></textarea>

                    </div>

                </div>


            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- LOGO + INFORMATIONS LÉGALES -->
    <!-- ===================================================== -->

    <div class="row g-4 mb-4">

        <!-- LOGO -->
        <div class="col-lg-5">

            <div class="lc-card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-4">

                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2 me-3">
                            <i class="bi bi-image fs-5"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Logo de l'agence
                            </h5>

                            <small class="text-muted">
                                Logo utilisé sur vos documents.
                            </small>
                        </div>

                    </div>


                    <!-- Aperçu -->
                    <div class="logo-preview bg-light rounded-4 d-flex align-items-center justify-content-center mb-3"
                         style="height: 180px;">

                        <?php if (!empty($agence['logo'])): ?>

                            <img id="logoPreview"
                                 src="<?= base_url('uploads/agences/' . $agence['logo']) ?>"
                                 alt="Logo de l'agence"
                                 style="max-width: 80%; max-height: 150px; object-fit: contain;">

                        <?php else: ?>

                            <div id="logoPlaceholder"
                                 class="text-center text-muted">

                                <i class="bi bi-card-image fs-1 d-block mb-2 opacity-50"></i>

                                <small>
                                    Aucun logo enregistré
                                </small>

                            </div>

                        <?php endif; ?>

                    </div>


                    <label class="form-label fw-semibold">
                        Nouveau logo
                    </label>

                    <input type="file"
                           name="logo"
                           id="logoInput"
                           class="form-control"
                           accept=".jpg,.jpeg,.png,.webp">

                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        JPG, PNG ou WEBP — 2 Mo maximum.
                    </div>


                    <?php if (!empty($agence['logo'])): ?>

                        <div class="mt-3">

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="deleteLogo()">

                                <i class="bi bi-trash me-1"></i>
                                Supprimer le logo

                            </button>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- INFORMATIONS LÉGALES -->
        <div class="col-lg-7">

            <div class="lc-card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-4">

                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 me-3">
                            <i class="bi bi-file-earmark-text fs-5"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Informations légales
                            </h5>

                            <small class="text-muted">
                                Informations administratives et fiscales.
                            </small>
                        </div>

                    </div>


                    <div class="row g-4">

                        <!-- NIF -->
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                NIF
                            </label>

                            <div class="input-group">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-hash text-muted"></i>
                                </span>

                                <input type="text"
                                       name="nif"
                                       class="form-control border-start-0 ps-0"
                                       value="<?= esc($agence['nif'] ?? '') ?>"
                                       placeholder="Numéro NIF">

                            </div>

                        </div>


                        <!-- STAT -->
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                STAT
                            </label>

                            <div class="input-group">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-card-text text-muted"></i>
                                </span>

                                <input type="text"
                                       name="stat"
                                       class="form-control border-start-0 ps-0"
                                       value="<?= esc($agence['stat'] ?? '') ?>"
                                       placeholder="Numéro STAT">

                            </div>

                        </div>


                        <!-- RCS -->
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                RCS
                            </label>

                            <div class="input-group">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-journal-text text-muted"></i>
                                </span>

                                <input type="text"
                                       name="rcs"
                                       class="form-control border-start-0 ps-0"
                                       value="<?= esc($agence['rcs'] ?? '') ?>"
                                       placeholder="Numéro RCS">

                            </div>

                        </div>


                        <!-- TVA -->
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                TVA
                            </label>

                            <div class="input-group">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-percent text-muted"></i>
                                </span>

                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       name="tva"
                                       class="form-control border-start-0 ps-0"
                                       value="<?= esc($agence['tva'] ?? '') ?>"
                                       placeholder="Ex. 20">

                                <span class="input-group-text bg-light">
                                    %
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- NUMÉROTATION -->
    <!-- ===================================================== -->

    <div class="lc-card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex align-items-center mb-4">

                <div class="bg-info bg-opacity-10 text-info rounded-3 p-2 me-3">
                    <i class="bi bi-hash fs-5"></i>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Numérotation des documents
                    </h5>

                    <small class="text-muted">
                        Définissez les préfixes utilisés pour vos documents commerciaux.
                    </small>
                </div>

            </div>


            <div class="row g-4">

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Préfixe cotation
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-light">
                            <i class="bi bi-file-earmark-text text-muted"></i>
                        </span>

                        <input type="text"
                               name="prefixe_cotation"
                               class="form-control"
                               value="<?= esc($agence['prefixe_cotation'] ?? '') ?>"
                               placeholder="COT">

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Préfixe réservation
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-light">
                            <i class="bi bi-calendar-check text-muted"></i>
                        </span>

                        <input type="text"
                               name="prefixe_reservation"
                               class="form-control"
                               value="<?= esc($agence['prefixe_reservation'] ?? '') ?>"
                               placeholder="RES">

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Préfixe facture
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-light">
                            <i class="bi bi-receipt text-muted"></i>
                        </span>

                        <input type="text"
                               name="prefixe_facture"
                               class="form-control"
                               value="<?= esc($agence['prefixe_facture'] ?? '') ?>"
                               placeholder="FAC">

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- ACTIONS -->
    <!-- ===================================================== -->

    <div class="d-flex justify-content-end align-items-center gap-2 pb-4">

        <a href="<?= site_url('/') ?>"
           class="btn btn-light border px-4">

            <i class="bi bi-x-lg me-1"></i>
            Annuler

        </a>

        <button type="submit"
                class="btn lc-btn-primary px-4">

            <i class="bi bi-check-lg me-1"></i>
            Enregistrer les modifications

        </button>

    </div>

</form>


<!-- Formulaire suppression logo -->
<form id="deleteLogoForm"
      action="<?= site_url('parametres/logo/delete') ?>"
      method="post"
      class="d-none">

    <?= csrf_field() ?>

</form>


</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const logoInput = document.getElementById('logoInput');

    if (logoInput) {

        logoInput.addEventListener('change', function (event) {

            const file = event.target.files[0];

            if (!file) {
                return;
            }

            if (!file.type.startsWith('image/')) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {

                let preview = document.getElementById('logoPreview');

                if (!preview) {

                    const placeholder =
                        document.getElementById('logoPlaceholder');

                    if (placeholder) {
                        placeholder.remove();
                    }

                    preview = document.createElement('img');

                    preview.id = 'logoPreview';
                    preview.alt = 'Aperçu du logo';

                    preview.style.maxWidth = '80%';
                    preview.style.maxHeight = '150px';
                    preview.style.objectFit = 'contain';

                    document.querySelector('.logo-preview')
                            .appendChild(preview);
                }

                preview.src = e.target.result;
            };

            reader.readAsDataURL(file);

        });

    }

});


function deleteLogo() {

    if (confirm('Voulez-vous vraiment supprimer le logo de l’agence ?')) {

        document
            .getElementById('deleteLogoForm')
            .submit();

    }

}

</script>

<?= $this->endSection() ?>
