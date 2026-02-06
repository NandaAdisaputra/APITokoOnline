<?php
include 'helper.php';
$aksi = $_POST['aksi'] ?? '';

if ($aksi == 'login') {
    $cek = ambil_data("SELECT * FROM Users WHERE username=? AND password=?", [$_POST['user'], $_POST['pass']]);
    echo json_encode($cek ? ["status" => "ok", "data" => $cek[0]] : ["status" => "gagal"]);
} 

if ($aksi == 'register') {
    $ok = proses_sql("INSERT INTO Users VALUES (?,?,?,'Kasir')", [$_POST['user'], $_POST['pass'], $_POST['nama']]);
    echo json_encode(["status" => $ok ? "ok" : "gagal"]);
}