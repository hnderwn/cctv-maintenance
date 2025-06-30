<?php
// app/Controllers/SearchController.php

require_once '../app/Models/SearchModel.php';

class SearchController {
    private $searchModel;

    public function __construct($dbConnection) {
        $this->searchModel = new SearchModel($dbConnection);
    }

    public function index() {
        // Ambil kata kunci dari URL
        $query = isset($_GET['q']) ? $_GET['q'] : '';
        $results = [];

        // Hanya lakukan pencarian jika query tidak kosong
        if (!empty($query)) {
            $results = $this->searchModel->searchGlobal($query);
        }
        
        $pageTitle = "Hasil Pencarian untuk '" . htmlspecialchars($query) . "'";
        
        // Panggil view untuk menampilkan hasilnya
        require_once 'views/hasil_pencarian.php';
    }
}