<?php

class SearchModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function searchGlobal($query) {
        $searchQuery = '%' . $this->conn->real_escape_string($query) . '%';
        $results = [
            'teknisi' => [],
            'cctv_unit' => [],
            'komponen' => [],
            'cctv_tipe' => []
        ];

        $stmt_teknisi = $this->conn->prepare("SELECT id_teknisi, nama_teknisi, kontak FROM teknisi WHERE nama_teknisi LIKE ? OR kontak LIKE ?");
        $stmt_teknisi->bind_param("ss", $searchQuery, $searchQuery);
        $stmt_teknisi->execute();
        $results['teknisi'] = $stmt_teknisi->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_teknisi->close();

        $stmt_cctv = $this->conn->prepare("SELECT id_cctv, lokasi, status FROM cctv WHERE id_cctv LIKE ? OR lokasi LIKE ? OR status LIKE ?");
        $stmt_cctv->bind_param("sss", $searchQuery, $searchQuery, $searchQuery);
        $stmt_cctv->execute();
        $results['cctv_unit'] = $stmt_cctv->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_cctv->close();

        $stmt_komponen = $this->conn->prepare("SELECT id_komponen, nama_komponen, stok FROM komponen WHERE nama_komponen LIKE ? OR id_komponen LIKE ?");
        $stmt_komponen->bind_param("ss", $searchQuery, $searchQuery);
        $stmt_komponen->execute();
        $results['komponen'] = $stmt_komponen->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_komponen->close();
        
        $stmt_tipe = $this->conn->prepare("SELECT id_model, nama_model, manufaktur FROM cctv_models WHERE nama_model LIKE ? OR manufaktur LIKE ?");
        $stmt_tipe->bind_param("ss", $searchQuery, $searchQuery);
        $stmt_tipe->execute();
        $results['cctv_tipe'] = $stmt_tipe->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_tipe->close();
        
        return $results;
    }
}