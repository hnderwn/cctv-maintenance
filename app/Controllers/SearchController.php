<?php

require_once '../app/Models/SearchModel.php';

class SearchController {
    private $searchModel;

    public function __construct($dbConnection) {
        $this->searchModel = new SearchModel($dbConnection);
    }

    public function index() {
        $query = isset($_GET['q']) ? $_GET['q'] : '';
        $results = [];

        if (!empty($query)) {
            $results = $this->searchModel->searchGlobal($query);
        }
        
        $pageTitle = "Hasil Pencarian untuk '" . htmlspecialchars($query) . "'";
        
        require_once 'views/hasil_pencarian.php';
    }
}