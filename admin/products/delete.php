<?php
session_start();
include '../../config/database.php'; 

// Pengecekan Otorisasi Admin
if (!isset($_SESSION["is_login"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: ../../public/login.php");
    exit();
}
if (!function_exists('connect_db')) {
    die("Error: Fungsi koneksi database (connect_db) tidak ditemukan.");
}

// Cek apakah ada ID produk yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$conn = connect_db();
$product_id = (int)$_GET['id'];

// --- Logika Hapus ---

// 1. Ambil nama file gambar (jika ada, untuk dihapus dari server)
$sql_get_image = "SELECT image FROM products WHERE id = $product_id";
$result_image = mysqli_query($conn, $sql_get_image);
$product = mysqli_fetch_assoc($result_image);
$image_to_delete = $product['image'] ?? null;


// 2. Query DELETE
// Menggunakan mysqli_real_escape_string tidak diperlukan di sini karena $product_id sudah di-cast ke (int)
$sql_delete = "DELETE FROM products WHERE id = $product_id";

if (mysqli_query($conn, $sql_delete)) {
    
    // 3. Hapus file gambar dari folder server (Jika ada)
    // Pastikan path ke folder 'uploads' benar, misalnya: ../../uploads/
    if ($image_to_delete && file_exists('../../uploads/' . $image_to_delete)) {
        unlink('../../uploads/' . $image_to_delete);
    }
    
    // Sukses: Redirect ke halaman index
    mysqli_close($conn);
    header("Location: index.php?status=success_delete");
    exit();
} else {
    // Gagal: Redirect dengan pesan error
    $_SESSION['status_message'] = "Gagal menghapus produk: " . mysqli_error($conn);
    $_SESSION['status_type'] = 'danger';
    mysqli_close($conn);
    header("Location: index.php");
    exit();
}
?>