# FindeRS - Healthcare Booking System

## Instalasi dan Konfigurasi Database

Langkah ini berlaku baik untuk Windows maupun Linux.

1.  **Siapkan Database**
    - Buka browser dan akses phpMyAdmin di `http://localhost/phpmyadmin`.
    - Klik tab **Databases**.
    - Buat database baru dengan nama: `finder_rs`.
    - Klik tombol **Create**.

2.  **Impor Struktur dan Data**
    - Pilih database `finder_rs` yang baru dibuat di sidebar kiri.
    - Klik tab **Import** di menu atas.
    - Klik **Choose File** dan cari file `finder_rs.sql` yang berada di dalam folder `database/` di repositori ini.
    - Klik tombol **Go** atau **Kirim** di bagian bawah halaman.
    - Pastikan impor berhasil ditandai dengan pesan sukses berwarna hijau.

3.  **Konfigurasi Koneksi**
    - Buka file `config/db_connect.php` di text editor.
    - Pastikan konfigurasi sesuai dengan setting XAMPP Anda (default biasanya):
      ```php
      $host = "localhost";
      $user = "root";
      $pass = "";
      $db   = "finder_rs";
      ```

---

## Cara Menjalankan di Windows (XAMPP)

1.  **Persiapan Folder**
    - Salin atau pindahkan folder project `finders` ke dalam direktori instalasi XAMPP, biasanya di `C:\xampp\htdocs\`.
    - Struktur akhir harusnya: `C:\xampp\htdocs\finders\`.

2.  **Jalankan Server**
    - Buka aplikasi **XAMPP Control Panel**.
    - Klik tombol **Start** pada modul **Apache** dan **MySQL**.

3.  **Akses Aplikasi**
    - Buka browser (Chrome/Edge/Firefox).
    - Akses URL: `http://localhost/finders`.

---

## Cara Menjalankan di Linux (XAMPP / LAMPP)

1.  **Persiapan Folder**
    - Salin folder project ke direktori htdocs XAMPP (biasanya di `/opt/lampp/htdocs/`).

2.  **Jalankan Server**
    - Jalankan XAMPP melalui terminal:
      ```bash
      sudo /opt/lampp/lampp start
      ```

3.  **Akses Aplikasi**
    - Buka browser dan akses URL: `http://localhost/finders`.

---

## Akun Demo (Data Dummy)

Anda dapat menggunakan kredensial berikut untuk pengujian sistem:

**1. Akun Super Admin**
- Username: `admin@example.com`
- Password: `password`

**2. Akun Mitra Rumah Sakit (RS Fatmawati)**
- Email: `admin@fatmawati.id`
- Password: `password`

**3. Akun User (Pasien)**
- Email: `budi@example.com`
- Password: `password` (atau daftar akun baru)
