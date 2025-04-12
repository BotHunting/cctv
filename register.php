<?php
// Memulai sesi
session_start();

// Memuat file koneksi
include "koneksi.php";

// Inisialisasi pesan error dan sukses
$error = "";
$success = "";

// Proses registrasi ketika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['user'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Memastikan data tidak kosong
    if (empty($user) || empty($password) || empty($password_confirm)) {
        $error = "Semua kolom harus diisi!";
    } else if ($password != $password_confirm) {
        // Memastikan password dan konfirmasi password sama
        $error = "Password dan konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username sudah terdaftar
        $sql = "SELECT * FROM admin WHERE user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username sudah terdaftar!";
        } else {
            // Menggunakan password_hash untuk mengamankan password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Menyimpan data pengguna baru dengan password yang sudah di-hash
            $sql = "INSERT INTO admin (user, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $user, $password_hash);
            $stmt->execute();

            $success = "Registrasi berhasil! Silakan login.";
        }

        $stmt->close();
    }
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Menggunakan Bootstrap untuk styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mt-5">Register Admin</h2>

                <!-- Menampilkan pesan error jika ada -->
                <?php if ($error != ""): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Menampilkan pesan sukses jika registrasi berhasil -->
                <?php if ($success != ""): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <!-- Form registrasi -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="user" class="form-label">Username</label>
                        <input type="text" class="form-control" id="user" name="user" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>

                <div class="text-center mt-3">
                    <p>Sudah punya akun? <a href="login.php" class="btn btn-secondary">Login</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Menggunakan Bootstrap JS untuk interaktivitas -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
