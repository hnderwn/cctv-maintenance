<aside class="sidebar">
    <h4 class="sidebar-title"><img src="public/assets/logo.jpg" alt="Logo" style="width: 30px; margin-right: 10px;"><span class="nav-link-text">CCTV Maint.</span></h4>

    <nav class="nav flex-column mt-2">
        <a href="index.php?page=dashboard" class="nav-link"><i class="bi bi-speedometer2 me-2"></i><span class="nav-link-text">Dashboard</span></a>

        <div class="sidebar-heading-divider text-white-50 small text-uppercase my-2"><span class="nav-link-text">Data Master</span></div>
        <a href="index.php?page=cctv_tipe" class="nav-link"><i class="bi bi-tags me-2"></i><span class="nav-link-text">Tipe CCTV</span></a>
        <a href="index.php?page=cctv_unit" class="nav-link"><i class="bi bi-camera-video me-2"></i><span class="nav-link-text">Unit CCTV</span></a>
        <a href="index.php?page=teknisi" class="nav-link"><i class="bi bi-person-badge me-2"></i><span class="nav-link-text">Teknisi</span></a>
        <a href="index.php?page=komponen" class="nav-link"><i class="bi bi-box-seam me-2"></i><span class="nav-link-text">Komponen</span></a>

        <div class="sidebar-heading-divider text-white-50 small text-uppercase my-2"><span class="nav-link-text">Aktivitas</span></div>
        <a href="index.php?page=laporan_maintenance" class="nav-link"><i class="bi bi-clipboard-data me-2"></i><span class="nav-link-text">Laporan Maintenance</span></a>
        <a href="index.php?page=laporan_kerusakan" class="nav-link"><i class="bi bi-clipboard-x me-2"></i><span class="nav-link-text">Laporan Kerusakan</span></a>
        <a href="index.php?page=laporan_komponen_dipakai" class="nav-link"><i class="bi bi-cart-plus-fill me-2"></i><span class="nav-link-text">Komponen Dipakai</span></a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <hr class="text-white-50">
            <a href="index.php?page=user" class="nav-link"><i class="bi bi-people-fill me-2"></i><span class="nav-link-text">Manajemen User</span></a>
        <?php endif; ?>

        <a href="index.php?page=logout" class="logout-btn nav-link">
            <i class="bi bi-box-arrow-right me-2"></i>
            <span class="nav-link-text">Logout</span>
        </a>
    </nav>
</aside>