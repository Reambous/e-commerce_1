<?php
// views/home.php - Halaman home/landing page ShopHub
session_start();
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopHub - Toko Online Terpercaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-2xl font-bold text-gray-900">ShopHub</h1>
                <div class="flex items-center space-x-6">
                    <?php if (isset($_SESSION['is_login'])): ?>
                        <span class="text-gray-700">Halo, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <a href="products_list.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">Belanja</a>
                        <a href="cart.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">🛒 Keranjang</a>
                        <a href="../admin/dashboard.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">Dashboard</a>
                    <?php else: ?>
                        <a href="products_list.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">Belanja</a>
                        <a href="../admin/login.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">Login</a>
                        <a href="../admin/register.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded font-semibold">Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-5xl font-bold mb-4">Belanja Produk Terbaik</h1>
                    <p class="text-xl mb-6 opacity-90">Temukan ribuan produk berkualitas dengan harga terjangkau di ShopHub. Belanja mudah, aman, dan terpercaya.</p>

                    <div class="flex gap-4">
                        <a href="products_list.php" class="bg-white text-indigo-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition">
                            🛍️ Mulai Belanja
                        </a>
                        <a href="#promo" class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-indigo-600 transition">
                            📢 Lihat Promo
                        </a>
                    </div>
                </div>

                <div class="text-center">
                    <img src="https://via.placeholder.com/400x300?text=ShopHub" alt="ShopHub" class="rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- Fitur-Fitur -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Mengapa Pilih ShopHub?</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
            <div class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
                <div class="text-4xl mb-4">✓</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Produk Berkualitas</h3>
                <p class="text-gray-600">Semua produk kami dipilih dan diverifikasi untuk memastikan kualitas terbaik</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
                <div class="text-4xl mb-4">🚚</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pengiriman Cepat</h3>
                <p class="text-gray-600">Pengiriman ke seluruh Indonesia dengan jaminan sampai tepat waktu</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
                <div class="text-4xl mb-4">💰</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Harga Terjangkau</h3>
                <p class="text-gray-600">Dapatkan harga terbaik dengan berbagai program diskon dan promosi</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
                <div class="text-4xl mb-4">🔒</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pembayaran Aman</h3>
                <p class="text-gray-600">Transaksi aman dengan enkripsi SSL dan berbagai metode pembayaran</p>
            </div>
        </div>
    </div>

    <!-- Kategori Produk -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold mb-12 text-gray-900">Kategori Produk Populer</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <?php
                $categories = [
                    ['icon' => '💻', 'name' => 'Elektronik', 'count' => '2.5K+'],
                    ['icon' => '👕', 'name' => 'Fashion', 'count' => '5K+'],
                    ['icon' => '🏠', 'name' => 'Rumah & Dekorasi', 'count' => '3.2K+'],
                    ['icon' => '🍕', 'name' => 'Makanan & Minuman', 'count' => '1.8K+'],
                    ['icon' => '⚽', 'name' => 'Olahraga', 'count' => '1.5K+'],
                    ['icon' => '📚', 'name' => 'Buku & Media', 'count' => '0.9K+'],
                ];
                ?>

                <?php foreach ($categories as $cat): ?>
                    <a href="products_list.php?category=<?php echo urlencode($cat['name']); ?>"
                        class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-6 text-center hover:shadow-lg transition hover:from-indigo-100 hover:to-purple-100">
                        <div class="text-4xl mb-2"><?php echo $cat['icon']; ?></div>
                        <h3 class="font-bold text-gray-900"><?php echo $cat['name']; ?></h3>
                        <p class="text-sm text-gray-600"><?php echo $cat['count']; ?> produk</p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Promo Section -->
    <div id="promo" class="bg-yellow-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold mb-12 text-gray-900">Promo & Flash Sale</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg p-8 relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-3xl font-bold mb-2">Flash Sale 🔥</h3>
                        <p class="text-xl mb-4">Diskon hingga 70% untuk produk pilihan</p>
                        <p class="text-sm mb-4">Waktu tersisa: <span class="font-bold">3 jam 45 menit</span></p>
                        <a href="products_list.php" class="bg-white text-red-600 font-bold py-2 px-6 rounded-lg inline-block hover:bg-gray-100 transition">
                            Lihat Flash Sale
                        </a>
                    </div>
                    <div class="absolute top-0 right-0 text-white opacity-10 text-9xl">🔥</div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg p-8 relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-3xl font-bold mb-2">Gratis Ongkir 🚚</h3>
                        <p class="text-xl mb-4">Untuk pembelian minimal Rp 100.000</p>
                        <p class="text-sm mb-4">Berlaku sampai: <span class="font-bold">31 Desember 2025</span></p>
                        <a href="products_list.php" class="bg-white text-green-600 font-bold py-2 px-6 rounded-lg inline-block hover:bg-gray-100 transition">
                            Belanja Sekarang
                        </a>
                    </div>
                    <div class="absolute top-0 right-0 text-white opacity-10 text-9xl">🚚</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Produk Unggulan -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold mb-12 text-gray-900">Produk Unggulan Kami</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $featured_products = [
                ['id' => 1, 'name' => 'Laptop Gaming Pro', 'price' => 15000000, 'img' => 'Laptop+Gaming'],
                ['id' => 2, 'name' => 'Smartphone Flagship', 'price' => 10000000, 'img' => 'Smartphone'],
                ['id' => 3, 'name' => 'Earbuds Wireless', 'price' => 2500000, 'img' => 'Earbuds'],
                ['id' => 4, 'name' => 'Smart Watch Pro', 'price' => 3000000, 'img' => 'SmartWatch'],
            ];
            ?>

            <?php foreach ($featured_products as $prod): ?>
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                    <img src="https://via.placeholder.com/300x300?text=<?php echo urlencode($prod['img']); ?>"
                        alt="<?php echo htmlspecialchars($prod['name']); ?>"
                        class="w-full h-48 object-cover">

                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            <?php echo htmlspecialchars($prod['name']); ?>
                        </h3>

                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-indigo-600">
                                Rp<?php echo number_format($prod['price'], 0, ',', '.'); ?>
                            </span>
                            <a href="product_detail.php?id=<?php echo $prod['id']; ?>"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                                Lihat
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Testimoni -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold mb-12 text-center text-gray-900">Kepuasan Pelanggan</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php
                $testimonials = [
                    ['name' => 'Budi Santoso', 'rating' => 5, 'text' => 'Produk sampai dengan cepat dan sesuai deskripsi. Pelayanan ShopHub sangat memuaskan!'],
                    ['name' => 'Siti Nurhaliza', 'rating' => 5, 'text' => 'Pertama kali belanja di ShopHub dan hasilnya luar biasa. Akan belanja lagi di sini.'],
                    ['name' => 'Rizki Wijaya', 'rating' => 5, 'text' => 'Harga murah, kualitas bagus, pengiriman cepat. Rekomendasi 5 bintang!'],
                ];
                ?>

                <?php foreach ($testimonials as $testi): ?>
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo strtoupper(substr($testi['name'], 0, 1)); ?>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-gray-900"><?php echo $testi['name']; ?></h4>
                                <p class="text-yellow-500 text-sm">★★★★★</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">"<?php echo $testi['text']; ?>"</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Siap untuk Berbelanja?</h2>
            <p class="text-xl mb-8">Daftar sekarang dan dapatkan voucher belanja Rp 50.000!</p>

            <div class="flex gap-4 justify-center flex-wrap">
                <a href="products_list.php" class="bg-white text-indigo-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition">
                    Mulai Belanja Sekarang
                </a>
                <?php if (!isset($_SESSION['is_login'])): ?>
                    <a href="../admin/register.php" class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-indigo-600 transition">
                        Daftar Member Baru
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="font-bold mb-4">Tentang ShopHub</h4>
                    <p class="text-gray-400 text-sm">ShopHub adalah platform e-commerce terpercaya yang menyediakan berbagai produk berkualitas dengan harga terjangkau untuk seluruh Indonesia.</p>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Kategori</h4>
                    <ul class="text-gray-400 text-sm space-y-2">
                        <li><a href="products_list.php" class="hover:text-white">Elektronik</a></li>
                        <li><a href="products_list.php" class="hover:text-white">Fashion</a></li>
                        <li><a href="products_list.php" class="hover:text-white">Rumah & Dekorasi</a></li>
                        <li><a href="products_list.php" class="hover:text-white">Olahraga</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Bantuan</h4>
                    <ul class="text-gray-400 text-sm space-y-2">
                        <li><a href="#" class="hover:text-white">Hubungi Kami</a></li>
                        <li><a href="#" class="hover:text-white">FAQ</a></li>
                        <li><a href="#" class="hover:text-white">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Hubungi Kami</h4>
                    <ul class="text-gray-400 text-sm space-y-2">
                        <li>Email: support@shophub.com</li>
                        <li>Telepon: 1-800-SHOPHUB</li>
                        <li>WhatsApp: +62 812 3456 7890</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
                <p class="font-semibold mb-2">ShopHub © 2025</p>
                <p>Toko online terpercaya untuk kebutuhan Anda | Belanja Aman, Pembayaran Mudah, Pengiriman Cepat</p>
            </div>
        </div>
    </footer>
</body>

</html>