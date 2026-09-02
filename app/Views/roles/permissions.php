<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$groupedPermissions = [];

foreach ($permissions as $permission) {
    $groupedPermissions[$permission['module']][] = $permission;
}
?>

<style>
    /* =========================================================
       PAGE PERMISSIONS
       ========================================================= */

    .permissions-header {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .permissions-header h4 {
        font-size: 18px;
        font-weight: 700;
    }

    .permissions-header .role-name {
        font-size: 14px;
        color: #6c757d;
    }

    /* =========================================================
       ACCORDION
       ========================================================= */

    .permissions-accordion {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .permission-section {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        background: #fff;
        overflow: hidden;
    }

    .permission-section-header {
        min-height: 52px;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background-color .2s ease;
    }

    .permission-section-header:hover {
        background: #f8f9fa;
    }

    .permission-module-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 14px;
    }

    .permission-module-icon {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        background: #f1f3f5;
        color: #495057;
        font-size: 14px;
    }

    .permission-count {
        font-size: 12px;
        font-weight: 500;
        color: #6c757d;
        background: #f1f3f5;
        padding: 3px 8px;
        border-radius: 20px;
    }

    .permission-header-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .select-module-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: #6c757d;
    }

    .select-module-wrapper .form-check-input {
        margin-top: 0;
        cursor: pointer;
    }

    .permission-chevron {
        transition: transform .2s ease;
        color: #6c757d;
        font-size: 14px;
    }

    .permission-section-header[aria-expanded="true"] .permission-chevron {
        transform: rotate(180deg);
    }

    /* =========================================================
       PERMISSIONS
       ========================================================= */

    .permission-section-body {
        border-top: 1px solid #f0f0f0;
        padding: 12px 15px;
        background: #fafbfc;
    }

    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 7px 10px;
    }

    .permission-item {
        min-height: 38px;
        padding: 7px 10px;
        border: 1px solid #e9ecef;
        border-radius: 7px;
        background: #fff;
        display: flex;
        align-items: center;
        transition: all .15s ease;
    }

    .permission-item:hover {
        border-color: #ced4da;
        background: #f8f9fa;
    }

    .permission-item .form-check {
        width: 100%;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .permission-item .form-check-input {
        margin-top: 0;
        margin-right: 8px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .permission-item .form-check-label {
        font-size: 14px;
        line-height: 1.3;
        cursor: pointer;
        color: #343a40;
    }

    .permission-item:has(.form-check-input:checked) {
        border-color: #b8d4fe;
        background: #f4f8ff;
    }

    /* =========================================================
       FOOTER
       ========================================================= */

    .permissions-footer {
        margin-top: 18px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1200px) {
        .permissions-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 992px) {
        .permissions-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .permissions-grid {
            grid-template-columns: 1fr;
        }

        .permission-header-right {
            gap: 8px;
        }

        .select-module-wrapper label {
            display: none;
        }

        .permission-section-header {
            padding: 9px 12px;
        }

        .permission-section-body {
            padding: 10px;
        }
    }
</style>


<!-- =========================================================
     EN-TÊTE
     ========================================================= -->

<div class="permissions-header">

    <div class="d-flex justify-content-between align-items-center">

        <div>

            <h4 class="mb-1">
                <i class="bi bi-shield-lock me-2"></i>
                Permissions du rôle
            </h4>

            <div class="role-name">
                Rôle :
                <strong><?= esc($role['libelle']) ?></strong>

                <?php if (! empty($role['code'])): ?>
                    <span class="ms-1">
                        (<?= esc($role['code']) ?>)
                    </span>
                <?php endif; ?>
            </div>

        </div>

        <a href="<?= site_url('roles') ?>"
           class="btn btn-light border btn-sm">

            <i class="bi bi-arrow-left me-1"></i>
            Retour

        </a>

    </div>

</div>


<!-- =========================================================
     FORMULAIRE
     ========================================================= -->

<form method="post"
      action="<?= site_url('roles/' . $role['id'] . '/permissions') ?>">

    <?= csrf_field() ?>


    <div class="permissions-accordion">

        <?php foreach ($groupedPermissions as $module => $modulePermissions): ?>

            <?php
            $moduleId = 'module_' . preg_replace(
                '/[^a-zA-Z0-9_-]/',
                '_',
                $module
            );

            $permissionIds = array_map(
                static fn ($permission) => (int) $permission['id'],
                $modulePermissions
            );

            $selectedCount = count(
                array_intersect(
                    $permissionIds,
                    $selectedPermissionIds
                )
            );

            $allSelected = (
                $selectedCount === count($modulePermissions)
                && count($modulePermissions) > 0
            );
            ?>


            <div class="permission-section">

                <!-- HEADER DU MODULE -->

                <div class="permission-section-header"
                     data-bs-toggle="collapse"
                     data-bs-target="#<?= esc($moduleId) ?>"
                     aria-expanded="true"
                     aria-controls="<?= esc($moduleId) ?>">

                    <div class="permission-module-title">

                        <div class="permission-module-icon">
                            <i class="bi bi-folder2-open"></i>
                        </div>

                        <span>
                            <?= esc(ucfirst($module)) ?>
                        </span>

                        <span class="permission-count">
                            <?= count($modulePermissions) ?> permission<?= count($modulePermissions) > 1 ? 's' : '' ?>
                        </span>

                    </div>


                    <div class="permission-header-right">

                        <!-- Tout sélectionner -->

                        <div class="select-module-wrapper"
                             onclick="event.stopPropagation();">

                            <input
                                type="checkbox"
                                class="form-check-input select-module"
                                data-module="<?= esc($moduleId) ?>"
                                id="select_<?= esc($moduleId) ?>"
                                <?= $allSelected ? 'checked' : '' ?>
                            >

                            <label
                                for="select_<?= esc($moduleId) ?>"
                                class="mb-0"
                            >
                                Tout sélectionner
                            </label>

                        </div>


                        <i class="bi bi-chevron-down permission-chevron"></i>

                    </div>

                </div>


                <!-- CORPS DU MODULE -->

                <div id="<?= esc($moduleId) ?>"
                     class="collapse show">

                    <div class="permission-section-body">

                        <div class="permissions-grid">

                            <?php foreach ($modulePermissions as $permission): ?>

                                <?php
                                $isChecked = in_array(
                                    (int) $permission['id'],
                                    $selectedPermissionIds,
                                    true
                                );
                                ?>

                                <div class="permission-item">

                                    <div class="form-check">

                                        <input
                                            type="checkbox"
                                            class="form-check-input permission-checkbox <?= esc($moduleId) ?>"
                                            name="permissions[]"
                                            value="<?= (int) $permission['id'] ?>"
                                            id="permission_<?= (int) $permission['id'] ?>"
                                            <?= $isChecked ? 'checked' : '' ?>
                                        >

                                        <label
                                            class="form-check-label"
                                            for="permission_<?= (int) $permission['id'] ?>"
                                        >
                                            <?= esc($permission['libelle']) ?>
                                        </label>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>


    <!-- =====================================================
         BOUTONS
         ===================================================== -->

    <div class="permissions-footer d-flex justify-content-end">

        <a href="<?= site_url('roles') ?>"
           class="btn btn-light border me-2">

            Annuler

        </a>

        <button type="submit"
                class="btn lc-btn-primary px-4">

            <i class="bi bi-check-lg me-1"></i>

            Enregistrer les permissions

        </button>

    </div>

</form>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Tout sélectionner / désélectionner
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.select-module').forEach(function (master) {

        master.addEventListener('change', function (event) {

            event.stopPropagation();

            const moduleId = this.dataset.module;

            document
                .querySelectorAll('.' + moduleId)
                .forEach(function (checkbox) {

                    checkbox.checked = master.checked;

                });

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Mise à jour automatique du "Tout sélectionner"
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.permission-checkbox')
        .forEach(function (checkbox) {

            checkbox.addEventListener('change', function () {

                const moduleClass = Array
                    .from(this.classList)
                    .find(function (className) {

                        return className.startsWith('module_');

                    });

                if (!moduleClass) {
                    return;
                }

                const moduleCheckboxes = document.querySelectorAll(
                    '.' + moduleClass
                );

                const master = document.querySelector(
                    '.select-module[data-module="' +
                    moduleClass +
                    '"]'
                );

                if (!master) {
                    return;
                }

                const checkedCount = document.querySelectorAll(
                    '.' + moduleClass + ':checked'
                ).length;

                master.checked =
                    checkedCount === moduleCheckboxes.length;

                master.indeterminate =
                    checkedCount > 0 &&
                    checkedCount < moduleCheckboxes.length;

            });

        });

});

</script>


<?= $this->endSection() ?>