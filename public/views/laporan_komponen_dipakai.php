<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>

<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['success_message'];
            unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $_SESSION['error_message'];
            unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="index.php?page=komponen_dipakai_create" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Input Pemakaian Komponen</a>
        <a href="index.php?page=export_komponen_dipakai" class="btn btn-success">
            <i class="bi bi-file-earmark-excel-fill me-2"></i>Export ke Excel
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Tanggal & Jam</th>
                    <th>Lokasi CCTV</th>
                    <th>Jenis Laporan</th>
                    <th>Nama Komponen</th>
                    <th>Jumlah</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftar_log_komponen)): ?>
                    <?php foreach ($daftar_log_komponen as $log): ?>
                        <tr class="expandable-row" data-bs-toggle="collapse" data-bs-target="#detail-komp-<?php echo $log['id']; ?>">
                            <td data-label="Tanggal"><strong><?php echo htmlspecialchars(date('d M Y', strtotime($log['tanggal_laporan']))); ?></strong><br><small class="text-muted"><?php echo htmlspecialchars(date('H:i', strtotime($log['jam_laporan']))); ?> WIB</small></td>
                            <td data-label="Lokasi"><?php echo htmlspecialchars($log['lokasi_cctv']); ?></td>
                            <td data-label="Laporan">
                                <?php $badge = $log['jenis_laporan'] == 'Maintenance' ? 'bg-primary' : 'bg-warning text-dark';
                                echo '<span class="badge ' . $badge . '">' . htmlspecialchars($log['jenis_laporan']) . '</span>'; ?>
                            </td>
                            <td data-label="Nama Komponen"><?php echo htmlspecialchars($log['nama_komponen']); ?></td>
                            <td data-label="Jumlah"><?php echo htmlspecialchars($log['jumlah_dipakai']); ?></td>
                            <td class="text-center">
                                <a href="index.php?page=komponen_dipakai_edit&id=<?php echo $log['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                <a href="index.php?page=komponen_dipakai_delete&id=<?php echo $log['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus log ini? Stok komponen akan dikembalikan.');"><i class="bi bi-trash-fill"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="p-0">
                                <div class="collapse" id="detail-komp-<?php echo $log['id']; ?>">
                                    <div class="detail-row">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <h6>Referensi</h6>
                                                <ul class="list-unstyled mb-lg-0">
                                                    <li><strong>ID Log Pemakaian:</strong> <?php echo htmlspecialchars($log['id']); ?></li>
                                                    <li><strong>Referensi Laporan:</strong> <?php echo htmlspecialchars($log['jenis_laporan'] . ' #' . $log['id_laporan_referensi']); ?></li>
                                                    <li><strong>ID Unit CCTV:</strong> <?php echo htmlspecialchars($log['id_cctv']); ?></li>
                                                    <li><strong>Lokasi:</strong> <?php echo htmlspecialchars($log['lokasi_cctv']); ?></li>
                                                    <li><strong>ID Teknisi:</strong> <?php echo htmlspecialchars($log['id_teknisi']); ?></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <h6>Detail Pemakaian</h6>
                                                <ul class="list-unstyled mb-0">
                                                    <li><strong>Detail Komponen:</strong> [<?php echo htmlspecialchars($log['id_komponen']); ?>] <?php echo htmlspecialchars($log['nama_komponen']); ?> (Satuan: <?php echo htmlspecialchars($log['satuan']); ?>)</li>
                                                    <li><strong>Rincian Biaya:</strong> (Rp <?php echo number_format($log['harga_satuan'], 0, ',', '.'); ?> x <?php echo htmlspecialchars($log['jumlah_dipakai']); ?>) = <strong>Rp <?php echo number_format($log['biaya'], 0, ',', '.'); ?></strong></li>
                                                </ul>
                                                <h6 class="mt-3">Deskripsi Laporan Terkait</h6>
                                                <p class="mb-0" style="white-space: pre-wrap;"><?php echo htmlspecialchars($log['deskripsi_laporan']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted p-5 bg-light">Belum ada data komponen yang dipakai.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once 'public/views/partials/footer.php'; ?>