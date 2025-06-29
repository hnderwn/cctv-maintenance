<?php
// public/index.php

require_once '../vendor/autoload.php';

session_start();
$conn = require_once '../config/database.php';
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

switch ($page) {
    case 'login': case 'proses_login': case 'logout':
        require_once '../app/Controllers/AuthController.php'; $c = new AuthController($conn);
        if ($page === 'login') $c->login(); elseif ($page === 'proses_login') $c->prosesLogin(); else $c->logout();
        break;
    
    case 'dashboard':
        require_once '../app/Controllers/DashboardController.php'; (new DashboardController($conn))->index();
        break;

    case 'teknisi': case 'teknisi_create': case 'teknisi_store': case 'teknisi_edit': case 'teknisi_update': case 'teknisi_delete':
        require_once '../app/Controllers/TeknisiController.php'; $c = new TeknisiController($conn);
        if ($page === 'teknisi') $c->index(); elseif ($page === 'teknisi_create') $c->create(); elseif ($page === 'teknisi_store') $c->store(); elseif ($page === 'teknisi_edit') $c->edit(); elseif ($page === 'teknisi_update') $c->update(); else $c->delete();
        break;

    // Rute 'cctv' diubah menjadi 'cctv_unit'
    case 'cctv_unit': case 'cctv_unit_create': case 'cctv_unit_store': case 'cctv_unit_edit': case 'cctv_unit_update': case 'cctv_unit_delete':
        require_once '../app/Controllers/CctvUnitController.php'; $c = new CctvUnitController($conn);
        if ($page === 'cctv_unit') $c->index(); elseif ($page === 'cctv_unit_create') $c->create(); elseif ($page === 'cctv_unit_store') $c->store(); elseif ($page === 'cctv_unit_edit') $c->edit(); elseif ($page === 'cctv_unit_update') $c->update(); else $c->delete();
        break;

    // Rute 'cctv_type' diubah menjadi 'cctv_tipe'
    case 'cctv_tipe': case 'cctv_tipe_create': case 'cctv_tipe_store': case 'cctv_tipe_edit': case 'cctv_tipe_update': case 'cctv_tipe_delete':
        require_once '../app/Controllers/CctvTipeController.php'; $c = new CctvTipeController($conn);
        if ($page === 'cctv_tipe') $c->index(); elseif ($page === 'cctv_tipe_create') $c->create(); elseif ($page === 'cctv_tipe_store') $c->store(); elseif ($page === 'cctv_tipe_edit') $c->edit(); elseif ($page === 'cctv_tipe_update') $c->update(); else $c->delete();
        break;
    
    // (case untuk komponen tetap sama)
    case 'komponen': case 'komponen_create': case 'komponen_store': case 'komponen_edit': case 'komponen_update': case 'komponen_delete':
        require_once '../app/Controllers/KomponenController.php'; $c = new KomponenController($conn);
        if ($page === 'komponen') $c->index(); elseif ($page === 'komponen_create') $c->create(); elseif ($page === 'komponen_store') $c->store(); elseif ($page === 'komponen_edit') $c->edit(); elseif ($page === 'komponen_update') $c->update(); else $c->delete();
        break;
    
    // Rute laporan ditambah dengan aksi CRUD via halaman form
    case 'laporan_maintenance': case 'maintenance_create': case 'maintenance_store': case 'maintenance_edit': case 'maintenance_update': case 'maintenance_delete':
        require_once '../app/Controllers/MaintenanceController.php'; $c = new MaintenanceController($conn);
        if ($page === 'laporan_maintenance') $c->index();
        elseif ($page === 'maintenance_create') $c->create();
        elseif ($page === 'maintenance_store') $c->store();
        elseif ($page === 'maintenance_edit') $c->edit();
        elseif ($page === 'maintenance_update') $c->update();
        else $c->delete();
        break;
        
    case 'laporan_kerusakan': case 'kerusakan_create': case 'kerusakan_store': case 'kerusakan_edit': case 'kerusakan_update': case 'kerusakan_delete':
        require_once '../app/Controllers/KerusakanController.php'; $c = new KerusakanController($conn);
        if ($page === 'laporan_kerusakan') $c->index();
        elseif ($page === 'kerusakan_create') $c->create();
        elseif ($page === 'kerusakan_store') $c->store();
        elseif ($page === 'kerusakan_edit') $c->edit();
        elseif ($page === 'kerusakan_update') $c->update();
        else $c->delete();
        break;

        
    case 'laporan_komponen_dipakai':
    case 'komponen_dipakai_create':
    case 'komponen_dipakai_store':
    case 'komponen_dipakai_edit':
    case 'komponen_dipakai_update':
    case 'komponen_dipakai_delete':
        require_once '../app/Controllers/KomponenDipakaiController.php';
        $c = new KomponenDipakaiController($conn);
        if ($page === 'laporan_komponen_dipakai') $c->index();
        elseif ($page === 'komponen_dipakai_create') $c->create();
        elseif ($page === 'komponen_dipakai_store') $c->store();
        elseif ($page === 'komponen_dipakai_edit') $c->edit();
        elseif ($page === 'komponen_dipakai_update') $c->update();
        else $c->delete();
        break;
        
    case 'user': case 'user_create': case 'user_store': case 'user_edit': case 'user_update': case 'user_delete': case 'user_reset_password': // <-- RUTE BARU
        require_once '../app/Controllers/UserController.php'; $c = new UserController($conn);
        if ($page === 'user') $c->index();
        elseif ($page === 'user_create') $c->create();
        elseif ($page === 'user_store') $c->store();
        elseif ($page === 'user_edit') $c->edit();
        elseif ($page === 'user_update') $c->update();
        elseif ($page === 'user_reset_password') $c->resetPassword(); // <-- AKSI BARU
        else $c->delete();
        break;

    // Rute untuk semua fitur Komponen Dipakai
    case 'laporan_komponen_dipakai':
    case 'komponen_dipakai_create':
    case 'komponen_dipakai_store':
        require_once '../app/Controllers/KomponenDipakaiController.php';
        $c = new KomponenDipakaiController($conn);
        if ($page === 'laporan_komponen_dipakai') {
            $c->index(); // Menampilkan laporan
        } elseif ($page === 'komponen_dipakai_create') {
            $c->create(); // Menampilkan form
        } else {
            $c->store(); // Menyimpan data
        }
        break;


    // --- Endpoint API ---
    case 'api_get_log_details': // Untuk modal detail komponen
        require_once '../app/Controllers/ApiController.php'; (new ApiController($conn))->getLogDetails();
        break;
    case 'api_get_log_by_id': // BARU: Untuk mengisi form edit
        require_once '../app/Controllers/ApiController.php'; (new ApiController($conn))->getLogById();
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
        break;
}
