<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Utilisateurs & rôles</h4>
        <p class="text-muted mb-0">Gestion des comptes de l'agence.</p>
    </div>
    <a href="<?= site_url('utilisateurs/new') ?>" class="btn lc-btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nouvel utilisateur
    </a>
</div>

<div class="lc-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            Aucun utilisateur.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="fw-semibold">
                                <?= esc(trim(($u['prenom'] ?? '') . ' ' . ($u['nom'] ?? ''))) ?>
                            </td>
                            <td><?= esc($u['email']) ?></td>
                            <td><?= esc($u['role_libelle'] ?? '—') ?></td>
                            <td>
                                <?php if ($u['actif']): ?>
                                    <span class="badge lc-badge-actif">Actif</span>
                                <?php else: ?>
                                    <span class="badge lc-badge-inactif">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="<?= site_url('utilisateurs/' . $u['id'] . '/edit') ?>"
                                       class="btn btn-sm btn-light border">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= site_url('utilisateurs/' . $u['id'] . '/delete') ?>"
                                       class="btn btn-sm btn-light border text-danger"
                                       onclick="return confirm('Supprimer cet utilisateur ?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>