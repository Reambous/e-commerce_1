<?php
// views/checkout.php - Halaman checkout pembayaran
session_start();

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header("Location: cart.php");
    exit();
}

// Hitung total
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * 0.10;
$shipping = 50000;
$grand_total = $subtotal + $tax + $shipping;

$checkout_message = '';
$checkout_status = '';

// Handle form checkout
if (isset($_POST['confirm_order'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');

    // Validasi
    $errors = [];
    if (empty($fullname)) $errors[] = "Nama lengkap wajib diisi.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid.";
    if (empty($phone) || strlen($phone) < 10) $errors[] = "Nomor telepon tidak valid.";
    if (empty($address)) $errors[] = "Alamat pengiriman wajib diisi.";
    if (empty($payment_method)) $errors[] = "Pilih metode pembayaran.";

    if (!empty($errors)) {
        $checkout_message = implode("<br>", $errors);
        $checkout_status = 'error';
    } else {
        // Simulasi proses order
        $checkout_message = "✓ Pesanan berhasil dibuat! No. Order: #ORD" . date('YmdHis') . ". Silakan lakukan pembayaran.";
        $checkout_status = 'success';
        // Bisa redirect ke payment gateway atau halaman konfirmasi
    }
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ShopHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-2xl font-bold text-gray-900">ShopHub</h1>
                <a href="cart.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">← Kembali ke Keranjang</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto mt-8 px-4 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Checkout (2/3) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h1 class="text-3xl font-bold mb-2 text-gray-900">Informasi Pengiriman</h1>
                    <p class="text-gray-600 mb-8">Isi data berikut untuk menyelesaikan pesanan</p>

                    <?php if (!empty($checkout_message)): ?>
                        <div class="<?php echo ($checkout_status === 'success') ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> mb-6 p-4 rounded-lg border">
                            <p class="font-medium"><?php echo $checkout_message; ?></p>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-6">
                        <!-- Data Pribadi -->
                        <div class="border-b pb-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Data Pribadi</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="fullname" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap *
                                    </label>
                                    <input type="text" id="fullname" name="fullname" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                        value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>"
                                        placeholder="Nama lengkap Anda">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email *
                                    </label>
                                    <input type="email" id="email" name="email" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                        placeholder="email@example.com">
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nomor Telepon *
                                    </label>
                                    <input type="tel" id="phone" name="phone" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                        value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                        placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                        </div>

                        <!-- Alamat Pengiriman -->
                        <div class="border-b pb-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Alamat Pengiriman</h3>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Lengkap *
                                </label>
                                <textarea id="address" name="address" required rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Jalan, No. Rumah, Kelurahan, Kecamatan, Kabupaten/Kota, Provinsi, Kode Pos"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="border-b pb-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Metode Pembayaran</h3>

                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-indigo-50 cursor-pointer">
                                    <input type="radio" name="payment_method" value="transfer_bank" class="w-4 h-4 text-indigo-600"
                                        <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'transfer_bank') ? 'checked' : ''; ?>>
                                    <span class="ml-3">
                                        <span class="font-semibold text-gray-900">Transfer Bank</span>
                                        <span class="text-gray-600 text-sm block">BCA, Mandiri, BNI, Permata</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-indigo-50 cursor-pointer">
                                    <input type="radio" name="payment_method" value="credit_card" class="w-4 h-4 text-indigo-600"
                                        <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'credit_card') ? 'checked' : ''; ?>>
                                    <span class="ml-3">
                                        <span class="font-semibold text-gray-900">Kartu Kredit</span>
                                        <span class="text-gray-600 text-sm block">Visa, MasterCard, Amex</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-indigo-50 cursor-pointer">
                                    <input type="radio" name="payment_method" value="ewallet" class="w-4 h-4 text-indigo-600"
                                        <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'ewallet') ? 'checked' : ''; ?>>
                                    <span class="ml-3">
                                        <span class="font-semibold text-gray-900">E-Wallet</span>
                                        <span class="text-gray-600 text-sm block">OVO, GoPay, DANA, LinkAja</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-indigo-50 cursor-pointer">
                                    <input type="radio" name="payment_method" value="cod" class="w-4 h-4 text-indigo-600"
                                        <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'cod') ? 'checked' : ''; ?>>
                                    <span class="ml-3">
                                        <span class="font-semibold text-gray-900">Cash on Delivery (COD)</span>
                                        <span class="text-gray-600 text-sm block">Bayar saat produk tiba</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" name="confirm_order"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition">
                            ✓ Konfirmasi Pesanan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Ringkasan Pesanan (1/3) -->
            <div>
                <div class="bg-indigo-50 border-2 border-indigo-600 rounded-lg p-6 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Ringkasan Pesanan</h3>

                    <!-- List Produk -->
                    <div class="space-y-3 mb-6 pb-6 border-b-2">
                        <?php foreach ($cart as $item): ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700"><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['quantity']; ?></span>
                                <span class="font-semibold">Rp<?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Perhitungan -->
                    <div class="space-y-3 mb-6 pb-6 border-b-2">
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

                    <div class="flex justify-between text-xl">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="font-bold text-indigo-600">Rp<?php echo number_format($grand_total, 0, ',', '.'); ?></span>
                    </div>

                    <div class="mt-6 p-4 bg-blue-100 border border-blue-300 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <span class="font-semibold">ℹ️ Info:</span> Pastikan data pengiriman sudah benar sebelum membayar.
                        </p>
                    </div>
                </div>
            </div>
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