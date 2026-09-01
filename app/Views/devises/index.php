<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Devises</h4>
        <p class="text-muted mb-0">Gestion des devises et taux de change</p>
    </div>

    <a href="<?= site_url('devises/new') ?>" class="btn lc-btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Nouvelle devise
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="lc-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Nom</th>
                    <th>Symbole</th>
                    <th class="text-end">Taux de change</th>
                    <th class="text-center">Par défaut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($devises)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucune devise enregistrée.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($devises as $devise): ?>
                        <tr>
                            <td>
                                <span class="fw-semibold"><?= esc($devise['code']) ?></span>
                            </td>
                            <td><?= esc($devise['nom']) ?></td>
                            <td><?= esc($devise['symbole']) ?></td>
                            <td class="text-end">
                                <?= number_format((float)$devise['taux_change'], 0, '', ' ') ?>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($devise['is_default'])): ?>
                                    <span class="badge bg-success">Par défaut</span>
                                <?php else: ?>
                                    <form method="post"
                                          action="<?= site_url('devises/' . $devise['id'] . '/set-default') ?>"
                                          class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            Définir
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= site_url('devises/' . $devise['id'] . '/edit') ?>"
                                   class="btn btn-sm btn-light border"
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <?php if (empty($devise['is_default'])): ?>
                                    <button type="button"
                                            class="btn btn-sm btn-light border text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDelete<?= $devise['id'] ?>"
                                            title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <!-- Modal suppression -->
                                    <div class="modal fade" id="modalDelete<?= $devise['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                                        Supprimer la devise
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Voulez-vous vraiment supprimer la devise
                                                    <strong><?= esc($devise['code']) ?></strong> ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                                                        Annuler
                                                    </button>
                                                    <form method="post"
                                                          action="<?= site_url('devises/' . $devise['id'] . '/delete') ?>">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-danger">
                                                            Oui, supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>