<?php include "header.php"; 

function isGoogleEmail($email)
{
    $domain = explode('@', $email);
    return strtolower($domain[1]) === 'gmail.com';
}

// Pesan gagal jika alamat email bukan dari Google
$fail_message = "";
if (isset($_GET['fail']) && $_GET['fail'] === 'true') {
    $fail_message = "Maaf, hanya alamat email dari Google yang diperbolehkan.";
}
?>
<div class="banner">
    <h1>CCTV Pelayanan di Kantor UPT Pengujian Kendaraan Bermotor Gresik</h1>
</div>
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <h2>Informasi Kontak</h2>
                    <ul>
                        <li><b>Alamat:</b> Jalan Raya Cerme Lor, Banjarsari, Cerme, Gresik Regency, East Java 61171</li>
                        <li><b>Telepon:</b> +62 123 4556 789</li>
                        <li><b>Email:</b> uptpkbgresik@gmail.com</li>
                        <li><b>Facebook:</b> @dishubkab.gresik</li>
                    </ul>
                    <h3>Informasi CCTV</h3>
                    <p>CCTV yang terpasang di Kantor UPT Pengujian Kendaraan Bermotor Gresik bertujuan untuk memantau kegiatan pelayanan pengujian kendaraan secara real-time. Kami menyediakan akses visual untuk memastikan setiap langkah dalam proses pengujian dapat dipantau oleh petugas dan masyarakat.</p>
                    <ul>
                        <li>Memonitor jalannya pelayanan pengujian kendaraan.</li>
                        <li>Meningkatkan transparansi dan keamanan di kantor.</li>
                        <li>Menjamin kualitas pelayanan publik di UPT Pengujian Kendaraan Bermotor Gresik.</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <h2>Formulir Kontak</h2>
                    <p>Jika Anda memiliki pertanyaan, laporan, atau saran terkait pelayanan CCTV di Kantor UPT Pengujian Kendaraan Bermotor Gresik, silakan menghubungi kami melalui formulir di bawah ini:</p>
                    <?php if (!empty($fail_message)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $fail_message; ?>
                        </div>
                    <?php endif; ?>
                    <form action="process_contact.php" method="post">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email (Gmail)</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subjek" class="form-label">Subjek</label>
                            <input type="text" class="form-control" id="subjek" name="subjek" required>
                        </div>
                        <div class="mb-3">
                            <label for="pesan" class="form-label">Pesan</label>
                            <textarea class="form-control" id="pesan" name="pesan" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                    </form><br>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
