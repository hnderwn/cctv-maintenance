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

    <?php if ($_SESSION['role'] === 'admin'): ?>
    <div class="mb-3">
        <a href="index.php?page=teknisi_create" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Tambah Teknisi</a>
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <?php
                        // Helper untuk membuat link sorting
                        function createSortLink($column, $displayName, $currentSort, $currentOrder) {
                            $order = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
                            $icon = '';
                            if ($currentSort === $column) {
                                $icon = $currentOrder === 'ASC' ? '<i class="bi bi-sort-up-alt ms-1"></i>' : '<i class="bi bi-sort-down ms-1"></i>';
                            }
                            return "<a href='index.php?page=teknisi&sort=$column&order=$order'>$displayName $icon</a>";
                        }
                    ?>
                    <th><?php echo createSortLink('id_teknisi', 'ID Teknisi', $sortBy, $sortOrder); ?></th>
                    <th><?php echo createSortLink('nama_teknisi', 'Nama Teknisi', $sortBy, $sortOrder); ?></th>
                    <th><?php echo createSortLink('kontak', 'Kontak', $sortBy, $sortOrder); ?></th>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <th class="text-center">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftar_teknisi)): ?>
                    <?php foreach ($daftar_teknisi as $teknisi): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($teknisi['id_teknisi']); ?></strong></td>
                            <td><?php echo htmlspecialchars($teknisi['nama_teknisi']); ?></td>
                            <td><?php echo htmlspecialchars($teknisi['kontak']); ?></td>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                            <td class="text-center">
                                <a href="index.php?page=teknisi_edit&id=<?php echo $teknisi['id_teknisi']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                <a href="index.php?page=teknisi_delete&id=<?php echo $teknisi['id_teknisi']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');"><i class="bi bi-trash-fill"></i></a>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="<?php echo ($_SESSION['role'] === 'admin') ? '4' : '3'; ?>" class="text-center text-muted p-5 bg-light">Data teknisi belum ada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>