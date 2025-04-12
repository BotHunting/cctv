<?php include "header.php"; ?>

<div class="banner">
    <img src="images/atcs.jpg" alt="ATCS" class="img-fluid">
</div>

<div class="main-content">
    <div class="container mt-5">
        <h2>CCTV 2</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lokasi 4</h5>
                        <button type="button" class="btn btn-primary open-video" data-src="https://www.youtube.com/embed/ymlm5DSyD0c">Tampilkan Video</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lokasi 5</h5>
                        <button type="button" class="btn btn-primary open-video" data-src="https://www.youtube.com/embed/22IzyDF1BvE">Tampilkan Video</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lokasi 6</h5>
                        <button type="button" class="btn btn-primary open-video" data-src="https://www.youtube.com/embed/IKqH-uo3NOY">Tampilkan Video</button>
                    </div>
                </div>
            </div>
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
                    <!-- Source video akan diatur melalui JavaScript -->
                    <iframe class="embed-responsive-item" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openButtons = document.querySelectorAll('.open-video');
        const videoModal = document.getElementById('videoModal');
        openButtons.forEach(button => {
            button.addEventListener('click', function() {
                const videoSrc = this.getAttribute('data-src');
                const videoIframe = videoModal.querySelector('iframe');
                videoIframe.src = videoSrc;
                videoModal.classList.add('show');
                videoModal.style.display = 'block';
                document.body.classList.add('modal-open');
                const backdrop = document.createElement('div');
                backdrop.classList.add('modal-backdrop', 'fade', 'show');
                document.body.appendChild(backdrop);
            });
        });
        // Tutup modal saat tombol close atau di luar modal diklik
        const closeButton = videoModal.querySelector('.btn-close');
        closeButton.addEventListener('click', function() {
            const videoIframe = videoModal.querySelector('iframe');
            videoIframe.src = '';
            videoModal.style.display = 'none';
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            document.body.removeChild(backdrop);
        });
        videoModal.addEventListener('click', function(event) {
            if (event.target === this) {
                const videoIframe = videoModal.querySelector('iframe');
                videoIframe.src = '';
                videoModal.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                document.body.removeChild(backdrop);
            }
        });
    });
</script>

<?php include "footer.php"; ?>