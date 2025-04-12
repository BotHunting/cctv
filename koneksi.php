<?php
// Konstanta koneksi database (gunakan pengecekan agar tidak redefinisi)
if (!defined('DB_HOST')) define('DB_HOST', 'sql12.freesqldatabase.com');
if (!defined('DB_USER')) define('DB_USER', 'sql12772764');
if (!defined('DB_PASS')) define('DB_PASS', '86hFpgPGtN');
if (!defined('DB_NAME')) define('DB_NAME', 'sql12772764');
if (!defined('DB_PORT')) define('DB_PORT', '3306');

// Membuat koneksi ke database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke UTF-8
$conn->set_charset("utf8");

?>
