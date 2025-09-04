<?php

class LogKomponenDipakaiModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function create($data) {
        mysqli_begin_transaction($this->conn);
        try {
            $sql_get_komponen = "SELECT stok, harga_satuan FROM komponen WHERE id_komponen = ? FOR UPDATE";
            $stmt_get = mysqli_prepare($this->conn, $sql_get_komponen);
            mysqli_stmt_bind_param($stmt_get, "s", $data['id_komponen']);
            mysqli_stmt_execute($stmt_get);
            $result_komponen = mysqli_stmt_get_result($stmt_get);
            $komponen = mysqli_fetch_assoc($result_komponen);
            if (!$komponen || $komponen['stok'] < $data['jumlah_dipakai']) {
                mysqli_rollback($this->conn);
                return ['success' => false, 'message' => 'Gagal! Stok komponen tidak mencukupi atau komponen tidak ditemukan.'];
            }
            $biaya = $komponen['harga_satuan'] * $data['jumlah_dipakai'];
            $sql_insert = "INSERT INTO log_komponen_dipakai (id_log_maintenance, id_log_kerusakan, id_komponen, jumlah_dipakai, biaya) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($this->conn, $sql_insert);
            mysqli_stmt_bind_param($stmt_insert, "iisid", $data['id_log_maintenance'], $data['id_log_kerusakan'], $data['id_komponen'], $data['jumlah_dipakai'], $biaya);
            mysqli_stmt_execute($stmt_insert);
            $sql_update_stok = "UPDATE komponen SET stok = stok - ? WHERE id_komponen = ?";
            $stmt_update_stok = mysqli_prepare($this->conn, $sql_update_stok);
            mysqli_stmt_bind_param($stmt_update_stok, "is", $data['jumlah_dipakai'], $data['id_komponen']);
            mysqli_stmt_execute($stmt_update_stok);
            mysqli_commit($this->conn);
            return ['success' => true, 'message' => 'Pemakaian komponen berhasil dicatat!'];
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($this->conn);
            return ['success' => false, 'message' => 'Terjadi kesalahan database: ' . $exception->getMessage()];
        }
    }

    public function getAllUsedComponents() {
        $sql = "SELECT
                    lkd.id,
                    lkd.jumlah_dipakai,
                    lkd.biaya,
                    k.id_komponen, k.nama_komponen, k.satuan, k.harga_satuan,
                    COALESCE(lm.id_log, lkr.id_log) AS id_laporan_referensi,
                    COALESCE(lm.tanggal, lkr.tanggal) AS tanggal_laporan,
                    COALESCE(lm.jam, lkr.jam) AS jam_laporan,
                    COALESCE(lm.deskripsi_log, lkr.deskripsi_kerusakan) AS deskripsi_laporan,
                    c.id_cctv, c.lokasi AS lokasi_cctv,
                    t.id_teknisi,
                    CASE
                        WHEN lkd.id_log_maintenance IS NOT NULL THEN 'Maintenance'
                        WHEN lkd.id_log_kerusakan IS NOT NULL THEN 'Kerusakan'
                        ELSE 'N/A'
                    END AS jenis_laporan
                FROM
                    log_komponen_dipakai lkd
                JOIN
                    komponen k ON lkd.id_komponen = k.id_komponen
                LEFT JOIN
                    log_maintenance lm ON lkd.id_log_maintenance = lm.id_log
                LEFT JOIN
                    log_kerusakan lkr ON lkd.id_log_kerusakan = lkr.id_log
                LEFT JOIN
                    cctv c ON c.id_cctv = COALESCE(lm.id_cctv, lkr.id_cctv)
                LEFT JOIN
                    teknisi t ON t.id_teknisi = COALESCE(lm.id_teknisi, lkr.id_teknisi)
                ORDER BY
                    tanggal_laporan DESC, jam_laporan DESC, lkd.id DESC";
        
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM log_komponen_dipakai WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function update($id, $data) {
        mysqli_begin_transaction($this->conn);
        try {
            $old_log_sql = "SELECT jumlah_dipakai, id_komponen FROM log_komponen_dipakai WHERE id = ? FOR UPDATE";
            $stmt_old = mysqli_prepare($this->conn, $old_log_sql);
            mysqli_stmt_bind_param($stmt_old, "i", $id);
            mysqli_stmt_execute($stmt_old);
            $old_log = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_old));
            
            $komponen_sql = "SELECT harga_satuan, stok FROM komponen WHERE id_komponen = ? FOR UPDATE";
            $stmt_komp = mysqli_prepare($this->conn, $komponen_sql);
            mysqli_stmt_bind_param($stmt_komp, "s", $data['id_komponen']);
            mysqli_stmt_execute($stmt_komp);
            $komponen = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_komp));

            $selisih_stok = $data['jumlah_dipakai'] - $old_log['jumlah_dipakai'];

            if ($komponen['stok'] < $selisih_stok) {
                mysqli_rollback($this->conn);
                return ['success' => false, 'message' => 'Gagal update! Stok komponen tidak mencukupi untuk perubahan ini.'];
            }
            
            $update_stok_sql = "UPDATE komponen SET stok = stok - ? WHERE id_komponen = ?";
            $stmt_update_stok = mysqli_prepare($this->conn, $update_stok_sql);
            mysqli_stmt_bind_param($stmt_update_stok, "is", $selisih_stok, $data['id_komponen']);
            mysqli_stmt_execute($stmt_update_stok);

            $biaya_baru = $komponen['harga_satuan'] * $data['jumlah_dipakai'];
            $update_log_sql = "UPDATE log_komponen_dipakai SET id_log_maintenance = ?, id_log_kerusakan = ?, id_komponen = ?, jumlah_dipakai = ?, biaya = ? WHERE id = ?";
            $stmt_update_log = mysqli_prepare($this->conn, $update_log_sql);
            mysqli_stmt_bind_param($stmt_update_log, "iisidi", $data['id_log_maintenance'], $data['id_log_kerusakan'], $data['id_komponen'], $data['jumlah_dipakai'], $biaya_baru, $id);
            mysqli_stmt_execute($stmt_update_log);

            mysqli_commit($this->conn);
            return ['success' => true, 'message' => 'Data pemakaian komponen berhasil diperbarui!'];
        } catch (mysqli_sql_exception $e) {
            mysqli_rollback($this->conn);
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
    
    public function delete($id) {
        mysqli_begin_transaction($this->conn);
        try {
            $sql_get_log = "SELECT id_komponen, jumlah_dipakai FROM log_komponen_dipakai WHERE id = ? FOR UPDATE";
            $stmt_get = mysqli_prepare($this->conn, $sql_get_log);
            mysqli_stmt_bind_param($stmt_get, "i", $id);
            mysqli_stmt_execute($stmt_get);
            $log = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_get));
            if (!$log) { mysqli_rollback($this->conn); return false; }
            $sql_update_stok = "UPDATE komponen SET stok = stok + ? WHERE id_komponen = ?";
            $stmt_update_stok = mysqli_prepare($this->conn, $sql_update_stok);
            mysqli_stmt_bind_param($stmt_update_stok, "is", $log['jumlah_dipakai'], $log['id_komponen']);
            mysqli_stmt_execute($stmt_update_stok);
            $sql_delete = "DELETE FROM log_komponen_dipakai WHERE id = ?";
            $stmt_delete = mysqli_prepare($this->conn, $sql_delete);
            mysqli_stmt_bind_param($stmt_delete, "i", $id);
            mysqli_stmt_execute($stmt_delete);
            mysqli_commit($this->conn);
            return true;
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($this->conn);
            return false;
        }
    }
    
    public function getForDetailModal($log_id, $log_type) {
        $col = ($log_type === 'maintenance') ? 'id_log_maintenance' : 'id_log_kerusakan';
        $sql = "SELECT k.nama_komponen, lkd.jumlah_dipakai, lkd.biaya FROM log_komponen_dipakai lkd JOIN komponen k ON lkd.id_komponen = k.id_komponen WHERE lkd.{$col} = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $log_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}