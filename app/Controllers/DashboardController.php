<?php
// app/Controllers/DashboardController.php

// Panggil model baru
require_once '../app/Models/DashboardModel.php';

class DashboardController {
    private $dashboardModel;
    
    public function __construct($dbConnection) {
        $this->dashboardModel = new DashboardModel($dbConnection);
    }

    public function index() {
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: index.php?page=login");
            exit();
        }
        
        $pageTitle = "Dashboard";

        // Ambil data statistik dari model
        $stats['cctv_aktif'] = $this->dashboardModel->countActiveCctv();
        $stats['cctv_bermasalah'] = $this->dashboardModel->countProblemCctv();
        $stats['total_teknisi'] = $this->dashboardModel->countTotalTeknisi();

        // Kirim data ke view
        require_once 'views/dashboard.php';
    }
}