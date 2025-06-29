<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>


<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>

    <?php $isEdit = isset($model); ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="index.php?page=<?php echo $isEdit ? 'cctv_tipe_update' : 'cctv_tipe_store'; ?>" method="POST">
                
                <div class="mb-3">
                    <label for="id_model" class="form-label">ID Model</label>
                    <input type="text" class="form-control" id="id_model" name="id_model" value="<?php echo $isEdit ? htmlspecialchars($model['id_model']) : ''; ?>" <?php echo $isEdit ? 'readonly' : 'required'; ?>>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_model" class="form-label">Nama Model</label>
                        <input type="text" class="form-control" id="nama_model" name="nama_model" value="<?php echo $isEdit ? htmlspecialchars($model['nama_model']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="manufaktur" class="form-label">Manufaktur / Pabrikan</label>
                        <input type="text" class="form-control" id="manufaktur" name="manufaktur" value="<?php echo $isEdit ? htmlspecialchars($model['manufaktur']) : ''; ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="spesifikasi" class="form-label">Spesifikasi</label>
                    <textarea class="form-control" id="spesifikasi" name="spesifikasi" rows="3"><?php echo $isEdit ? htmlspecialchars($model['spesifikasi']) : ''; ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="umur_ekonomis_th" class="form-label">Umur Ekonomis (Tahun)</label>
                    <input type="number" class="form-control" id="umur_ekonomis_th" name="umur_ekonomis_th" value="<?php echo $isEdit ? htmlspecialchars($model['umur_ekonomis_th']) : '5'; ?>" required>
                </div>

                <a href="index.php?page=cctv_tipe" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <?php echo $isEdit ? 'Simpan Perubahan' : 'Tambah Tipe'; ?>
                </button>
            </form>
        </div>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>
