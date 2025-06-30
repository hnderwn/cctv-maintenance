<?php require_once 'partials/header.php'; ?>
<?php require_once 'partials/sidebar.php'; ?>

<main class="content-wrapper">
    <?php require_once 'partials/navbar.php'; ?>

    <h3 class="mb-4">Dashboard</h3>
    
    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-success border-5 border-top-0 border-bottom-0 border-end-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-success fw-bold text-uppercase mb-1">CCTV Aktif</div>
                        <div class="h2 mb-0 fw-bold text-gray-800"><?php echo $stats['cctv_aktif']; ?></div>
                    </div>
                    <i class="bi bi-camera-video-fill fs-1 text-success opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-danger border-5 border-top-0 border-bottom-0 border-end-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-danger fw-bold text-uppercase mb-1">CCTV Bermasalah</div>
                        <div class="h2 mb-0 fw-bold text-gray-800"><?php echo $stats['cctv_bermasalah']; ?></div>
                    </div>
                    <i class="bi bi-exclamation-triangle-fill fs-1 text-danger opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-primary border-5 border-top-0 border-bottom-0 border-end-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-primary fw-bold text-uppercase mb-1">Total Teknisi</div>
                        <div class="h2 mb-0 fw-bold text-gray-800"><?php echo $stats['total_teknisi']; ?></div>
                    </div>
                    <i class="bi bi-person-badge-fill fs-1 text-primary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <h5 class="mb-3">Akses Cepat</h5>
    <div class="row g-3">
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="index.php?page=maintenance_create" class="card card-box shadow-sm text-center p-3">
                <i class="bi bi-tools fs-1 text-info mb-2"></i>
                <h6 class="mb-0">Input Maintenance</h6>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="index.php?page=kerusakan_create" class="card card-box shadow-sm text-center p-3">
                <i class="bi bi-bug-fill fs-1 text-danger mb-2"></i>
                <h6 class="mb-0">Lapor Kerusakan</h6>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="index.php?page=komponen_dipakai_create" class="card card-box shadow-sm text-center p-3">
                <i class="bi bi-cart-plus-fill fs-1 text-success mb-2"></i>
                <h6 class="mb-0">Input Komponen Dipakai</h6>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="index.php?page=teknisi_create" class="card card-box shadow-sm text-center p-3">
                <i class="bi bi-person-plus-fill fs-1 text-primary mb-2"></i>
                <h6 class="mb-0">Tambah Teknisi</h6>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="index.php?page=cctv_unit_create" class="card card-box shadow-sm text-center p-3">
                <i class="bi bi-camera-reels-fill fs-1 text-secondary mb-2"></i>
                <h6 class="mb-0">Tambah Unit CCTV</h6>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="index.php?page=cctv_tipe_create" class="card card-box shadow-sm text-center p-3">
                <i class="bi bi-tags-fill fs-1 text-warning mb-2"></i>
                <h6 class="mb-0">Tambah Tipe CCTV</h6>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="index.php?page=komponen_create" class="card card-box shadow-sm text-center p-3">
                <i class="bi bi-box-seam-fill fs-1 text-dark mb-2"></i>
                <h6 class="mb-0">Tambah Komponen</h6>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="index.php?page=user_create" class="card card-box shadow-sm text-center p-3">
                <i class="bi bi-person-lock fs-1 text-primary-emphasis mb-2"></i>
                <h6 class="mb-0">Tambah User</h6>
            </a>
        </div>
        
        <hr class="my-2">
        <div class="text-center mt-4">
            <a href="index.php?page=about_us" class="btn btn-outline-primary">
                Lihat Tim Kami <i class="bi bi-arrow-right-short"></i>
            </a>
            <p class="text-muted mb-0"><small>Aplikasi ini dikembangkan dengan ❤️ oleh Tim Hebat.</small></p>
        </div>
    </div>
</main>

<?php require_once 'partials/footer.php'; ?>