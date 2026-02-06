<?php
include 'helper.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$aksi = $_REQUEST['aksi'] ?? '';

// --- 1. SEARCH & VIEW (GET) ---
if ($method == 'GET') {
    $q = $_GET['q'] ?? ''; // Keyword pencarian
    $data = ambil("SELECT * FROM Produk WHERE nama_produk LIKE ?", ["%$q%"]);
    echo json_encode($data);
} 

// --- 2. CRUD & TRANSAKSI (POST) ---
else {
    $ok = false;
    if ($aksi == 'tambah') {
        $ok = eksekusi("INSERT INTO Produk VALUES (?,?,?,?)", [$_POST['k'], $_POST['n'], $_POST['h'], $_POST['s']]);
    }
    else if ($aksi == 'hapus') {
        $ok = eksekusi("DELETE FROM Produk WHERE kode_produk=?", [$_POST['k']]);
    }
    else if ($aksi == 'jual') {
        // Logika Transaksi Header-Detail (Paling Kompleks)
        $items = json_decode($_POST['items'], true);
        
        // Simpan Header (Nota)
        eksekusi("INSERT INTO Penjualan VALUES (?, GETDATE(), ?, ?)", [$_POST['f'], $_POST['t'], $_POST['kasir']]);
        
        // Simpan Detail (Barang) & Potong Stok
        foreach ($items as $it) {
            eksekusi("INSERT INTO Penjualan_Detail VALUES (?,?,?,?)", [$_POST['f'], $it['k'], $it['q'], $it['s']]);
            eksekusi("UPDATE Produk SET stok = stok - ? WHERE kode_produk = ?", [$it['q'], $it['k']]);
        }
        $ok = true;
    }
    echo json_encode(["status" => $ok ? "ok" : "gagal"]);
}
?>