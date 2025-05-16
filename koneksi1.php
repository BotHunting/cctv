<?php
// Konstanta koneksi database (gunakan pengecekan agar tidak redefinisi)
if (!defined(constant_name: 'DB_HOST')) define(constant_name: 'DB_HOST', value: 'localhost');
if (!defined(constant_name: 'DB_USER')) define(constant_name: 'DB_USER', value: 'root');
if (!defined(constant_name: 'DB_PASS')) define(constant_name: 'DB_PASS', value: '');
if (!defined(constant_name: 'DB_NAME')) define(constant_name: 'DB_NAME', value: 'sql12772764');

// Membuat koneksi ke database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke UTF-8
$conn->set_charset("utf8");
?>
