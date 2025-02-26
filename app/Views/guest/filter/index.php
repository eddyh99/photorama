<div class="h-100 w-100" id="content-bg">
    <div class="mx-5 py-5 h-100">
        <div class="row text-black h-100">
            <!-- Bagian kiri (SELECT FRAME) -->
            <div class="col-8 d-flex flex-column">
                <div class="flex-grow-1 mt-2 d-flex justify-content-center align-items-center" style="background-color: green;">
                    <img id="photo" class="img-fluid d-none"
                        src="<?= BASE_URL ?>assets/photobooth/<?= $dir ?>/photos.png">
                    <canvas class="img-fluid px-4" id="canvas"></canvas>
                </div>
            </div>
            <!-- Bagian kanan (PREVIEW FRAME) -->
            <div class="col-4 d-flex flex-column">
                <div class="bg-warning mb-4 flex-grow-1 mt-2 rounded">
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 gap-5">
                        <h2 class="fw-bold text-white">SELECT FILTER</h2>
                        <button id="normal" class="btn btn-info w-75 py-3 fw-bold">NORMAL</button>
                        <button id="grayscale" class="btn btn-info w-75 py-3 fw-bold">GRAYSCALE</button>
                        <button id="sephia" class="btn btn-info w-75 py-3 fw-bold">SEPHIA</button>
                        <button id="polaroid" class="btn btn-info w-75 py-3 fw-bold">POLAROID</button>
                    </div>
                </div>
                <div class="d-grid">
                    <button id="next" class="btn btn-primary fs-3">NEXT</button>
                </div>
            </div>
        </div>
    </div>
</div>