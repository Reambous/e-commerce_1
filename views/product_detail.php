<?php
// views/product_detail.php - Menampilkan detail produk
include '../config/database.php';
session_start();

$conn = connect_db();
$db_available = ($conn !== null && $conn !== false);
$product = null;
$error_message = '';

// Ambil ID produk dari URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id > 0) {
    if ($db_available) {
        // Query dari MySQL
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
            } else {
                $error_message = "Produk tidak ditemukan.";
            }
            $stmt->close();
        }
    } else {
        // Fallback: data dummy
        $products_dummy = [
            1 => [
                'id' => 1,
                'name' => 'Laptop Gaming Pro',
                'price' => 15000000,
                'description' => 'Laptop gaming dengan prosesor Intel i9 dan GPU RTX 4090',
                'image' => 'https://via.placeholder.com/600x400?text=Laptop+Gaming'
            ],
            2 => [
                'id' => 2,
                'name' => 'Smartphone Flagship',
                'price' => 10000000,
                'description' => 'Smartphone flagship dengan layar AMOLED 6.7 inci',
                'image' => 'https://via.placeholder.com/600x400?text=Smartphone'
            ]
        ];
        $product = $products_dummy[$product_id] ?? null;
        if (!$product) {
            $error_message = "Produk tidak ditemukan.";
        }
    }
} else {
    $error_message = "ID produk tidak valid.";
}

// Handle tambah ke keranjang
$cart_message = '';
if (isset($_POST['add_to_cart']) && $product) {
    // Simpan ke session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $quantity = (int)($_POST['quantity'] ?? 1);
    $quantity = max(1, min($quantity, 100));

    // Tambah atau update keranjang
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product['id']) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }

    $cart_message = "✓ Produk ditambahkan ke keranjang!";
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product ? htmlspecialchars($product['name']) : 'Detail Produk'; ?> - ShopHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-2xl font-bold text-gray-900">ShopHub</h1>
                <div class="flex items-center space-x-6">
                    <a href="products_list.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">← Kembali</a>
                    <a href="cart.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">🛒 Keranjang</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto mt-8 px-4 pb-16">
        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 border-red-400 text-red-700 p-4 rounded-lg mb-8">
                <p class="font-medium"><?php echo $error_message; ?></p>
            </div>
            <a href="products_list.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg inline-block">
                Lihat Semua Produk
            </a>
        <?php elseif ($product): ?>
            <!-- Detail Produk -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
                    <!-- Gambar Produk -->
                    <div>
                        <img src="<?php echo $product['image'] ?? 'https://via.placeholder.com/600x400?text=No+Image'; ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                            class="w-full rounded-lg shadow-md object-cover">
                    </div>

                    <!-- Informasi Produk -->
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h1>

                        <div class="bg-indigo-50 p-6 rounded-lg mb-6">
                            <p class="text-sm text-gray-600 mb-2">Harga</p>
                            <p class="text-4xl font-bold text-indigo-600">
                                Rp<?php echo number_format($product['price'] ?? 0, 0, ',', '.'); ?>
                            </p>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi Produk</h3>
                            <p class="text-gray-700 leading-relaxed">
                                <?php echo htmlspecialchars($product['description'] ?? 'Tidak ada deskripsi'); ?>
                            </p>
                        </div>

                        <!-- Form Pembelian -->
                        <form method="POST" class="space-y-4">
                            <?php if (!empty($cart_message)): ?>
                                <div class="bg-green-100 border-green-400 text-green-700 p-4 rounded-lg">
                                    <p class="font-medium"><?php echo $cart_message; ?></p>
                                </div>
                            <?php endif; ?>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="100"
                                        class="w-20 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <span class="text-gray-600">Stok: <span class="font-semibold">Tersedia</span></span>
                                </div>
                            </div>

                            <button type="submit" name="add_to_cart"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                🛒 Tambahkan ke Keranjang
                            </button>

                            <button type="button"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                💳 Beli Sekarang
                            </button>
                        </form>

                        <!-- Info Tambahan -->
                        <div class="mt-8 pt-8 border-t">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600 text-sm">SKU</p>
                                    <p class="font-semibold text-gray-900">#SHOP<?php echo str_pad($product['id'], 5, '0', STR_PAD_LEFT); ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Kategori</p>
                                    <p class="font-semibold text-gray-900"><?php echo $product['category'] ?? 'Elektronik'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produk Terkait -->
            <div class="mt-16">
                <h2 class="text-2xl font-bold mb-6">Produk Terkait</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                            <img src="https://via.placeholder.com/300x200?text=Produk+<?php echo $i; ?>"
                                alt="Produk <?php echo $i; ?>"
                                class="w-full h-40 object-cover">
                            <div class="p-4">
                                <h4 class="font-bold text-gray-900 mb-2">Produk Rekomendasi <?php echo $i; ?></h4>
                                <p class="text-indigo-600 font-bold">Rp<?php echo number_format(5000000 * $i, 0, ',', '.'); ?></p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="text-center">
                <p class="font-semibold mb-2">ShopHub © 2025</p>
                <p class="text-gray-400">Toko online terpercaya untuk kebutuhan Anda</p>
            </div>
        </div>
    </footer>

    <?php
    if ($db_available && isset($conn) && $conn) {
        $conn->close();
    }
    ?>
</body>

</html>