<header class="main-navbar">
    <div class="navbar-left">
        <button class="btn me-2 d-block" type="button" id="sidebar-toggle-btn">
            <i class="bi bi-list"></i>
        </button>
        
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" method="GET" action="index.php">
            <input type="hidden" name="page" value="search">
            <div class="input-group">
                <input class="form-control" type="text" name="q" placeholder="Cari data..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" />
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>

    </div>
    <div class="navbar-right">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-4 me-2"></i>
                <span><?php echo isset($_SESSION['nama_lengkap']) ? htmlspecialchars($_SESSION['nama_lengkap']) : 'User'; ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="#">Profil (Soon)</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="index.php?page=logout">Logout</a></li>
            </ul>
        </div>
    </div>
</header>