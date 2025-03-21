<style>
    video {
        transform: scaleX(-1);
    }
</style>
<div class="h-100 w-100" id="content-bg">
    <div class="mx-5 py-5 h-100">
        <h2 class="text-center">SELECT YOUR CAMERA</h2>
        <div class="row text-black h-75 mt-4">
            <!-- Bagian kiri  -->
            <div class="col-6 d-flex flex-column">
                <div class="bg-black mb-4 flex-grow-1 d-flex align-items-center justify-content-center">
                    <!-- Kamera 1 -->
                    <video id="camera1" width="100%" height="100%" autoplay></video>
                </div>
                <div class="d-grid">
                    <btn class="btn btn-primary fs-3 mx-5 " onclick="setCamera(1)">CAMERA 1</btn>
                </div>
            </div>
            <!-- Bagian kanan -->
            <div class="col-6 d-flex flex-column">
                <div class="bg-black mb-4 flex-grow-1 d-flex align-items-center justify-content-center">
                    <!-- Kamera 2 -->
                    <video id="camera2" width="100%" height="100%" autoplay></video>
                </div>
                <div class="d-grid">
                <btn class="btn btn-primary fs-3 mx-5 " onclick="setCamera(2)">CAMERA 2</btn>
                </div>
            </div>
        </div>
    </div>
</div>