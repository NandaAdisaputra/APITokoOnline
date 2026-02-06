<?php
// ===============================
// KONFIGURASI KONEKSI SQL SERVER
// ===============================

// Nama server / komputer yang terpasang SQL Server
$server = "DESKTOP-10LBD89"; 

// Konfigurasi database:
// - Database = nama database yang dipakai
// - TrustServerCertificate = true agar koneksi lebih stabil
$db = [
    "Database" => "DB_LKS",
    "TrustServerCertificate" => true
];

// Membuat koneksi ke SQL Server
// Jika berhasil, $koneksi berisi koneksi aktif ke database
$koneksi = sqlsrv_connect($server, $db);

/* =========================================================
   FUNGSI UNTUK MENGAMBIL DATA (SELECT)
   Digunakan untuk query SELECT (menampilkan data)
   Contoh: SELECT * FROM Users
   ========================================================= */
function ambil_data($sql, $param = []) {

    // Mengambil variabel koneksi dari luar fungsi
    global $koneksi;

    // Menjalankan query SQL dengan parameter (prepared statement)
    $query = sqlsrv_query($koneksi, $sql, $param);

    // Array kosong untuk menampung hasil data
    $hasil = [];

    // Mengambil setiap baris data dari hasil query
    // SQLSRV_FETCH_ASSOC = hasil dalam bentuk array asosiatif (nama kolom => nilai)
    while ($query && $baris = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $hasil[] = $baris; // Menyimpan setiap baris ke dalam array
    }

    // Mengembalikan semua data dalam bentuk array
    return $hasil;
}

/* =========================================================
   FUNGSI UNTUK INSERT, UPDATE, DELETE
   Digunakan untuk:
   - INSERT (tambah data)
   - UPDATE (ubah data)
   - DELETE (hapus data)
   ========================================================= */
function proses_sql($sql, $param = []) {

    // Mengambil variabel koneksi dari luar fungsi
    global $koneksi;

    // Menjalankan query dan mengembalikan hasil true/false
    return sqlsrv_query($koneksi, $sql, $param);
}
?>
