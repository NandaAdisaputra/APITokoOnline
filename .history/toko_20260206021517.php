<?php
include 'helper.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$aksi = $_REQUEST['aksi'] ?? '';

if ($method == 'GET') {
    $q = $_GET['q'] ?? '';
    $data = ambil("SELECT * FROM Produk WHERE nama_produk LIKE ?", ["%$q%"]);
    
    echo json_encode([
        "status" => "success",
        "message" => count($data) > 0 ? "Data produk berhasil dimuat" : "Produk tidak ditemukan",
        "data" => $data
    ]);
} 

else {
    $ok = false;
    $msg = "Aksi tidak dikenal";

    if ($aksi == 'tambah') {
        $ok = eksekusi("INSERT INTO Produk VALUES (?,?,?,?)", [$_POST['k'], $_POST['n'], $_POST['h'], $_POST['s']]);
        $msg = $ok ? "Produk berhasil ditambahkan" : "Gagal menambah produk";
    }
    else if ($aksi == 'hapus') {
        $ok = eksekusi("DELETE FROM Produk WHERE kode_produk=?", [$_POST['k']]);
        $msg = $ok ? "Produk berhasil dihapus" : "Gagal menghapus produk";
    }
    else if ($aksi == 'jual') {
        $items = json_decode($_POST['items'], true);
        
        // Simpan Header
        $h = eksekusi("INSERT INTO Penjualan VALUES (?, GETDATE(), ?, ?)", [$_POST['f'], $_POST['t'], $_POST['kasir']]);
        
        if ($h) {
            foreach ($items as $it) {
                eksekusi("INSERT INTO Penjualan_Detail VALUES (?,?,?,?)", [$_POST['f'], $it['k'], $it['q'], $it['s']]);
                eksekusi("UPDATE Produk SET stok = stok - ? WHERE kode_produk = ?", [$it['q'], $it['k']]);
            }
            $ok = true;
            $msg = "Transaksi penjualan berhasil disimpan";
        } else {
            $msg = "Gagal memproses header penjualan";
        }
    }

    echo json_encode([
        "status" => $ok ? "success" : "error",
        "message" => $msg,
        "data" => null
    ]);
}
?>