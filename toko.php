<?php 
// Nonaktifkan semua error reporting (tidak disarankan di produksi, tapi bisa untuk menutupi warning)
error_reporting(0);

// Include file helper.php yang berisi fungsi ambil_data() dan proses_sql()
include 'helper.php';

// Set header response menjadi JSON agar client tahu formatnya
header('Content-Type: application/json');  

// Ambil metode HTTP yang dipakai (GET, POST, dll)
$metode = $_SERVER['REQUEST_METHOD'];

// Ambil parameter 'aksi' dari GET/POST (REQUEST membaca keduanya)
$aksi   = $_REQUEST['aksi'] ?? '';  

// Ambil data produk dari REQUEST (POST/GET)
$kode   = $_REQUEST['kode_produk'] ?? '';  
$nama   = $_REQUEST['nama_produk'] ?? '';  
$harga  = $_REQUEST['harga'] ?? 0;  
$stok   = $_REQUEST['stok'] ?? 0;  

// Jika metode HTTP adalah GET (biasanya untuk menampilkan data)
if ($metode == 'GET') {
    // Ambil parameter 'cari' untuk pencarian nama produk
    $cari = $_GET['cari'] ?? '';  

    // Query database untuk produk yang namanya mirip dengan keyword
    $hasil = ambil_data("SELECT * FROM Produk WHERE nama_produk LIKE ?", ["%$cari%"]);

    // Kirim response JSON berisi status, pesan, dan data produk
    echo json_encode([
        "status" => "200", 
        "message" => "Data Produk", 
        "data" => $hasil
    ]);
}  

// Jika metode HTTP adalah POST (biasanya untuk tambah/edit/hapus)
elseif ($metode == 'POST') {

    // Tambah produk baru
    if ($aksi == 'tambah') {
        $ok = proses_sql(
            "INSERT INTO Produk (kode_produk, nama_produk, harga, stok) VALUES (?,?,?,?)", 
            [$kode, $nama, $harga, $stok]
        );
        echo json_encode([
            "status" => $ok ? "201" : "400", 
            "message" => $ok ? "Berhasil Tambah" : "Gagal ke DB"
        ]);
    }  

    // Edit/update produk
    elseif ($aksi == 'edit') {
        $ok = proses_sql(
            "UPDATE Produk SET nama_produk=?, harga=?, stok=? WHERE kode_produk=?", 
            [$nama, $harga, $stok, $kode]
        );
        echo json_encode([
            "status" => $ok ? "200" : "400", 
            "message" => $ok ? "Berhasil Update" : "Gagal Update"
        ]);
    }  

    // Hapus produk
    elseif ($aksi == 'hapus') {
        $ok = proses_sql(
            "DELETE FROM Produk WHERE kode_produk=?", 
            [$kode]
        );
        echo json_encode([
            "status" => $ok ? "200" : "400", 
            "message" => $ok ? "Berhasil Hapus" : "Gagal Hapus"
        ]);
    }  

    // Jika aksi tidak dikenal, beri respon error
    else {
        echo json_encode([
            "status" => "400", 
            "message" => "Aksi '$aksi' tidak dikenali"
        ]);
    }
} 
?>
