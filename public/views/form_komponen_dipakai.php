<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>

<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>

    <?php
    $isEdit = isset($log);
    $selectedLogType = '';
    if ($isEdit) {
        if (!empty($log['id_log_maintenance'])) {
            $selectedLogType = 'maintenance';
        } elseif (!empty($log['id_log_kerusakan'])) {
            $selectedLogType = 'kerusakan';
        }
    }
    ?>

    <h1 class="h3 mb-4 text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="index.php?page=<?php echo $isEdit ? 'komponen_dipakai_update' : 'komponen_dipakai_store'; ?>" method="POST" id="form-komponen-dipakai">
                
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($log['id']); ?>">
                <?php endif; ?>
                
                <div class="mb-3">
                    <label for="log_type" class="form-label">Jenis Laporan</label>
                    <select class="form-select" name="log_type" id="log_type" required>
                        <option value="">-- Pilih Jenis Laporan --</option>
                        <option value="maintenance" <?php echo ($selectedLogType === 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                        <option value="kerusakan" <?php echo ($selectedLogType === 'kerusakan') ? 'selected' : ''; ?>>Kerusakan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="log_id" class="form-label">Pilih ID Laporan</label>
                    <select class="form-select" name="log_id" id="log_id" required>
                        <option value="">-- Pilih Jenis Laporan Dulu --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_komponen" class="form-label">Komponen yang Dipakai</label>
                    <select class="form-select" name="id_komponen" required>
                        <option value="">-- Pilih Komponen --</option>
                        <?php foreach ($daftar_komponen as $komponen): ?>
                            <option value="<?php echo htmlspecialchars($komponen['id_komponen']); ?>" <?php echo ($isEdit && $komponen['id_komponen'] === $log['id_komponen']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($komponen['nama_komponen']); ?> (Stok: <?php echo $komponen['stok']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jumlah_dipakai" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" name="jumlah_dipakai" value="<?php echo $isEdit ? htmlspecialchars($log['jumlah_dipakai']) : '1'; ?>" min="1" required>
                </div>

                <a href="index.php?page=laporan_komponen_dipakai" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Simpan Perubahan' : 'Simpan'; ?></button>
            </form>
        </div>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const logTypeSelect = document.getElementById('log_type');
    const logIdSelect = document.getElementById('log_id');
    const maintenanceOptions = <?php echo json_encode($daftar_maintenance); ?>;
    const kerusakanOptions = <?php echo json_encode($daftar_kerusakan); ?>;

    function populateLogId(type) {
        logIdSelect.innerHTML = '<option value="">Memuat...</option>';
        let options = [];
        if (type === 'maintenance') options = maintenanceOptions;
        if (type === 'kerusakan') options = kerusakanOptions;

        let optionsHTML = '<option value="">-- Pilih ID Laporan --</option>';
        options.forEach(function(opt) {
            let selected = '';
            <?php if ($isEdit): ?>
                let selectedId = '<?php echo !empty($log['id_log_maintenance']) ? $log['id_log_maintenance'] : $log['id_log_kerusakan']; ?>';
                if (opt.id_log == selectedId) {
                    selected = 'selected';
                }
            <?php endif; ?>
            optionsHTML += `<option value="${opt.id_log}" ${selected}>ID: ${opt.id_log} (${opt.cctv_lokasi})</option>`;
        });
        logIdSelect.innerHTML = optionsHTML;
    }

    logTypeSelect.addEventListener('change', function() {
        populateLogId(this.value);
    });
    
    // Jika ini mode edit, langsung panggil fungsinya sekali saat halaman dimuat
    <?php if ($isEdit && !empty($selectedLogType)): ?>
        populateLogId('<?php echo $selectedLogType; ?>');
    <?php endif; ?>
});
</script>