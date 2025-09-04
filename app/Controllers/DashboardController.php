<?php

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

        $stats['cctv_aktif'] = $this->dashboardModel->countActiveCctv();
        $stats['cctv_bermasalah'] = $this->dashboardModel->countProblemCctv();
        $stats['total_teknisi'] = $this->dashboardModel->countTotalTeknisi();

        require_once 'views/dashboard.php';
    }
}