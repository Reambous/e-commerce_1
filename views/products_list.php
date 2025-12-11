<?php
// views/products_list.php - Menampilkan daftar produk
include '../config/database.php';
session_start();

$conn = connect_db();
$db_available = ($conn !== null && $conn !== false);

// Ambil data produk dari database atau fallback
$products = [];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($db_available) {
    // Query dari MySQL
    $query = "SELECT * FROM products";
    if (!empty($search)) {
        $search_safe = mysqli_real_escape_string($conn, $search);
        $query .= " WHERE name LIKE '%$search_safe%' OR description LIKE '%$search_safe%'";
    }
    $query .= " ORDER BY created_at DESC";

    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        mysqli_free_result($result);
    }
} else {
    // Fallback: gunakan data dummy untuk demo
    $products = [
        [
            'id' => 1,
            'name' => 'Laptop Gaming Pro',
            'price' => 15000000,
            'description' => 'Laptop gaming dengan prosesor Intel i9 dan GPU RTX 4090',
            'image' => 'https://via.placeholder.com/300x300?text=Laptop+Gaming'
        ],
        [
            'id' => 2,
            'name' => 'Smartphone Flagship',
            'price' => 10000000,
            'description' => 'Smartphone flagship dengan layar AMOLED 6.7 inci',
            'image' => 'https://via.placeholder.com/300x300?text=Smartphone'
        ],
        [
            'id' => 3,
            'name' => 'Earbuds Wireless',
            'price' => 2500000,
            'description' => 'Earbuds wireless dengan noise cancellation aktif',
            'image' => 'https://via.placeholder.com/300x300?text=Earbuds'
        ],
        [
            'id' => 4,
            'name' => 'Smart Watch',
            'price' => 3000000,
            'description' => 'Smartwatch dengan monitor kesehatan dan GPS',
            'image' => 'https://via.placeholder.com/300x300?text=Smart+Watch'
        ]
    ];
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - ShopHub</title>
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
                        <a href="cart.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">🛒 Keranjang</a>
                        <form method="POST" action="../admin/dashboard.php" class="inline">
                            <button name="logout" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                Logout
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="../admin/login.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search & Filter -->
    <div class="max-w-7xl mx-auto mt-8 px-4">
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">Cari Produk</h2>
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Cari produk..." required
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                    Cari
                </button>
                <a href="products_list.php" class="bg-gray-300 hover:bg-gray-400 text-gray-900 px-6 py-2 rounded-lg">
                    Reset
                </a>
            </form>
        </div>

        <!-- Daftar Produk -->
        <div class="mb-12">
            <h2 class="text-3xl font-bold mb-6">Produk Terbaru</h2>

            <?php if (empty($products)): ?>
                <div class="bg-yellow-100 border-yellow-400 text-yellow-700 p-4 rounded-lg">
                    <p class="font-medium">Tidak ada produk ditemukan.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                            <img src="<?php echo $product['image'] ?? 'https://via.placeholder.com/300x300?text=No+Image'; ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>"
                                class="w-full h-48 object-cover">

                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </h3>

                                <p class="text-gray-600 text-sm mb-4">
                                    <?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 80)) . '...'; ?>
                                </p>

                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-indigo-600">
                                        Rp<?php echo number_format($product['price'] ?? 0, 0, ',', '.'); ?>
                                    </span>
                                    <a href="product_detail.php?id=<?php echo $product['id']; ?>"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
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

    <?php
    if ($db_available && isset($conn) && $conn) {
        $conn->close();
    }
    ?>
</body>

</html>