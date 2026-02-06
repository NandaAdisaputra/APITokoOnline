<?php
include 'helper.php';
$metode = $_SERVER['REQUEST_METHOD'];
$aksi = $_REQUEST['aksi'] ?? '';

if ($metode == 'GET') {
    $cari = $_GET['cari'] ?? '';
    $hasil = ambil_data("SELECT * FROM Produk WHERE nama_produk LIKE ?", ["%$cari%"]);
    echo json_encode(["status" => "ok", "message" => "Data dimuat", "data" => $hasil]);
} else {
    $sukses = false;
    if ($aksi == 'tambah') {
        $sukses = proses_sql("INSERT INTO Produk VALUES (?,?,?,?)", [$_POST['kode'], $_POST['nama'], $_POST['harga'], $_POST['stok']]);
    }
    if ($aksi == 'hapus') {
        $sukses = proses_sql("DELETE FROM Produk WHERE kode_produk=?", [$_POST['kode']]);
    }
    if ($aksi == 'jual') {
        $barang = json_decode($_POST['items'], true);
        if (proses_sql("INSERT INTO Penjualan VALUES (?, GETDATE(), ?, ?)", [$_POST['faktur'], $_POST['total'], $_POST['kasir']])) {
            foreach ($barang as $item) {
                proses_sql("INSERT INTO Penjualan_Detail VALUES (?,?,?,?)", [$_POST['faktur'], $item['kode'], $item['jumlah'], $item['subtotal']]);
                proses_sql("UPDATE Produk SET stok = stok - ? WHERE kode_produk = ?", [$item['jumlah'], $item['kode']]);
            }
            $sukses = true;
        }
    }
    echo json_encode(["status" => $sukses ? "ok" : "gagal", "message" => $sukses ? "Berhasil" : "Gagal", "data" => null]);
}
?>