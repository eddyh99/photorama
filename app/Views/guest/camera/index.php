<style>
    video {
        max-width: 100%;
        max-height: 100%; /* Pastikan video tidak keluar dari container */
    }

    .camera-container {
        height: 80vh; /* Tetapkan tinggi agar kamera lebih besar */
        overflow: hidden; /* Cegah elemen meluas saat diputar */
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent; /* Agar tampilan lebih jelas */
    }

    .button-container {
        margin-top: 20px; /* Jarak tombol agar tidak tertindih */
    }
</style>

<div class="h-100 w-100" id="content-bg">
    <div class="mx-5 py-5 h-100">
        <h3 class="text-center mt-4">SELECT YOUR CAMERA</h3>
        <div class="row text-black g-4">
            <!-- Bagian kiri -->
            <div class="col-lg-6 col-md-12">
                <div class="camera-container">
                    <video id="camera1" autoplay 
                        style="transform: scaleX(-1) rotate(<?= $camera_rotation->camera1 ?? 0 ?>deg);">
                    </video>
                </div>
            </div>
            <!-- Bagian kanan -->
            <div class="col-lg-6 col-md-12">
                <div class="camera-container">
                    <video id="camera2" autoplay 
                        style="transform: scaleX(-1) rotate(<?= $camera_rotation->camera2 ?? 0 ?>deg);">
                    </video>
                </div>
            </div>
        </div>

        <!-- Row Baru untuk Tombol -->
        <div class="row button-container">
            <div class="col-lg-6 col-md-12 text-center">
                <button class="btn btn-primary fs-3 px-5" onclick="setCamera(1)">CAMERA 1</button>
            </div>
            <div class="col-lg-6 col-md-12 text-center">
                <button class="btn btn-primary fs-3 px-5" onclick="setCamera(2)">CAMERA 2</button>
            </div>
        </div>
    </div>
</div>
