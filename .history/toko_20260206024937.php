<?php
// Menghubungkan file helper.php yang berisi koneksi database
// serta fungsi ambil_data() dan proses_sql()
include 'helper.php';

// Mengambil metode request (GET, POST, dll)
$metode = $_SERVER['REQUEST_METHOD'];

// Mengambil parameter aksi dari GET atau POST
// Jika tidak ada, diisi string kosong
$aksi = $_REQUEST['aksi'] ?? '';

/* ======================================================
   JIKA REQUEST METHOD ADALAH GET → UNTUK PENCARIAN DATA
   ====================================================== */
if ($metode == 'GET') {

    // Mengambil kata kunci pencarian (jika ada)
    $cari = $_GET['cari'] ?? '';

    // Mengambil data produk berdasarkan nama produk (LIKE)
    $hasil = ambil_data(
        "SELECT * FROM Produk WHERE nama_produk LIKE ?",
        ["%$cari%"]
    );

    // Mengirim respon JSON ke client (misalnya Android)
    echo json_encode([
        "status" => "Ok",
        "message" => "Data Berhasil dimuat",
        "data" => $hasil
    ]);

} 
/* ======================================================
   JIKA BUKAN GET (BERARTI POST) → UNTUK TAMBAH/HAPUS/JUAL
   ====================================================== */
else {

    // Penanda apakah proses berhasil atau tidak
    $sukses = false;

    /* ----------- AKSI TAMBAH PRODUK ----------- */
    if ($aksi == 'tambah') {

        // Menambahkan produk baru ke tabel Produk
        $sukses = proses_sql(
            "INSERT INTO Produk VALUES (?,?,?,?)",
            [$_POST['kode'], $_POST['nama'], $_POST['harga'], $_POST['stok']]
        );
    }

    /* ----------- AKSI HAPUS PRODUK ----------- */
    if ($aksi == 'hapus') {

        // Menghapus produk berdasarkan kode produk
        $sukses = proses_sql(
            "DELETE FROM Produk WHERE kode_produk=?",
            [$_POST['kode']]
        );
    }

    /* ----------- AKSI PENJUALAN ----------- */
    if ($aksi == 'jual') {

        // Mengambil daftar item penjualan dari JSON
        $barang = json_decode($_POST['items'], true);

        // 1. Simpan data penjualan utama ke tabel Penjualan
        if (
            proses_sql(
                "INSERT INTO Penjualan VALUES (?, GETDATE(), ?, ?)",
                [$_POST['faktur'], $_POST['total'], $_POST['kasir']]
            )
        ) {

            // 2. Untuk setiap barang yang dijual:
            foreach ($barang as $item) {

                // Simpan detail penjualan per barang
                proses_sql(
                    "INSERT INTO Penjualan_Detail VALUES (?,?,?,?)",
                    [
                        $_POST['faktur'],
                        $item['kode'],
                        $item['jumlah'],
                        $item['subtotal']
                    ]
                );

                // Kurangi stok produk sesuai jumlah terjual
                proses_sql(
                    "UPDATE Produk 
                     SET stok = stok - ? 
                     WHERE kode_produk = ?",
                    [$item['jumlah'], $item['kode']]
                );
            }

            // Jika semua proses sukses
            $sukses = true;
        }
    }

    // Mengirim respon akhir ke client
    echo json_encode([
        "status" => $sukses ? "ok" : "gagal",
        "message" => $sukses ? "Berhasil" : "Gagal",
        "data" => null
    ]);
}
?>
