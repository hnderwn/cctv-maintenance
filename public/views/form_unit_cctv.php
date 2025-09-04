<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>

<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>

    <?php
    $isEdit = isset($cctv); 
    ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="index.php?page=<?php echo $isEdit ? 'cctv_unit_update' : 'cctv_unit_store'; ?>" method="POST">
                
                <div class="mb-3">
                    <label for="id_cctv" class="form-label">ID Unit CCTV</label>
                    <input type="text" class="form-control" id="id_cctv" name="id_cctv" value="<?php echo $isEdit ? htmlspecialchars($cctv['id_cctv']) : ''; ?>" <?php echo $isEdit ? 'readonly' : 'required'; ?>>
                </div>
                
                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi Pemasangan</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?php echo $isEdit ? htmlspecialchars($cctv['lokasi']) : ''; ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_model" class="form-label">Model/Tipe CCTV</label>
                        <select class="form-select" id="id_model" name="id_model" required>
                            <option value="">-- Pilih Model --</option>
                            <?php foreach($daftar_model as $model): ?>
                                <option value="<?php echo $model['id_model']; ?>" <?php echo ($isEdit && $model['id_model'] == $cctv['id_model']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($model['nama_model']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Aktif" <?php echo ($isEdit && $cctv['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="Tidak Aktif" <?php echo ($isEdit && $cctv['status'] == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                            <option value="Perbaikan" <?php echo ($isEdit && $cctv['status'] == 'Perbaikan') ? 'selected' : ''; ?>>Dalam Perbaikan</option>
                            <option value="Perawatan" <?php echo ($isEdit && $cctv['status'] == 'Perawatan') ? 'selected' : ''; ?>>Dalam Perawatan</option>
                            <option value="Rusak" <?php echo ($isEdit && $cctv['status'] == 'Rusak') ? 'selected' : ''; ?>>Rusak</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo $isEdit ? htmlspecialchars($cctv['keterangan']) : ''; ?></textarea>
                </div>

                <a href="index.php?page=cctv" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <?php echo $isEdit ? 'Simpan Perubahan' : 'Tambah Unit CCTV'; ?>
                </button>
            </form>
        </div>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>
