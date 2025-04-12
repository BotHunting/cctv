<?php
include "header.php";
require 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID video tidak valid'); window.location='live.php';</script>";
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM video_cctv WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Video tidak ditemukan'); window.location='live.php';</script>";
    exit;
}

$video = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lokasi = trim($_POST['lokasi']);
    $link_embed = trim($_POST['link_embed']);

    if (!empty($lokasi) && !empty($link_embed)) {
        $stmt = $conn->prepare("UPDATE video_cctv SET lokasi = ?, link_embed = ? WHERE id = ?");
        $stmt->bind_param("ssi", $lokasi, $link_embed, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Video berhasil diperbarui!'); window.location='live.php';</script>";
            exit;
        } else {
            $error = "Gagal memperbarui video.";
        }
    } else {
        $error = "Harap isi semua kolom.";
    }
}
?>

<div class="container mt-5">
    <h2>Edit Video CCTV ATCS</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control"
                value="<?= htmlspecialchars($video['lokasi']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="link_embed" class="form-label">Link Embed / Streaming</label>
            <input type="url" name="link_embed" id="link_embed" class="form-control"
                value="<?= htmlspecialchars($video['link_embed']) ?>"
                placeholder="https://www.youtube.com/embed/..., atau .m3u8" required>
            <div class="form-text">
                Contoh:<br>
                • YouTube: <code>https://www.youtube.com/embed/abc123</code><br>
                • HLS / Streaming: <code>https://domain.com/stream/video.m3u8</code>
            </div>
        </div>

        <div class="mb-4" id="preview-container">
            <label class="form-label">Preview Video</label>
            <div id="video-preview" class="ratio ratio-16x9">
                <!-- Preview akan diganti lewat JS -->
            </div>
        </div>

        <div>
            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="live.php" class="btn btn-secondary">Batal</a>
        </div>
        <br>
    </form>

</div>

<script>
    function isYouTube(url) {
        return url.includes("youtube.com") || url.includes("youtu.be");
    }

    function isM3U8(url) {
        return url.endsWith(".m3u8");
    }

    function updatePreview() {
        const url = document.getElementById('link_embed').value;
        const preview = document.getElementById('video-preview');

        if (!url) {
            preview.innerHTML = '';
            return;
        }

        if (url.includes("youtube.com") || url.includes("youtu.be")) {
            preview.innerHTML = `<iframe class="embed-responsive-item" src="${url}" frameborder="0" allowfullscreen></iframe>`;
        } else if (url.endsWith(".m3u8")) {
            preview.innerHTML = `
            <video id="hls-video" controls autoplay class="w-100 h-100"></video>
            <script>
                if (Hls.isSupported()) {
                    var video = document.getElementById('hls-video');
                    var hls = new Hls();
                    hls.loadSource("${url}");
                    hls.attachMedia(video);
                    hls.on(Hls.Events.MANIFEST_PARSED, function () {
                        video.play();
                    });
                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = "${url}";
                    video.addEventListener('loadedmetadata', function () {
                        video.play();
                    });
                }
            <\/script>
        `;
        } else {
            preview.innerHTML = `<iframe class="embed-responsive-item" src="${url}" frameborder="0" allowfullscreen></iframe>`;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        updatePreview(); // panggil saat load awal
        document.getElementById('link_embed').addEventListener('input', updatePreview); // panggil saat input berubah
    });


    document.addEventListener('DOMContentLoaded', function () {
        updatePreview();
        document.getElementById('link_embed').addEventListener('input', updatePreview);
    });
</script>

<!-- HLS.js untuk mendukung .m3u8 -->
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<?php include "footer.php"; ?>