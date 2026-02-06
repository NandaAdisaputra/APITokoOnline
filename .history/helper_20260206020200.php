<?php
// Ganti LOCALHOST menjadi nama PC kamu
$host = "DESKTOP-10LBD89"; 
// Sesuaikan dengan Windows Authentication
$konf = [
    "Database" => "DB_LKS",
    "TrustServerCertificate" => true // Penting untuk menghindari error SSL
];
// UID dan PWD dikosongkan karena menggunakan kredensial Windows
$conn = sqlsrv_connect($host, $konf);

if (!$conn) {
    die(json_encode(["status" => "error", "msg" => "Koneksi Gagal!"]));
}

// Fungsi Ambil Data (SELECT)
function ambil($sql, $p = []) {
    global $conn;
    $s = sqlsrv_query($conn, $sql, $p);
    $d = [];
    while ($r = sqlsrv_fetch_array($s, SQLSRV_FETCH_ASSOC)) { $d[] = $r; }
    return $d;
}

// Fungsi Eksekusi (INSERT, UPDATE, DELETE)
function eksekusi($sql, $p = []) {
    global $conn;
    return sqlsrv_query($conn, $sql, $p);
}
?>