<?php
// views/cart.php - Menampilkan keranjang belanja
session_start();

$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Handle hapus dari keranjang
if (isset($_POST['remove_item'])) {
    $remove_id = (int)$_POST['remove_item'];
    $cart = array_filter($cart, function ($item) use ($remove_id) {
        return $item['id'] !== $remove_id;
    });
    $_SESSION['cart'] = $cart;
}

// Handle update quantity
if (isset($_POST['update_quantity'])) {
    $update_id = (int)$_POST['update_id'];
    $new_qty = (int)$_POST['quantities'][$update_id] ?? 1;
    $new_qty = max(1, min($new_qty, 100));

    foreach ($cart as &$item) {
        if ($item['id'] == $update_id) {
            $item['quantity'] = $new_qty;
            break;
        }
    }
    $_SESSION['cart'] = $cart;
}

// Hitung total
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - ShopHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-2xl font-bold text-gray-900">ShopHub</h1>
                <div class="flex items-center space-x-6">
                    <a href="products_list.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">← Kembali Belanja</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto mt-8 px-4 pb-16">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-2 text-gray-900">🛒 Keranjang Belanja</h1>
            <p class="text-gray-600 mb-8">Anda memiliki <?php echo count($cart); ?> produk di keranjang</p>

            <?php if (empty($cart)): ?>
                <!-- Keranjang Kosong -->
                <div class="text-center py-16">
                    <p class="text-5xl mb-4">🛒</p>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Keranjang Anda Kosong</h2>
                    <p class="text-gray-600 mb-8">Mulai berbelanja untuk menambahkan produk ke keranjang</p>
                    <a href="products_list.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg inline-block">
                        Lihat Produk
                    </a>
                </div>

            <?php else: ?>
                <!-- Daftar Produk di Keranjang -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Kolom Produk (2/3) -->
                    <div class="lg:col-span-2 space-y-4">
                        <?php foreach ($cart as $item): ?>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                                <div class="flex gap-6">
                                    <!-- Gambar Produk -->
                                    <div class="w-24 h-24 flex-shrink-0">
                                        <img src="https://via.placeholder.com/150x150?text=<?php echo urlencode($item['name']); ?>"
                                            alt="<?php echo htmlspecialchars($item['name']); ?>"
                                            class="w-full h-full object-cover rounded-lg">
                                    </div>

                                    <!-- Info Produk -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </h3>
                                        <p class="text-indigo-600 font-semibold mt-2">
                                            Rp<?php echo number_format($item['price'], 0, ',', '.'); ?>
                                        </p>

                                        <!-- Quantity & Actions -->
                                        <div class="flex items-center gap-4 mt-4">
                                            <form method="POST" class="flex items-center gap-2">
                                                <label for="qty_<?php echo $item['id']; ?>" class="text-sm text-gray-600">Qty:</label>
                                                <input type="number" id="qty_<?php echo $item['id']; ?>"
                                                    name="quantities[<?php echo $item['id']; ?>]"
                                                    value="<?php echo $item['quantity']; ?>"
                                                    min="1" max="100"
                                                    class="w-16 px-2 py-1 border border-gray-300 rounded text-center">
                                                <button type="submit" name="update_quantity"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                                    Update
                                                </button>
                                                <input type="hidden" name="update_id" value="<?php echo $item['id']; ?>">
                                            </form>

                                            <form method="POST" class="inline">
                                                <button type="submit" name="remove_item" value="<?php echo $item['id']; ?>"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"
                                                    onclick="return confirm('Hapus produk ini dari keranjang?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-sm text-gray-600">Subtotal</p>
                                        <p class="text-2xl font-bold text-indigo-600">
                                            Rp<?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Ringkasan Pesanan (1/3) -->
                    <div>
                        <div class="bg-indigo-50 border-2 border-indigo-600 rounded-lg p-6 sticky top-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Ringkasan Pesanan</h3>

                            <div class="space-y-3 mb-6 pb-6 border-b-2">
                                <?php
                                $subtotal = $total;
                                $tax = $subtotal * 0.10; // Pajak 10%
                                $shipping = 50000; // Ongkos kirim flat
                                $grand_total = $subtotal + $tax + $shipping;
                                ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Subtotal</span>
                                    <span class="font-semibold">Rp<?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Pajak (10%)</span>
                                    <span class="font-semibold">Rp<?php echo number_format($tax, 0, ',', '.'); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Ongkos Kirim</span>
                                    <span class="font-semibold">Rp<?php echo number_format($shipping, 0, ',', '.'); ?></span>
                                </div>
                            </div>

                            <div class="flex justify-between mb-6 text-xl">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="font-bold text-indigo-600">Rp<?php echo number_format($grand_total, 0, ',', '.'); ?></span>
                            </div>

                            <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition mb-3">
                                💳 Lanjutkan Pembayaran
                            </button>

                            <a href="products_list.php" class="block text-center bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold py-2 px-4 rounded-lg transition">
                                ← Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Info Pengiriman -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-bold text-blue-900 mb-2">✓ Pengiriman Gratis</h4>
                        <p class="text-sm text-blue-700">Untuk pembelian di atas Rp 500.000</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-bold text-green-900 mb-2">✓ Pembayaran Aman</h4>
                        <p class="text-sm text-green-700">Berbagai metode pembayaran tersedia</p>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <h4 class="font-bold text-purple-900 mb-2">✓ Jaminan Uang Kembali</h4>
                        <p class="text-sm text-purple-700">Jika produk tidak sesuai deskripsi</p>
                    </div>
                </div>

            <?php endif; ?>
        </div>
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
</body>

</html>