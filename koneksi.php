<?php
// Fungsi untuk mencoba koneksi ke database
function cobaKoneksi($host, $user, $pass, $name, $port = 3306) {
    $koneksi = @new mysqli($host, $user, $pass, $name, $port);
    if ($koneksi->connect_errno === 0) {
        $koneksi->set_charset("utf8");
        return $koneksi;
    }
    return false;
}

// Konfigurasi koneksi offline (lokal)
$offline = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'name' => 'sql12772764',
    'port' => 3306
];

// Konfigurasi koneksi online
$online = [
    'host' => 'sql12.freesqldatabase.com',
    'user' => 'sql12772764',
    'pass' => '86hFpgPGtN',
    'name' => 'sql12772764',
    'port' => 3306
];

// Coba koneksi offline dahulu
$conn = cobaKoneksi($offline['host'], $offline['user'], $offline['pass'], $offline['name'], $offline['port']);

if (!$conn) {
    // Jika gagal, coba koneksi online
    $conn = cobaKoneksi($online['host'], $online['user'], $online['pass'], $online['name'], $online['port']);
}

// Jika masih gagal
if (!$conn) {
    die("Koneksi ke database gagal total.");
}
$koneksi_status = $conn === false ? 'gagal' : ($conn->host_info === 'localhost' ? 'offline' : 'online');

?>
