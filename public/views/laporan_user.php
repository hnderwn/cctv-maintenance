<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>

<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="index.php?page=user_create" class="btn btn-primary"><i class="bi bi-person-plus-fill me-2"></i>Tambah User Baru</a>
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftar_user)): ?>
                    <?php foreach ($daftar_user as $user): ?>
                        <tr>
                            <td data-label="Nama Lengkap"><strong><?php echo htmlspecialchars($user['nama_lengkap']); ?></strong></td>
                            <td data-label="Username"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td data-label="Role"><span class="badge bg-info text-dark"><?php echo htmlspecialchars(ucfirst($user['role'])); ?></span></td>
                            <td class="text-center">
                                <a href="index.php?page=user_edit&id=<?php echo $user['id_user']; ?>" class="btn btn-sm btn-warning" title="Edit User">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <?php if ($_SESSION['id_user'] != $user['id_user']): // Tombol aksi tidak muncul untuk diri sendiri ?>
                                    <a href="index.php?page=user_reset_password&id=<?php echo $user['id_user']; ?>" class="btn btn-sm btn-secondary" title="Reset Password" onclick="return confirm('Yakin ingin mereset password user ini menjadi `123456`?');">
                                        <i class="bi bi-key-fill"></i>
                                    </a>
                                    <a href="index.php?page=user_delete&id=<?php echo $user['id_user']; ?>" class="btn btn-sm btn-danger" title="Hapus User" onclick="return confirm('Yakin ingin menghapus user ini? Aksi ini tidak bisa dibatalkan.');">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted p-5 bg-light">Belum ada data user.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>