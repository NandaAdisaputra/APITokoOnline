<?php
include 'helper.php';
$aksi = $_POST['aksi'] ?? '';

if ($aksi == 'login') {
    $cek = ambil_data("SELECT * FROM Users WHERE username=? AND password=?", [$_POST['user'], $_POST['pass']]);
    
    if ($cek) {
        echo json_encode(["status" => "ok", "message" => "Login Berhasil", "data" => $cek[0]]);
    } else {
        echo json_encode(["status" => "gagal", "message" => "Akun tidak ditemukan", "data" => null]);
    }
} 

if ($aksi == 'register') {
    $ok = proses_sql("INSERT INTO Users VALUES (?,?,?,'Kasir')", [$_POST['user'], $_POST['pass'], $_POST['nama']]);
    echo json_encode(["status" => $ok ? "ok" : "gagal", "message" => $ok ? "Berhasil Daftar" : "Gagal Daftar", "data" => null]);
}
?>