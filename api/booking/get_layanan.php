<?php
/**
 * API Endpoint: Get Layanan by RS ID
 * 
 * Mengambil daftar layanan berdasarkan ID Rumah Sakit
 * Digunakan untuk populate dropdown "Jenis Layanan" di booking.php
 * 
 * Method: GET
 * Parameter: id_rs (required)
 * Return: JSON array of layanan objects
 */

header('Content-Type: application/json');
require_once '../../config/db_connect.php';

// Cek apakah parameter id_rs ada
if (!isset($_GET['id_rs']) || empty($_GET['id_rs'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Parameter id_rs diperlukan',
        'data' => []
    ]);
    exit;
}

// Ambil dan sanitasi parameter
$id_rs = mysqli_real_escape_string($conn, $_GET['id_rs']);

// Query untuk mengambil layanan berdasarkan ID RS
$query = "SELECT id_layanan, nama_layanan, kategori, ketersediaan_layanan 
          FROM data_layanan_rs 
          WHERE id_rs = '$id_rs' 
          AND ketersediaan_layanan = 'Tersedia'
          ORDER BY kategori ASC, nama_layanan ASC";

$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Query error: ' . mysqli_error($conn),
        'data' => []
    ]);
    exit;
}

// Kumpulkan data ke array
$layanan = [];
while ($row = mysqli_fetch_assoc($result)) {
    $layanan[] = [
        'id_layanan' => $row['id_layanan'],
        'nama_layanan' => $row['nama_layanan'],
        'kategori' => $row['kategori'],
        'ketersediaan_layanan' => $row['ketersediaan_layanan']
    ];
}

// Return data sebagai JSON
echo json_encode($layanan);

// Close connection
mysqli_close($conn);
?>
