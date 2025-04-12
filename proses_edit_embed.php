<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: live.php");
    exit;
}

// Simulasi update ke database atau file
$index = $_POST['index'];
$link_baru = $_POST['link'];

// Simpan ke database atau file (belum di-implementasi)
// Contoh sederhana: redirect kembali
$_SESSION['pesan'] = "Link berhasil diperbarui.";
header("Location: live.php");
exit;
