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
        <a href="index.php?page=cctv_tipe_create" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Tambah Tipe CCTV</a>
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <?php
                        function createSortLink($column, $displayName, $currentSort, $currentOrder) {
                            $order = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
                            $icon = '';
                            if ($currentSort === $column) {
                                $icon = $currentOrder === 'ASC' ? '<i class="bi bi-sort-up-alt ms-1"></i>' : '<i class="bi bi-sort-down ms-1"></i>';
                            }
                            // Ganti 'page=teknisi' menjadi 'page=cctv_tipe'
                            return "<a href='index.php?page=cctv_tipe&sort=$column&order=$order'>$displayName $icon</a>";
                        }
                    ?>
                    <th><?php echo createSortLink('id_model', 'ID Model', $sortBy, $sortOrder); ?></th>
                    <th><?php echo createSortLink('nama_model', 'Nama Model', $sortBy, $sortOrder); ?></th>
                    <th><?php echo createSortLink('manufaktur', 'Manufaktur', $sortBy, $sortOrder); ?></th>
                    <th>Spesifikasi (Singkat)</th> <?php if ($_SESSION['role'] === 'admin'): ?>
                        <th class="text-center">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftar_model)): ?>
                    <?php foreach ($daftar_model as $model): ?>
                        <tr class="expandable-row" data-bs-toggle="collapse" data-bs-target="#detail-<?php echo $model['id_model']; ?>">
                            <td data-label="ID Model"><strong><?php echo htmlspecialchars($model['id_model']); ?></strong></td>
                            <td data-label="Nama Model"><?php echo htmlspecialchars($model['nama_model']); ?></td>
                            <td data-label="Manufaktur"><?php echo htmlspecialchars($model['manufaktur']); ?></td>
                            <td data-label="Spesifikasi">
                                <?php
                                    $spec = $model['spesifikasi'];
                                    echo htmlspecialchars(strlen($spec) > 50 ? substr($spec, 0, 50) . '...' : $spec);
                                ?>
                            </td>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                            <td class="text-center">
                                <a href="index.php?page=cctv_tipe_edit&id=<?php echo $model['id_model']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                <a href="index.php?page=cctv_tipe_delete&id=<?php echo $model['id_model']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td colspan="<?php echo ($_SESSION['role'] === 'admin') ? '5' : '4'; ?>" class="p-0">
                                <div class="collapse" id="detail-<?php echo $model['id_model']; ?>">
                                    <div class="detail-row">
                                        <ul class="list-unstyled mb-0">
                                            <li><strong>ID Model:</strong> <?php echo htmlspecialchars($model['id_model']); ?></li>
                                            <li><strong>Nama Model:</strong> <?php echo htmlspecialchars($model['nama_model']); ?></li>
                                            <li><strong>Manufaktur:</strong> <?php echo htmlspecialchars($model['manufaktur']); ?></li>
                                            <li><strong>Umur Ekonomis:</strong> <?php echo htmlspecialchars($model['umur_ekonomis_th']); ?> Tahun</li>
                                            <hr class="my-2">
                                            <li><strong>Spesifikasi Lengkap:</strong><br><?php echo nl2br(htmlspecialchars($model['spesifikasi'])); ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="<?php echo ($_SESSION['role'] === 'admin') ? '5' : '4'; ?>" class="text-center text-muted p-5 bg-light">Belum ada data tipe CCTV.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>