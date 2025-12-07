<?php
session_start();
include '../../config/db_connect.php';

// Cek Login
if(!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

// Validasi Input
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_user = $_SESSION['user_id'];
    $nama_pasien = mysqli_real_escape_string($conn, $_POST['nama_pasien']);
    $no_telpon = mysqli_real_escape_string($conn, $_POST['no_telpon']);
    $id_rs = mysqli_real_escape_string($conn, $_POST['id_rs']);
    $id_layanan = mysqli_real_escape_string($conn, $_POST['id_layanan']);
    $tanggal_kunjungan = mysqli_real_escape_string($conn, $_POST['tanggal_kunjungan']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');
    
    // Validasi tanggal tidak boleh di masa lalu
    if(strtotime($tanggal_kunjungan) < strtotime(date('Y-m-d'))) {
        $_SESSION['error_message'] = "Tanggal kunjungan tidak boleh di masa lalu!";
        header("Location: ../../booking.php?rs_id=" . $id_rs);
        exit;
    }
    
    // Insert ke database
    $query = "INSERT INTO data_penjadwalan 
              (id_user, id_rs, id_layanan, nama_pasien, no_telpon, tanggal_kunjungan, catatan, status_jadwal, created_at) 
              VALUES 
              ('$id_user', '$id_rs', '$id_layanan', '$nama_pasien', '$no_telpon', '$tanggal_kunjungan', '$catatan', 'pending', NOW())";
    
    if(mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = "Booking kunjungan berhasil! Silakan tunggu konfirmasi dari rumah sakit.";
        header("Location: ../../index.php#history");
    } else {
        $_SESSION['error_message'] = "Gagal membuat booking: " . mysqli_error($conn);
        header("Location: ../../booking.php?rs_id=" . $id_rs);
    }
    
} else {
    header("Location: ../../booking.php");
}

mysqli_close($conn);
exit;
?>
