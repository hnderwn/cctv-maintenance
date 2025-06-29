<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>

<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>

    <?php
    $isEdit = isset($teknisi);
    ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="index.php?page=<?php echo $isEdit ? 'teknisi_update' : 'teknisi_store'; ?>" method="POST">
                
                <div class="mb-3">
                    <label for="id_teknisi" class="form-label">ID Teknisi</label>
                    <input type="text" class="form-control" id="id_teknisi" name="id_teknisi" value="<?php echo $isEdit ? htmlspecialchars($teknisi['id_teknisi']) : ''; ?>" <?php echo $isEdit ? 'readonly' : 'required'; ?>>
                    <?php if (!$isEdit): ?>
                        <div class="form-text">Pastikan ID ini unik.</div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="nama_teknisi" class="form-label">Nama Teknisi</label>
                    <input type="text" class="form-control" id="nama_teknisi" name="nama_teknisi" value="<?php echo $isEdit ? htmlspecialchars($teknisi['nama_teknisi']) : ''; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="kontak" class="form-label">Kontak</label>
                    <input type="text" class="form-control" id="kontak" name="kontak" value="<?php echo $isEdit ? htmlspecialchars($teknisi['kontak']) : ''; ?>" placeholder="Nomor Telepon" required>
                </div>

                <a href="index.php?page=teknisi" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i><?php echo $isEdit ? 'Simpan Perubahan' : 'Tambah Teknisi'; ?>
                </button>
            </form>
        </div>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>