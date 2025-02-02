<div class="h-100 w-100" id="content-bg">
    <div class="mx-5 py-5 h-100">
        <div class="row text-black h-100">
            <!-- Bagian kiri (SELECT FRAME) -->
            <div class="col-8 d-flex flex-column">
                <div class="flex-grow-1 mt-2 d-flex justify-content-center align-items-center" style="background-color: green;">
                        <img id="photo" class="img-fluid px-4" src="<?= BASE_URL ?>assets/photobooth/<?= $dir ?>/my-photo.png" alt="">
                </div>
            </div>
            <!-- Bagian kanan (PREVIEW FRAME) -->
            <div class="col-4 d-flex flex-column">
                <div class="bg-warning mb-4 flex-grow-1 mt-2 rounded">
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 gap-5">
                        <h2 class="fw-bold text-white">SELECT FILTER</h2>
                        <button class="btn btn-info w-75 py-3 fw-bold" onclick="normal()">NORMAL</button>
                        <button class="btn btn-info w-75 py-3 fw-bold" onclick="grayScale()">GRAYSCALE</button>
                        <button class="btn btn-info w-75 py-3 fw-bold">SEPHIA</button>
                        <button class="btn btn-info w-75 py-3 fw-bold">POLAROID</button>
                    </div>
                </div>
                <div class="d-grid">
                    <a href="<?= BASE_URL ?>print/<?= base64_encode($dir ) ?>" class="btn btn-primary fs-3">NEXT</a>
                </div>
            </div>
        </div>
    </div>
</div>