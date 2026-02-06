<?php
$server = "DESKTOP-10LBD89"; 
$db     = ["Database" => "DB_LKS", "TrustServerCertificate" => true];
$koneksi = sqlsrv_connect($server, $db);

// Fungsi untuk mengambil data (SELECT)
function ambil_data($sql, $param = []) {
    global $koneksi;
    $query = sqlsrv_query($koneksi, $sql, $param);
    $hasil = [];
    while ($query && $baris = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $hasil[] = $baris;
    }
    return $hasil;
}

// Fungsi untuk perintah simpan/ubah/hapus
function proses_sql($sql, $param = []) {
    global $koneksi;
    return sqlsrv_query($koneksi, $sql, $param);
}
?>