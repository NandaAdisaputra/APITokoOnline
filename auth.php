<?php
// Menghubungkan file helper.php yang berisi:
// 1. Koneksi database PDO ($koneksi)
// 2. Fungsi ambil_data() untuk SELECT
// 3. Fungsi proses_sql() untuk INSERT/UPDATE/DELETE
include 'helper.php';

// Ambil nilai parameter 'aksi' dari POST, jika tidak ada maka string kosong
$aksi = $_POST['aksi'] ?? '';

// =======================
// FUNGSI LOGIN
// =======================
if ($aksi == 'login') {

    // Ambil username & password dari POST, jika tidak ada, isi dengan string kosong
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';

    // Validasi: jika username atau password kosong, kirim response gagal
    if (empty($user) || empty($pass)) {
        echo json_encode([
            "status" => "gagal",
            "message" => "Username dan Password harus diisi",
            "data" => null
        ]);
        exit; // hentikan eksekusi script
    }

    // Query database untuk mengecek apakah user & password cocok
    $cek = ambil_data(
        "SELECT * FROM Users WHERE username=? AND password=?", 
        [$user, $pass]  // Parameter untuk prepared statement (menghindari SQL Injection)
    );

    // Jika data ditemukan, login berhasil
    if ($cek) {
        echo json_encode([
            "status" => "ok",
            "message" => "Login Berhasil",
            "data" => null
        ]);
    } else {
        // Jika data tidak ditemukan, kirim response gagal
        echo json_encode([
            "status" => "gagal",
            "message" => "Akun tidak ditemukan",
            "data" => null
        ]);
    }
}

// =======================
// FUNGSI REGISTER
// =======================
if ($aksi == 'register') {

    // Menjalankan query INSERT untuk menambahkan user baru
    // Kolom yang diisi: username, password, nama, role = 'Kasir'
    // $_POST['user'], $_POST['pass'], $_POST['nama'] harus ada
    $ok = proses_sql(
        "INSERT INTO Users VALUES (?,?,?,'Kasir')",
        [$_POST['user'], $_POST['pass'], $_POST['nama']]
    );

    // Mengirim respon JSON berdasarkan hasil insert
    echo json_encode([
        "status" => $ok ? "ok" : "gagal",
        "message" => $ok ? "Berhasil Daftar" : "Gagal Daftar",
        "data" => null
    ]);
}
?>
