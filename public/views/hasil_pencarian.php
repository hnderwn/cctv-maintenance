<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>

<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <?php if (empty($query)): ?>
        <div class="alert alert-info">Silakan masukkan kata kunci pada kotak pencarian di atas.</div>
    <?php else: ?>
        <?php 
            // Hitungan total diupdate
            $total_results = count($results['teknisi']) + count($results['cctv_unit']) + count($results['komponen']) + count($results['cctv_tipe']);
        ?>
        <?php if ($total_results > 0): ?>
            <p class="text-muted">Ditemukan total <?php echo $total_results; ?> hasil yang cocok.</p>

            <?php if (!empty($results['cctv_tipe'])): ?>
                <h5 class="mt-4">Ditemukan di Data Tipe CCTV (<?php echo count($results['cctv_tipe']); ?>)</h5>
                <ul class="list-group">
                    <?php foreach($results['cctv_tipe'] as $item): ?>
                        <li class="list-group-item">
                            <a href="index.php?page=cctv_tipe_edit&id=<?php echo $item['id_model']; ?>" class="text-decoration-none">
                                <strong><?php echo htmlspecialchars($item['nama_model']); ?></strong>
                            </a>
                            <br><small class="text-muted">Manufaktur: <?php echo htmlspecialchars($item['manufaktur']); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($results['teknisi'])): ?>
                <h5 class="mt-4">Ditemukan di Data Teknisi (<?php echo count($results['teknisi']); ?>)</h5>
                <ul class="list-group">
                    <?php foreach($results['teknisi'] as $item): ?>
                        <li class="list-group-item">
                            <a href="index.php?page=teknisi_edit&id=<?php echo $item['id_teknisi']; ?>" class="text-decoration-none">
                                <strong><?php echo htmlspecialchars($item['nama_teknisi']); ?></strong>
                            </a>
                            <br><small class="text-muted">Kontak: <?php echo htmlspecialchars($item['kontak']); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($results['cctv_unit'])): ?>
                <h5 class="mt-4">Ditemukan di Data Unit CCTV (<?php echo count($results['cctv_unit']); ?>)</h5>
                <ul class="list-group">
                    <?php foreach($results['cctv_unit'] as $item): ?>
                        <li class="list-group-item">
                            <a href="index.php?page=cctv_unit_edit&id=<?php echo $item['id_cctv']; ?>" class="text-decoration-none">
                                <strong><?php echo htmlspecialchars($item['lokasi']); ?></strong>
                            </a>
                            <br><small class="text-muted">ID: <?php echo htmlspecialchars($item['id_cctv']); ?> | Status: <?php echo htmlspecialchars($item['status']); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($results['komponen'])): ?>
                <h5 class="mt-4">Ditemukan di Data Komponen (<?php echo count($results['komponen']); ?>)</h5>
                <ul class="list-group">
                    <?php foreach($results['komponen'] as $item): ?>
                        <li class="list-group-item">
                            <a href="index.php?page=komponen_edit&id=<?php echo $item['id_komponen']; ?>" class="text-decoration-none">
                                <strong><?php echo htmlspecialchars($item['nama_komponen']); ?></strong>
                            </a>
                            <br><small class="text-muted">ID: <?php echo htmlspecialchars($item['id_komponen']); ?> | Stok: <?php echo htmlspecialchars($item['stok']); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-warning">Tidak ada hasil yang ditemukan untuk kata kunci "<strong><?php echo htmlspecialchars($query); ?></strong>".</div>
        <?php endif; ?>
    <?php endif; ?>

</main>

<?php require_once 'partials/footer.php'; ?>