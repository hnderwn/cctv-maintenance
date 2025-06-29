<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>


<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>
    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <!-- Notifikasi -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tombol Tambah, hanya untuk Admin -->
    <?php if ($_SESSION['role'] === 'admin'): ?>
    <div class="mb-3">
        <a href="index.php?page=komponen_create" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Tambah Komponen</a>
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>ID Komponen</th>
                    <th>Nama Komponen</th>
                    <th>Satuan</th>
                    <th>Stok</th>
                    <th>Harga Satuan (Rp)</th>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <th class="text-center">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftar_komponen)): ?>
                    <?php foreach ($daftar_komponen as $item): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($item['id_komponen']); ?></strong></td>
                            <td><?php echo htmlspecialchars($item['nama_komponen']); ?></td>
                            <td><?php echo htmlspecialchars($item['stok']); ?></td>
                            <td><?php echo htmlspecialchars($item['satuan']); ?></td>
                            <td>Rp.<?php echo number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                            <td class="text-center">
                                <a href="index.php?page=komponen_edit&id=<?php echo $item['id_komponen']; ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="index.php?page=komponen_delete&id=<?php echo $item['id_komponen']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="<?php echo ($_SESSION['role'] === 'admin') ? '6' : '5'; ?>" class="text-center text-muted p-5 bg-light">Belum ada data komponen.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>
