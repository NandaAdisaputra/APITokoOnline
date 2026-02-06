<?php
include 'helper.php';
header('Content-Type: application/json');

$aksi = $_POST['aksi'] ?? '';

if ($aksi == 'login') {
    // Ambil data user berdasarkan u (user) dan p (pass)
    $res = ambil("SELECT * FROM Users WHERE username=? AND password=?", [$_POST['u'], $_POST['p']]);
    echo json_encode($res ? ["status" => "ok", "user" => $res[0]] : ["status" => "gagal"]);
} 

else if ($aksi == 'register') {
    // Tambah user baru
    $ok = eksekusi("INSERT INTO Users VALUES (?,?,?,'Kasir')", [$_POST['u'], $_POST['p'], $_POST['n']]);
    echo json_encode(["status" => $ok ? "ok" : "gagal"]);
}
?>