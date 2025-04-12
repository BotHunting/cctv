<?php

include "header.php";
require 'koneksi.php';

// Cek login
$logged_in = isset($_SESSION['username']);

// Hapus video jika ada parameter hapus dan id valid
if ($logged_in && isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    $hapus_id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM video_cctv WHERE id = ?");
    $stmt->bind_param("i", $hapus_id);
    $stmt->execute();
    echo "<script>alert('Video berhasil dihapus'); window.location='live.php';</script>";
    exit;
}

// Ambil data video
$stmt = $conn->prepare("SELECT * FROM video_cctv ORDER BY id ASC");
$stmt->execute();
$videos = $stmt->get_result();
?>

<div class="banner">
    <img src="images/atcs.jpg" alt="ATCS" class="img-fluid">
</div>

<div class="main-content">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Live CCTV ATCS</h2>
            <!-- Tombol Tambah Video hanya muncul jika pengguna login -->
            <?php if ($logged_in): ?>
                <a href="tambah_video.php" class="btn btn-success">+ Tambah Video</a>
            <?php endif; ?>
        </div>

        <div class="row">
            <?php while ($video = $videos->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($video['lokasi']) ?></h5>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary open-video"
                                    data-src="<?= htmlspecialchars($video['link_embed']) ?>">Tampilkan Video</button>

                                <!-- Tombol Edit dan Hapus hanya muncul jika pengguna login -->
                                <?php if ($logged_in): ?>
                                    <a href="edit_video.php?id=<?= $video['id'] ?>" class="btn btn-warning">Edit Link</a>
                                    <a href="live.php?hapus=<?= $video['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus video ini?')">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- Modal untuk video overlay -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video Daerah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openButtons = document.querySelectorAll('.open-video');
        const videoModal = document.getElementById('videoModal');

        openButtons.forEach(button => {
            button.addEventListener('click', function () {
                const videoSrc = this.getAttribute('data-src');
                const videoIframe = videoModal.querySelector('iframe');

                if (videoSrc.includes('youtube.com') || videoSrc.includes('youtu.be')) {
                    videoIframe.src = videoSrc.replace('watch?v=', 'embed/') + '?autoplay=1';
                } else if (videoSrc.includes('.m3u8')) {
                    videoIframe.src = `https://www.jwplayer.com/players/8gfMlN9s-8GFjZ8kj.html?file=${videoSrc}`;
                } else {
                    videoIframe.src = videoSrc;
                }

                videoModal.classList.add('show');
                videoModal.style.display = 'block';
                document.body.classList.add('modal-open');
                const backdrop = document.createElement('div');
                backdrop.classList.add('modal-backdrop', 'fade', 'show');
                document.body.appendChild(backdrop);
            });
        });

        const closeButton = videoModal.querySelector('.btn-close');
        closeButton.addEventListener('click', function () {
            const videoIframe = videoModal.querySelector('iframe');
            videoIframe.src = '';
            videoModal.style.display = 'none';
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) document.body.removeChild(backdrop);
        });

        videoModal.addEventListener('click', function (event) {
            if (event.target === this) {
                const videoIframe = videoModal.querySelector('iframe');
                videoIframe.src = '';
                videoModal.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) document.body.removeChild(backdrop);
            }
        });
    });
</script>

<?php include "footer.php"; ?>
