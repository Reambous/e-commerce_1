<?php
// 1. --- Pemuatan Konfigurasi dan Otorisasi ---
session_start();
include '../../config/database.php'; // Keluar dari products/ (..) lalu keluar lagi dari admin/ (..)

// Pastikan fungsi koneksi ada
if (!function_exists('connect_db')) {
    die("Error: Fungsi koneksi database (connect_db) tidak ditemukan.");
}

// Pengecekan Otorisasi Admin
if (!isset($_SESSION["is_login"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$conn = connect_db();
$errors = []; // Array untuk menyimpan pesan kesalahan

// --- Logika Ambil Kategori untuk Dropdown ---
$categories = [];
try {
    $sql_categories = "SELECT id, name FROM categories ORDER BY name ASC";
    $result_categories = mysqli_query($conn, $sql_categories);
    if ($result_categories) {
        $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
        mysqli_free_result($result_categories);
    }
} catch (Exception $e) {
    // Penanganan error jika tabel categories bermasalah
    $errors[] = "Gagal memuat kategori: " . $e->getMessage();
}


// 2. --- Logika Pemrosesan Form (POST) ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil dan bersihkan data input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (int)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category_id = (int)$_POST['category_id'];
    $image_name = ''; // Placeholder untuk nama file gambar

    // --- Validasi Sederhana ---
    if (empty($name)) {
        $errors[] = "Nama produk wajib diisi.";
    }
    if ($price <= 0) {
        $errors[] = "Harga harus lebih dari 0.";
    }
    if ($category_id === 0) {
        $errors[] = "Kategori produk wajib dipilih.";
    }
    // TODO: Tambahkan validasi upload gambar di sini

    // Jika tidak ada error validasi, masukkan ke database
    if (empty($errors)) {
        // Asumsi: Proses upload gambar berhasil dan nama file tersimpan di $image_name
        
        $sql_insert = "INSERT INTO products (name, description, price, stock, category_id, image) 
                       VALUES ('$name', '$description', $price, $stock, $category_id, '$image_name')";
        
        if (mysqli_query($conn, $sql_insert)) {
            // Sukses: Lakukan Redirect ke halaman index (PRG Pattern)
            mysqli_close($conn);
            header("Location: index.php?status=success_create");
            exit();
        } else {
            $errors[] = "Gagal menyimpan produk: " . mysqli_error($conn);
        }
    }
}

// Tutup koneksi jika tidak terjadi redirect
mysqli_close($conn); 

// --- Pemuatan Header Admin ---
// Asumsi ada di ../partials/admin_header.php
include '../partials/admin_header.php'; 
?>

<div class="container">
    <h2>Tambah Produk Baru</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="create.php" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="name">Nama Produk:</label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="category_id">Kategori:</label>
            <select id="category_id" name="category_id" required>
                <option value="0">-- Pilih Kategori --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"
                        <?php echo (isset($_POST['category_id']) && (int)$_POST['category_id'] === $category['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (empty($categories)): ?>
                <p class="warning">⚠️ Belum ada kategori! Silakan buat kategori terlebih dahulu.</p>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Harga (Rp):</label>
            <input type="number" id="price" name="price" required min="1" 
                   value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="stock">Stok:</label>
            <input type="number" id="stock" name="stock" required min="0" 
                   value="<?php echo isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : '0'; ?>">
        </div>

        <div class="form-group">
            <label for="image">Gambar Produk:</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Produk</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php 
// --- Pemuatan Footer Admin ---
include '../partials/admin_footer.php'; 
?>