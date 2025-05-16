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

                            <div class="mb-3">
                                <?= getVideoPreview($video['link_embed']) ?>
                            </div>

                            <?php if ($logged_in): ?>
                                <div class="d-grid gap-2">
                                    <a href="edit_video.php?id=<?= $video['id'] ?>" class="btn btn-warning">Edit Link</a>
                                    <a href="live.php?hapus=<?= $video['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus video ini?')">Hapus</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php
function getVideoPreview($src) {
    if (!$src) return '<div class="text-danger">Link tidak tersedia</div>';

    $safeSrc = htmlspecialchars($src);

    // YouTube
    if (preg_match('/(youtube\.com|youtu\.be)/', $src)) {
        $embedLink = preg_replace('/watch\?v=/', 'embed/', $src);
        return "<iframe class='w-100' height='200' src='{$embedLink}' allowfullscreen allow='autoplay'></iframe>";
    }

    // m3u8 HLS (via hls.js atau player iframe jika punya server player)
    if (strpos($src, '.m3u8') !== false) {
        return "
        <video class='w-100' height='200' autoplay muted controls playsinline id='video_" . md5($src) . "'></video>
        <script src='https://cdn.jsdelivr.net/npm/hls.js@latest'></script>
        <script>
            const videoEl = document.getElementById('video_" . md5($src) . "');
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource('{$safeSrc}');
                hls.attachMedia(videoEl);
            } else if (videoEl.canPlayType('application/vnd.apple.mpegurl')) {
                videoEl.src = '{$safeSrc}';
            }
        </script>";
    }

    // MP4 / WEBM
    if (preg_match('/\.(mp4|webm)$/i', $src)) {
        $type = pathinfo($src, PATHINFO_EXTENSION);
        return "<video class='w-100' height='200' controls autoplay muted>
                    <source src='{$safeSrc}' type='video/{$type}'>
                    Browser tidak mendukung video ini.
                </video>";
    }

    // HTML custom player (play.html, index.html, dll)
    if (preg_match('/\.html?$/i', $src) || strpos($src, 'play.html') !== false) {
        return "<iframe class='w-100' height='200' src='{$safeSrc}' allow='autoplay; fullscreen'></iframe>";
    }

    // WebRTC via iframe (misalnya wss:// atau gateway Janus/MediaSoup)
    if (strpos($src, 'webrtc') !== false || strpos($src, 'wss://') !== false || strpos($src, 'rtc') !== false) {
        return "<iframe class='w-100' height='200' src='{$safeSrc}' allow='camera; microphone; autoplay; fullscreen'></iframe>";
    }

    // RTSP link (biasanya perlu player gateway)
    if (strpos($src, 'rtsp://') !== false) {
        return "<div class='text-warning'>RTSP tidak bisa langsung diputar di browser. Gunakan gateway WebRTC/RTMP seperti Ant Media Server, Wowza, Janus, atau restream ke HLS/m3u8.</div>";
    }

    // Default (iframe fallback)
    return "<iframe class='w-100' height='200' src='{$safeSrc}' allowfullscreen allow='autoplay'></iframe>";
}
?>

<?php include "footer.php"; ?>
