<?php
// Konfigurasi Database (didefinisikan di Global Scope)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "online_shop"; // Pastikan nama database ini sudah Anda buat!

/**
 * Fungsi untuk membuat dan mengembalikan objek koneksi MySQLi.
 * @return mysqli|false Objek koneksi mysqli atau false jika gagal.
 */
function connect_db() {
    // Mengakses variabel koneksi yang didefinisikan di luar fungsi
    global $host, $user, $pass, $db; 
    
    // Membuat koneksi berdasarkan variabel global
    $conn = mysqli_connect($host, $user, $pass, $db);

    // Cek koneksi dan hentikan skrip jika gagal
    if (!$conn) {
        // Hentikan eksekusi dan tampilkan error
        die("Koneksi gagal: " . mysqli_connect_error());
    }
    
    // Mengembalikan objek koneksi yang berhasil
    return $conn;
}

// Catatan: Variabel $conn TIDAK dibuat di sini. 
// Variabel $conn hanya dibuat di file lain saat mereka memanggil connect_db().
?>