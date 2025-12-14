<?php
session_start();
header('Content-Type: application/json');

require_once '../../config/db_connect.php';

// Cek Login Mitra
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mitra') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
    exit;
}

// Validasi input
if (!isset($_POST['nama_layanan']) || empty(trim($_POST['nama_layanan']))) {
    echo json_encode(['success' => false, 'message' => 'Nama layanan harus diisi']);
    exit;
}

if (!isset($_POST['kategori']) || empty(trim($_POST['kategori']))) {
    echo json_encode(['success' => false, 'message' => 'Kategori harus dipilih']);
    exit;
}

$id_rs = $_SESSION['id_rs'];
$id_rs_akun = $_SESSION['id_rs_akun'] ?? null;
$nama_layanan = mysqli_real_escape_string($conn, trim($_POST['nama_layanan']));
$kategori = mysqli_real_escape_string($conn, trim($_POST['kategori']));
$ketersediaan = isset($_POST['ketersediaan']) ? mysqli_real_escape_string($conn, $_POST['ketersediaan']) : 'Tersedia';

// Cek apakah nama layanan sudah ada untuk RS ini
$check_query = mysqli_query($conn, "
    SELECT id_layanan 
    FROM data_layanan_rs 
    WHERE id_rs = '$id_rs' 
    AND nama_layanan = '$nama_layanan'
");

if (mysqli_num_rows($check_query) > 0) {
    echo json_encode(['success' => false, 'message' => 'Layanan dengan nama tersebut sudah ada']);
    exit;
}

// Insert layanan baru
$query = "INSERT INTO data_layanan_rs 
          (id_rs, nama_layanan, kategori, ketersediaan_layanan, id_rs_akun, create_by) 
          VALUES 
          ('$id_rs', '$nama_layanan', '$kategori', '$ketersediaan', " . 
          ($id_rs_akun ? "'$id_rs_akun'" : "NULL") . ", 
          " . ($id_rs_akun ? "'$id_rs_akun'" : "NULL") . ")";

if (mysqli_query($conn, $query)) {
    $id_layanan_baru = mysqli_insert_id($conn);
    echo json_encode([
        'success' => true, 
        'message' => 'Layanan berhasil ditambahkan',
        'id_layanan' => $id_layanan_baru
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Gagal menambahkan layanan: ' . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>
