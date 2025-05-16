<?php
include "header.php";
require 'koneksi.php';

$error = "";

// Proses ketika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lokasi = trim($_POST['lokasi']);
    $link_embed = trim($_POST['link_embed']);

    if (!empty($lokasi) && !empty($link_embed)) {
        $stmt = $conn->prepare("INSERT INTO video_cctv (lokasi, link_embed) VALUES (?, ?)");
        $stmt->bind_param("ss", $lokasi, $link_embed);
        if ($stmt->execute()) {
            echo "<script>alert('Video berhasil ditambahkan!'); window.location='live.php';</script>";
            exit;
        } else {
            $error = "Gagal menambahkan video.";
        }
    } else {
        $error = "Harap isi semua kolom.";
    }
}
?>

<div class="container mt-5">
    <h2>Tambah Video CCTV ATCS</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="link_embed" class="form-label">Link Embed Video</label>
            <input type="url" name="link_embed" id="link_embed" class="form-control" placeholder="https://www.youtube.com/embed/..." required>
            <div class="form-text">Bisa berupa link YouTube embed, link .m3u8, atau link streaming lainnya.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Preview Video</label>
            <div id="video-preview" class="ratio ratio-16x9 border rounded bg-light d-flex align-items-center justify-content-center text-muted">
                <span>Preview akan tampil di sini</span>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="live.php" class="btn btn-secondary">Batal</a>
    </form>
    <br>
</div>

<!-- Include HLS.js untuk preview m3u8 -->
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    function updatePreview() {
        const url = document.getElementById('link_embed').value;
        const preview = document.getElementById('video-preview');
        preview.innerHTML = '';

        if (!url) {
            preview.innerHTML = '<span class="text-muted">Preview akan tampil di sini</span>';
            return;
        }

        if (url.includes("youtube.com") || url.includes("youtu.be")) {
            const embedUrl = url.replace('watch?v=', 'embed/');
            preview.innerHTML = `<iframe src="${embedUrl}" frameborder="0" allowfullscreen class="w-100 h-100"></iframe>`;
        } else if (url.endsWith(".m3u8")) {
            const video = document.createElement('video');
            video.id = 'hls-video';
            video.controls = true;
            video.autoplay = true;
            video.className = 'w-100 h-100';
            preview.appendChild(video);

            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(url);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED, function () {
                    video.play();
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = url;
                video.addEventListener('loadedmetadata', function () {
                    video.play();
                });
            } else {
                preview.innerHTML = '<p class="text-danger">Browser tidak mendukung format .m3u8</p>';
            }
        } else {
            preview.innerHTML = `<iframe src="${url}" frameborder="0" allowfullscreen class="w-100 h-100"></iframe>`;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        updatePreview();
        document.getElementById('link_embed').addEventListener('input', updatePreview);
    });
</script>

<?php include "footer.php"; ?>
