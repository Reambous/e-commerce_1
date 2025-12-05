<?php
// 1. --- Pemuatan Konfigurasi dan Otorisasi ---
session_start();
// Pastikan path ke database.php benar, disesuaikan dengan struktur Anda (misalnya /team/config/database.php)
include '../../config/database.php'; 

// Pengecekan Otorisasi Admin (Wajib di semua halaman Admin)
if (!isset($_SESSION["is_login"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: ../../public/login.php");
    exit();
}

// Gunakan fungsi koneksi database (sesuai diskusi kita)
if (!function_exists('connect_db')) {
    die("Error: Fungsi koneksi database (connect_db) tidak ditemukan.");
}

$conn = connect_db();
$products = [];
$status_message = ''; // Untuk notifikasi setelah CRUD

// 2. --- Logika Ambil Data Produk (Read) ---

// Query dengan JOIN untuk mengambil nama kategori
$sql = "SELECT p.id, p.name AS product_name, p.price, p.stock, c.name AS category_name, p.image 
        FROM products p 
        JOIN categories c ON p.category_id = c.id
        ORDER BY p.id DESC"; // Tampilkan yang terbaru di atas

$result = mysqli_query($conn, $sql);

if ($result) {
    // Ambil semua hasil dan masukkan ke array
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
} else {
    // Penanganan error database
    $status_message = "Error saat mengambil data produk: " . mysqli_error($conn);
}

// 3. --- Logika Notifikasi Setelah Redirect (Flash Message dari Session) ---
if (isset($_SESSION['status_message'])) {
    $status_message = $_SESSION['status_message'];
    $status_type = $_SESSION['status_type'] ?? 'success'; // Default success
    
    // Hapus sesi agar notifikasi tidak muncul lagi saat di-refresh
    unset($_SESSION['status_message']);
    unset($_SESSION['status_type']);
} 
// Juga cek dari GET parameter (PRG pattern)
elseif (isset($_GET['status'])) {
    if ($_GET['status'] === 'success_create') {
        $status_message = '✅ Produk baru berhasil ditambahkan!';
    } elseif ($_GET['status'] === 'success_update') {
        $status_message = '✍️ Produk berhasil diperbarui!';
    } elseif ($_GET['status'] === 'success_delete') {
        $status_message = '🗑️ Produk berhasil dihapus!';
    }
}

// Tutup koneksi
mysqli_close($conn); 

// --- Pemuatan Header Admin ---
// Asumsi ada di ../partials/admin_header.php
include '../partials/admin_header.php'; 
?>

<div class="container">
    <h2>Manajemen Produk</h2>
    
    <div class="text-right mb-4">
        <a href="create.php" class="btn btn-primary">➕ Tambah Produk</a>
    </div>

    <?php if (!empty($status_message)): ?>
        <div class="alert alert-<?php echo isset($status_type) ? $status_type : 'success'; ?>">
            <?php echo htmlspecialchars($status_message); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <p class="alert alert-info">Belum ada produk yang terdaftar.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td>
                                <?php if (!empty($product['image'])): ?>
                                    <img src="../../uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                                         width="50">
                                <?php else: ?>
                                    [Gambar Tidak Ada]
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                            <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($product['stock']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                
                                <a href="delete.php?id=<?php echo $product['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Anda yakin ingin menghapus produk ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php 
// --- Pemuatan Footer Admin ---
include '../partials/admin_footer.php'; 
?>