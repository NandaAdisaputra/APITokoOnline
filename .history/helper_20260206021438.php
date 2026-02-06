<?php
$host = "DESKTOP-10LBD89"; 
$konf = [
    "Database" => "DB_LKS",
    "TrustServerCertificate" => true
];

$conn = sqlsrv_connect($host, $konf);

if (!$conn) {
    header('Content-Type: application/json');
    die(json_encode([
        "status" => "error",
        "message" => "Koneksi ke database gagal!",
        "data" => null
    ]));
}

function ambil($sql, $p = []) {
    global $conn;
    $s = sqlsrv_query($conn, $sql, $p);
    $d = [];
    if ($s === false) return false; // Berikan sinyal jika query gagal
    while ($r = sqlsrv_fetch_array($s, SQLSRV_FETCH_ASSOC)) { $d[] = $r; }
    return $d;
}

function eksekusi($sql, $p = []) {
    global $conn;
    return sqlsrv_query($conn, $sql, $p);
}
?>