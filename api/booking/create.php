<?php
session_start();
include '../../config/db_connect.php';

// Set header untuk JSON response
header('Content-Type: application/json');

// 1. Cek Login
if(!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Anda harus login terlebih dahulu'
    ]);
    exit;
}

// 2. Validasi Request Method
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_user = $_SESSION['user_id'];
    
    // Sanitasi Input
    $nama_pasien = mysqli_real_escape_string($conn, $_POST['nama_pasien']);
    $no_telpon = mysqli_real_escape_string($conn, $_POST['no_telpon']); // Not saved to DB, but kept for future use
    $id_rs = mysqli_real_escape_string($conn, $_POST['id_rs']);
    $id_layanan = mysqli_real_escape_string($conn, $_POST['id_layanan']);
    $tanggal_kunjungan = mysqli_real_escape_string($conn, $_POST['tanggal_kunjungan']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');
    
    // --- BARU: Tangkap Jam Mulai ---
    $jam_mulai = isset($_POST['jam_mulai']) ? mysqli_real_escape_string($conn, $_POST['jam_mulai']) : null;

    // 3. Validasi Input
    
    // Validasi Sesi Waktu
    if (!$jam_mulai) {
        echo json_encode([
            'success' => false,
            'message' => 'Harap pilih sesi jam kunjungan!'
        ]);
        exit;
    }

    // Validasi Tanggal (Tidak boleh masa lalu)
    if(strtotime($tanggal_kunjungan) < strtotime(date('Y-m-d'))) {
        echo json_encode([
            'success' => false,
            'message' => 'Tanggal kunjungan tidak boleh di masa lalu!'
        ]);
        exit;
    }
    
    // --- BARU: Hitung Jam Selesai (Durasi 2 Jam) ---
    // Menggunakan strtotime untuk menambah 2 jam (7200 detik)
    $jam_selesai = date('H:i:s', strtotime($jam_mulai) + (2 * 60 * 60));
    
    // --- GENERATE NOMOR ANTRIAN ---
    // Ambil nomor antrian terakhir untuk tanggal dan layanan yang sama
    $query_last_queue = mysqli_query($conn, 
        "SELECT queue_number FROM data_penjadwalan 
         WHERE id_layanan = '$id_layanan' 
         AND tanggal_kunjungan = '$tanggal_kunjungan' 
         AND queue_number IS NOT NULL 
         ORDER BY id_penjadwalan DESC 
         LIMIT 1"
    );
    
    $queue_number = null;
    if($queue_last = mysqli_fetch_assoc($query_last_queue)) {
        // Ekstrak nomor dari format F-XXX atau format lainnya
        $last_number = (int)substr($queue_last['queue_number'], 2); // Ambil angka setelah "F-"
        $new_number = $last_number + 1;
        $queue_number = 'F-' . str_pad($new_number, 3, '0', STR_PAD_LEFT);
    } else {
        // Jika belum ada antrian untuk tanggal dan layanan ini, mulai dari F-001
        $queue_number = 'F-001';
    }
    
    // 4. Insert ke Database
    // Menambahkan kolom jam_mulai, jam_selesai, dan queue_number
    // Menggunakan status 'Menunggu' sesuai ENUM database
    $query = "INSERT INTO data_penjadwalan 
              (id_user, id_rs, id_layanan, nama_pasien, tanggal_kunjungan, jam_mulai, jam_selesai, catatan, status, queue_number, dibuat_pada) 
              VALUES 
              ('$id_user', '$id_rs', '$id_layanan', '$nama_pasien', '$tanggal_kunjungan', '$jam_mulai', '$jam_selesai', '$catatan', 'Menunggu', '$queue_number', NOW())";
    
    if(mysqli_query($conn, $query)) {
        // Ambil nama RS dan layanan untuk response
        $rs_check = mysqli_query($conn, "SELECT nama_rs FROM data_rumah_sakit WHERE id_rs='$id_rs'");
        $layanan_check = mysqli_query($conn, "SELECT nama_layanan FROM data_layanan_rs WHERE id_layanan='$id_layanan'");
        
        $rs_name = 'Rumah Sakit';
        $lay_name = 'Layanan';
        
        if($rs_data = mysqli_fetch_assoc($rs_check)) {
            $rs_name = $rs_data['nama_rs'];
        }
        if($lay_data = mysqli_fetch_assoc($layanan_check)) {
            $lay_name = $lay_data['nama_layanan'];
        }

        echo json_encode([
            'success' => true,
            'message' => 'Booking berhasil dibuat!',
            'data' => [
                'rs_name' => $rs_name,
                'layanan_name' => $lay_name,
                'tanggal' => $tanggal_kunjungan,
                'jam_mulai' => $jam_mulai,
                'nama_pasien' => $nama_pasien,
                'queue_number' => $queue_number
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal membuat booking: ' . mysqli_error($conn)
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan'
    ]);
}

mysqli_close($conn);
exit;
?>