<?php
session_start();
include "koneksi.php";

// Inisialisasi pesan error
$error = "";

// Proses login ketika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['user'];
    $password = $_POST['password'];

    // Memastikan data tidak kosong
    if (empty($user) || empty($password)) {
        $error = "Username dan Password harus diisi!";
    } else {
        // Query untuk mencari pengguna berdasarkan username
        $sql = "SELECT * FROM admin WHERE user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user); // "s" berarti string
        $stmt->execute();
        $result = $stmt->get_result();

        // Mengecek apakah ada pengguna yang cocok
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Verifikasi password menggunakan password_verify
            if (password_verify($password, $row['password'])) {
                // Pengguna ditemukan, set sesi dan redirect ke halaman dashboard atau halaman lain
                $_SESSION['username'] = $user; // Menyimpan username di session
                header("Location: index.php"); // Ganti dengan halaman tujuan setelah login
                exit;
            } else {
                $error = "Username atau Password salah!";
            }
        } else {
            $error = "Username atau Password salah!";
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mt-5">Login Admin</h2>

                <!-- Menampilkan pesan error jika ada -->
                <?php if ($error != ""): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Form login -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="user" class="form-label">Username</label>
                        <input type="text" class="form-control" id="user" name="user" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
