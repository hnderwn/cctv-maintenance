<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>

<main class="content-wrapper">
    <?php require_once 'public/views/partials/navbar.php'; ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'admin'): ?>
    <div class="mb-3">
        <a href="index.php?page=cctv_unit_create" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Tambah Unit CCTV</a>
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
                            // Ganti page= jadi 'cctv_unit'
                            return "<a href='index.php?page=cctv_unit&sort=$column&order=$order'>$displayName $icon</a>";
                        }
                    ?>
                    <th><?php echo createSortLink('id_cctv', 'ID CCTV', $sortBy, $sortOrder); ?></th>
                    <th><?php echo createSortLink('lokasi', 'Lokasi', $sortBy, $sortOrder); ?></th>
                    <th><?php echo createSortLink('nama_model', 'Model', $sortBy, $sortOrder); ?></th>
                    <th><?php echo createSortLink('status', 'Status', $sortBy, $sortOrder); ?></th>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <th class="text-center">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftar_cctv)): ?>
                    <?php foreach ($daftar_cctv as $cctv): ?>
                        <tr class="expandable-row" data-bs-toggle="collapse" data-bs-target="#detail-<?php echo str_replace(' ', '-', $cctv['id_cctv']); ?>">
                            <td data-label="ID CCTV"><strong><?php echo htmlspecialchars($cctv['id_cctv']); ?></strong></td>
                            <td data-label="Lokasi"><?php echo htmlspecialchars($cctv['lokasi']); ?></td>
                            <td data-label="Nama Model"><?php echo htmlspecialchars($cctv['nama_model']); ?></td>
                            <td data-label="Status">
                                <?php 
                                    $status_class = '';
                                    switch ($cctv['status']) {
                                        case 'Aktif': $status_class = 'bg-success'; break;
                                        case 'Rusak': $status_class = 'bg-danger'; break;
                                        case 'Dalam Perbaikan': case 'Dalam Perawatan': $status_class = 'bg-warning text-dark'; break;
                                        default: $status_class = 'bg-secondary'; break;
                                    }
                                    echo '<span class="badge ' . $status_class . '">' . htmlspecialchars($cctv['status']) . '</span>';
                                ?>
                            </td>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                            <td class="text-center">
                                <a href="index.php?page=cctv_unit_edit&id=<?php echo $cctv['id_cctv']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                <a href="index.php?page=cctv_unit_delete&id=<?php echo $cctv['id_cctv']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data CCTV ini?');"><i class="bi bi-trash-fill"></i></a>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td colspan="<?php echo ($_SESSION['role'] === 'admin') ? '5' : '4'; ?>" class="p-0">
                                <div class="collapse" id="detail-<?php echo str_replace(' ', '-', $cctv['id_cctv']); ?>">
                                    <div class="detail-row">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Detail Unit CCTV</h6>
                                                <ul class="list-unstyled mb-0">
                                                    <li><strong>ID Unit:</strong> <?php echo htmlspecialchars($cctv['id_cctv']); ?></li>
                                                    <li><strong>Lokasi:</strong> <?php echo htmlspecialchars($cctv['lokasi']); ?></li>
                                                    <li><strong>Keterangan:</strong> <?php echo !empty($cctv['keterangan']) ? nl2br(htmlspecialchars($cctv['keterangan'])) : '-'; ?></li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Detail Model CCTV</h6>
                                                <ul class="list-unstyled mb-0">
                                                    <li><strong>ID Model:</strong> <?php echo htmlspecialchars($cctv['id_model']); ?></li>
                                                    <li><strong>Model:</strong> <?php echo htmlspecialchars($cctv['nama_model']); ?></li>
                                                    <li><strong>Manufaktur:</strong> <?php echo htmlspecialchars($cctv['manufaktur']); ?></li>
                                                    <li><strong>Spesifikasi:</strong> <?php echo !empty($cctv['spesifikasi']) ? nl2br(htmlspecialchars($cctv['spesifikasi'])) : '-'; ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="<?php echo ($_SESSION['role'] === 'admin') ? '5' : '4'; ?>" class="text-center text-muted p-5 bg-light">Belum ada data unit CCTV.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>