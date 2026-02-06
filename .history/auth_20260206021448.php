<?php
include 'helper.php';
header('Content-Type: application/json');

$aksi = $_POST['aksi'] ?? '';

if ($aksi == 'login') {
    $res = ambil("SELECT * FROM Users WHERE username=? AND password=?", [$_POST['u'], $_POST['p']]);
    
    if ($res && count($res) > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Login berhasil",
            "data" => $res[0]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Username atau password salah",
            "data" => null
        ]);
    }
} 

else if ($aksi == 'register') {
    $ok = eksekusi("INSERT INTO Users VALUES (?,?,?,'Kasir')", [$_POST['u'], $_POST['p'], $_POST['n']]);
    
    echo json_encode([
        "status" => $ok ? "success" : "error",
        "message" => $ok ? "Registrasi user baru berhasil" : "Gagal mendaftarkan user",
        "data" => null
    ]);
}
?>