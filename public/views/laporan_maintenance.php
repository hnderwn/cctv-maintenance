<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>

<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="index.php?page=maintenance_create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Laporan Maintenance
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Tanggal & Jam</th>
                    <th>Lokasi CCTV</th>
                    <th>Teknisi</th>
                    <th>Deskripsi (Singkat)</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftar_maintenance)): ?>
                    <?php foreach ($daftar_maintenance as $log): ?>
                        <tr class="expandable-row" data-bs-toggle="collapse" data-bs-target="#detail-maint-<?php echo $log['id_log']; ?>">
                            <td><strong><?php echo htmlspecialchars(date('d M Y', strtotime($log['tanggal']))); ?></strong><br><small class="text-muted"><?php echo htmlspecialchars(date('H:i', strtotime($log['jam']))); ?> WIB</small></td>
                            <td><?php echo htmlspecialchars($log['cctv_lokasi']); ?></td>
                            <td><?php echo htmlspecialchars($log['nama_teknisi']); ?></td>
                            <td>
                                <?php
                                    // Potong deskripsi jika lebih dari 100 karakter
                                    $desc = $log['deskripsi_log'];
                                    echo htmlspecialchars(strlen($desc) > 100 ? substr($desc, 0, 100) . '...' : $desc);
                                ?>
                            </td>
                            <td class="text-center">
                                <a href="index.php?page=maintenance_edit&id=<?php echo $log['id_log']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                <a href="index.php?page=maintenance_delete&id=<?php echo $log['id_log']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus laporan ini?');"><i class="bi bi-trash-fill"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="p-0">
                                <div class="collapse" id="detail-maint-<?php echo $log['id_log']; ?>">
                                    <div class="detail-row">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Detail Laporan</h6>
                                                <ul class="list-unstyled mb-0">
                                                    <li><strong>ID CCTV:</strong> <?php echo htmlspecialchars($log['id_cctv']); ?></li>
                                                    <li><strong>Lokasi:</strong> <?php echo htmlspecialchars($log['cctv_lokasi']); ?></li>
                                                    <li><strong>ID Teknisi:</strong> <?php echo htmlspecialchars($log['id_teknisi']); ?></li>
                                                    <li><strong>Nama Teknisi:</strong> <?php echo htmlspecialchars($log['nama_teknisi']); ?></li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Deskripsi Lengkap</h6>
                                                <p class="mb-0" style="white-space: pre-wrap;"><?php echo htmlspecialchars($log['deskripsi_log']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted p-5 bg-light">Belum ada laporan maintenance.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>