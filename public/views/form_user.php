<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>


<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>

    <?php $isEdit = isset($user); ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="index.php?page=<?php echo $isEdit ? 'user_update' : 'user_store'; ?>" method="POST">
                
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($user['id_user']); ?>">
                <?php endif; ?>
                
                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $isEdit ? htmlspecialchars($user['nama_lengkap']) : ''; ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $isEdit ? htmlspecialchars($user['username']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="user" <?php echo ($isEdit && $user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                            <option value="admin" <?php echo ($isEdit && $user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" <?php echo !$isEdit ? 'required' : ''; ?>>
                    <?php if ($isEdit): ?>
                        <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                    <?php endif; ?>
                </div>

                <a href="index.php?page=user" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <?php echo $isEdit ? 'Simpan Perubahan' : 'Tambah User'; ?>
                </button>
            </form>
        </div>
    </div>
</main>

<?php require_once 'public/views/partials/footer.php'; ?>
