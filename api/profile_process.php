<?php
session_start();
include '../config/db_connect.php';

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Ambil data dari form
$id_user = $_SESSION['user_id'];
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$no_telpon = mysqli_real_escape_string($conn, $_POST['no_telpon']);
$password_lama = $_POST['password_lama'];
$password_baru = $_POST['password_baru'] ?? '';

// Validasi: Cek password lama
$query = mysqli_query($conn, "SELECT password FROM akun_user WHERE id_user = '$id_user'");
$user = mysqli_fetch_array($query);

if(!password_verify($password_lama, $user['password'])) {
    $_SESSION['msg_type'] = 'error';
    $_SESSION['msg_content'] = 'Password lama yang Anda masukkan salah!';
    header("Location: ../profile.php");
    exit;
}

// Cek apakah email sudah digunakan user lain
$check_email = mysqli_query($conn, "SELECT id_user FROM akun_user WHERE email = '$email' AND id_user != '$id_user'");
if(mysqli_num_rows($check_email) > 0) {
    $_SESSION['msg_type'] = 'error';
    $_SESSION['msg_content'] = 'Email sudah digunakan oleh user lain!';
    header("Location: ../profile.php");
    exit;
}

// Update data user
$update_query = "UPDATE akun_user SET 
                 nama = '$nama',
                 email = '$email',
                 no_telpon = '$no_telpon'";

// Jika password baru diisi, update juga password
if(!empty($password_baru)) {
    $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
    $update_query .= ", password = '$password_hash'";
}

$update_query .= " WHERE id_user = '$id_user'";

if(mysqli_query($conn, $update_query)) {
    $_SESSION['msg_type'] = 'success';
    $_SESSION['msg_content'] = 'Profil berhasil diperbarui!';
    
    // Update session nama jika nama berubah
    $_SESSION['nama'] = $nama;
} else {
    $_SESSION['msg_type'] = 'error';
    $_SESSION['msg_content'] = 'Terjadi kesalahan saat memperbarui profil!';
}

header("Location: ../profile.php");
exit;
?>
