<?php
// Konfigurasi Database (didefinisikan di Global Scope)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "online_shop"; // Pastikan nama database ini sudah Anda buat!

/**
 * Fungsi untuk membuat dan mengembalikan objek koneksi MySQLi.
 * Jika koneksi gagal, fungsi tidak akan menghentikan skrip —
 * sebagai gantinya mengembalikan null sehingga aplikasi dapat
 * menggunakan fallback berbasis file (JSON) untuk penyimpanan.
 * @return mysqli|null
 */
function connect_db()
{
    global $host, $user, $pass, $db;

    // Sembunyikan warning jika MySQL tidak berjalan
    $conn = @mysqli_connect($host, $user, $pass, $db);

    if ($conn && !mysqli_connect_errno()) {
        // Return mysqli connection
        return $conn;
    }

    // Kembalikan null jika koneksi gagal (jangan die())
    return null;
}

// --- Fallback penyimpanan pengguna berbasis file (JSON) ---
// Lokasi file: project_root/data/users.json
define('USER_DATA_DIR', __DIR__ . '/../data');
define('USER_JSON_FILE', USER_DATA_DIR . '/users.json');

function ensure_user_storage()
{
    if (!is_dir(USER_DATA_DIR)) {
        mkdir(USER_DATA_DIR, 0755, true);
    }
    if (!file_exists(USER_JSON_FILE)) {
        file_put_contents(USER_JSON_FILE, json_encode([], JSON_PRETTY_PRINT));
    }
}

function load_users_file()
{
    ensure_user_storage();
    $json = file_get_contents(USER_JSON_FILE);
    $data = json_decode($json, true);
    if (!is_array($data)) {
        $data = [];
    }
    return $data;
}

function save_users_file(array $users)
{
    ensure_user_storage();
    file_put_contents(USER_JSON_FILE, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

function get_user_by_email_file($email)
{
    $users = load_users_file();
    foreach ($users as $u) {
        if (isset($u['email']) && strtolower($u['email']) === strtolower($email)) {
            return $u;
        }
    }
    return null;
}

function insert_user_file($name, $email, $password_hashed, $role = 'user')
{
    $users = load_users_file();
    // Cek duplikat
    foreach ($users as $u) {
        if (isset($u['email']) && strtolower($u['email']) === strtolower($email)) {
            return ['ok' => false, 'error' => 'Email sudah terdaftar.'];
        }
    }

    $maxId = 0;
    foreach ($users as $u) {
        if (isset($u['id'])) {
            $maxId = max($maxId, (int)$u['id']);
        }
    }

    $newId = $maxId + 1;
    $newUser = [
        'id' => $newId,
        'name' => $name,
        'email' => $email,
        'password' => $password_hashed,
        'role' => $role
    ];

    $users[] = $newUser;
    save_users_file($users);
    return ['ok' => true, 'user' => $newUser];
}
