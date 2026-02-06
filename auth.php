<?php
include 'helper.php';
header('Content-Type: application/json');

$aksi = $_POST['aksi'] ?? '';

if ($aksi == 'login') {
    $cek = ambil_data(
        "SELECT * FROM Users WHERE username=? AND password=?",
        [$_POST['user'], $_POST['pass']]
    );

    http_response_code($cek ? 200 : 404);
    echo json_encode([
        "status" => $cek ? 200 : 404,
        "message" => $cek ? "Login Berhasil" : "Akun tidak ditemukan",
        "data" => $cek ? $cek[0] : null
    ]);
}

if ($aksi == 'register') {
    $ok = proses_sql(
        "INSERT INTO Users VALUES (?,?,?,'Kasir')",
        [$_POST['user'], $_POST['pass'], $_POST['nama']]
    );

    http_response_code($ok ? 201 : 404);
    echo json_encode([
        "status" => $ok ? 201 : 404,
        "message" => $ok ? "Berhasil Daftar" : "Gagal Daftar",
        "data" => null
    ]);
}
?>

