<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>


<main class="content-wrapper">
    <?php require_once 'public/views/partials/navbar.php'; ?>

    <?php
    $isEdit = isset($log);
    ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="index.php?page=<?php echo $isEdit ? 'kerusakan_update' : 'kerusakan_store'; ?>" method="POST">

                <?php if ($isEdit): ?>
                    <input type="hidden" name="id_log" value="<?php echo htmlspecialchars($log['id_log']); ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal" class="form-label">Tanggal Lapor</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $isEdit ? htmlspecialchars($log['tanggal']) : date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jam" class="form-label">Jam Lapor</label>
                        <input type="time" class="form-control" id="jam" name="jam" value="<?php echo htmlspecialchars($log['jam']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="id_cctv" class="form-label">CCTV yang Rusak</label>
                    <select class="form-select" name="id_cctv" required>
                        <option value="">-- Pilih Lokasi CCTV --</option>
                        <?php foreach ($daftar_cctv as $cctv): ?>
                            <option value="<?php echo $cctv['id_cctv']; ?>" <?php echo ($isEdit && $cctv['id_cctv'] == $log['id_cctv']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cctv['lokasi']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_teknisi" class="form-label">Tugaskan Teknisi (Opsional)</label>
                    <select class="form-select" name="id_teknisi">
                        <option value="">-- Belum Ditugaskan --</option>
                        <?php foreach ($daftar_teknisi as $teknisi): ?>
                            <option value="<?php echo $teknisi['id_teknisi']; ?>" <?php echo ($isEdit && $teknisi['id_teknisi'] == $log['id_teknisi']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($teknisi['nama_teknisi']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="deskripsi_kerusakan" class="form-label">Deskripsi Kerusakan</label>
                    <textarea class="form-control" name="deskripsi_kerusakan" rows="4" required><?php echo $isEdit ? htmlspecialchars($log['deskripsi_kerusakan']) : ''; ?></textarea>
                </div>

                <a href="index.php?page=laporan_kerusakan" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Simpan Perubahan' : 'Tambah Laporan'; ?></button>
            </form>
        </div>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>