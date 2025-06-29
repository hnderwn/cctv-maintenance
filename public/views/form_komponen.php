<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>

<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>

    <?php $isEdit = isset($komponen); ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="index.php?page=<?php echo $isEdit ? 'komponen_update' : 'komponen_store'; ?>" method="POST">
                
                <div class="mb-3">
                    <label for="id_komponen" class="form-label">ID Komponen</label>
                    <input type="text" class="form-control" id="id_komponen" name="id_komponen" value="<?php echo $isEdit ? htmlspecialchars($komponen['id_komponen']) : ''; ?>" <?php echo $isEdit ? 'readonly' : 'required'; ?>>
                </div>
                
                <div class="mb-3">
                    <label for="nama_komponen" class="form-label">Nama Komponen</label>
                    <input type="text" class="form-control" id="nama_komponen" name="nama_komponen" value="<?php echo $isEdit ? htmlspecialchars($komponen['nama_komponen']) : ''; ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan" placeholder="e.g., Pcs, Unit, Meter" value="<?php echo $isEdit ? htmlspecialchars($komponen['satuan']) : ''; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $isEdit ? htmlspecialchars($komponen['stok']) : '0'; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="harga_satuan" class="form-label">Harga Satuan (Rp)</label>
                        <input type="number" class="form-control" id="harga_satuan" name="harga_satuan" value="<?php echo $isEdit ? htmlspecialchars($komponen['harga_satuan']) : '0'; ?>" required>
                    </div>
                </div>

                <a href="index.php?page=komponen" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <?php echo $isEdit ? 'Simpan Perubahan' : 'Tambah Komponen'; ?>
                </button>
            </form>
        </div>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>
