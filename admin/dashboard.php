<?php
session_start();

// ----------------------------------------------------
// LOGIKA OTORISASI DAN LOGOUT HARUS DI ATAS SEMUA OUTPUT
// ----------------------------------------------------

// 1. Pengecekan Otorisasi Dasar: Jika sesi tidak lengkap, redirect ke login
// Perlu dipastikan $_SESSION["user_role"] juga ada
if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true || !isset($_SESSION["user_role"])) {
    header("Location: login.php");
    exit();
}

// 2. Pengecekan Otorisasi Role (Khusus Halaman Admin)
// Pastikan hanya ADMIN yang boleh mengakses halaman ini
if ($_SESSION["user_role"] !== 'admin') {
    // Jika user adalah customer, arahkan ke dashboard customer (di public)
    header("Location: ../public/dashboard.php"); // Ganti dengan halaman customer yang sesuai
    exit();
}

// 3. Logika Logout
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// --- Variabel Sesi yang Dibutuhkan untuk Dashboard ---
// Asumsi: Variabel ini telah diset saat proses login (saat mengambil data dari DB)
$nama_user = $_SESSION["user_name"] ?? 'Administrator';
$role_user = $_SESSION["user_role"] ?? 'admin';
$tanggal_gabung = $_SESSION["join_date"] ?? 'Tanggal Tidak Ditemukan'; // Asumsi ini ada di sesi
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin</title>
</head>
<body>
    <header>
        <h1>Dashboard Admin</h1>
        <form method="POST" style="display:inline;">
            <button type="submit" name="logout">Logout</button>
        </form>
    </header>

    <main>
        <h2>Selamat Datang, <?php echo htmlspecialchars($nama_user); ?>!</h2>
        <p>Anda login sebagai: <strong><?php echo strtoupper(htmlspecialchars($role_user)); ?></strong></p>
        <p>Bergabung sejak: <?php echo htmlspecialchars($tanggal_gabung); ?></p>
        
        <ul>
            <li><a href="products/index.php">Kelola Produk</a></li>
            <li><a href="orders/index.php">Kelola Pesanan</a></li>
            </ul>
    </main>
</body>
</html>