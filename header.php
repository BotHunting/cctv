<?php
session_start();

// Cek apakah pengguna sudah login
$logged_in = isset($_SESSION['username']) && !empty($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCTV Pelayanan UPT PKB Gresik</title>
    <link rel="icon" href="images/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <header class="container-fluid bg-dark text-light py-3">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="index.php" class="text-light text-decoration-none fs-4">
                <img src="images/logo.png" alt="Logo" height="50" class="me-2">
                CCTV Pelayanan UPT PKB Gresik
            </a>
            <nav class="nav">
                <a class="nav-link text-light" href="index.php">Beranda</a>
                <a class="nav-link text-light" href="live.php">CCTV Pelayanan</a>
                <a class="nav-link text-light" href="layanan.php">Layanan</a>
                <a class="nav-link text-light" href="kontak.php">Kontak</a>

                <!-- Menampilkan tombol Logout jika sudah login, atau Login jika belum login -->
                <?php if ($logged_in): ?>
                    <a class="nav-link text-light" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link text-light" href="login.php">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="container mt-5">
