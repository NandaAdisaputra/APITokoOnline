<?php
// Menghubungkan file helper.php yang berisi fungsi koneksi database,
// serta fungsi ambil_data() dan proses_sql()
include 'helper.php';

// Mengambil nilai parameter 'aksi' dari POST.
// Jika tidak ada, maka diisi string kosong.
$aksi = $_POST['aksi'] ?? '';

/* =======================
   FUNGSI LOGIN
   ======================= */
if ($aksi == 'login') {

    // Memanggil fungsi ambil_data() untuk mengecek username & password di database
    // Menggunakan prepared statement (tanda ? diganti dengan nilai dari POST)
    $cek = ambil_data(
        "SELECT * FROM Users WHERE username=? AND password=?", 
        [$_POST['user'], $_POST['pass']]
    );

    // Jika data ditemukan (hasil tidak kosong)
    if ($cek) {
        // Mengirim respon JSON berhasil login
        echo json_encode([
            "status" => "ok",
            "message" => "Login Berhasil",
            "data" => $cek[0]   // Mengambil data user pertama
        ]);
    } else {
        // Jika username/password salah atau tidak ada di database
        echo json_encode([
            "status" => "gagal",
            "message" => "Akun tidak ditemukan",
            "data" => null
        ]);
    }
}

/* =======================
   FUNGSI REGISTER
   ======================= */
if ($aksi == 'register') {

    // Menjalankan query INSERT untuk menambahkan user baru
    // Kolom yang diisi: username, password, nama, role = 'Kasir'
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
