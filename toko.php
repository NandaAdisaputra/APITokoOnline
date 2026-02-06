<?php
include 'helper.php';

$metode = $_SERVER['REQUEST_METHOD'];
$aksi = $_REQUEST['aksi'] ?? '';

// Default: Jika tidak ada aksi yang cocok
$res = ["status" => "404", "message" => "Aksi tidak ditemukan", "data" => null];

if ($metode == 'GET') {
    $hasil = ambil_data("SELECT * FROM Produk WHERE nama_produk LIKE ?", ["%".($_GET['cari'] ?? '')."%"]);
    $res = $hasil ? ["status" => "200", "message" => "Data ditemukan", "data" => $hasil] 
                  : ["status" => "404", "message" => "Data kosong", "data" => []];
} 
else {
    if ($aksi == 'tambah') {
        $ok = proses_sql("INSERT INTO Produk VALUES (?,?,?,?)", [$_POST['kode'], $_POST['nama'], $_POST['harga'], $_POST['stok']]);
        $res = $ok ? ["status" => "201", "message" => "Produk ditambah"] : ["status" => "400", "message" => "Gagal tambah"];
    }

    if ($aksi == 'hapus') {
        $ok = proses_sql("DELETE FROM Produk WHERE kode_produk=?", [$_POST['kode']]);
        $res = $ok ? ["status" => "200", "message" => "Produk dihapus"] : ["status" => "404", "message" => "Kode tidak ada"];
    }

    if ($aksi == 'jual') {
        $barang = json_decode($_POST['items'], true);
        $jual = proses_sql("INSERT INTO Penjualan VALUES (?, GETDATE(), ?, ?)", [$_POST['faktur'], $_POST['total'], $_POST['kasir']]);
        
        if ($jual) {
            foreach ($barang as $i) {
                proses_sql("INSERT INTO Penjualan_Detail VALUES (?,?,?,?)", [$_POST['faktur'], $i['kode'], $i['jumlah'], $i['subtotal']]);
                proses_sql("UPDATE Produk SET stok = stok - ? WHERE kode_produk = ?", [$i['jumlah'], $i['kode']]);
            }
            $res = ["status" => "201", "message" => "Transaksi berhasil"];
        } else {
            $res = ["status" => "500", "message" => "Gagal simpan transaksi"];
        }
    }
}

echo json_encode($res);
?>