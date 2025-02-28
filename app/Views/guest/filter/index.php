<style>
    .btn-circle {
    width: 80px; /* Atur ukuran sesuai kebutuhan */
    aspect-ratio: 1/1; /* Pastikan proporsi tetap lingkaran */
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0; /* Hapus padding agar teks tidak memperbesar tombol */
    text-align: center;
    font-size: 10px; /* Sesuaikan ukuran font */
    overflow: hidden;
}

</style>

<div class="min-vh-100 w-100 overflow-hidden d-flex flex-column" id="content-bg">
    <div class="mx-5 my-3 flex-grow-1">
        <div class="row text-black h-100">
            <!-- Bagian kiri (SELECT FRAME) -->
            <div class="col-8 d-flex flex-column min-vh-0">
                <div class="flex-grow-1 mt-2 d-flex justify-content-center align-items-center" 
                     style="background-color: green; max-height: 90vh; overflow: hidden;">
                    <img id="photo" class="img-fluid d-none" 
                        src="<?= BASE_URL ?>assets/photobooth/<?= $dir ?>/photos.png"
                        style="max-width: 100%; max-height: 100%; object-fit: contain; aspect-ratio: 4/3;">
                    <canvas class="img-fluid p-4" id="canvas"
                        style="max-width: 100%; max-height: 100%; object-fit: contain;"></canvas>
                </div>
            </div>
            <!-- Bagian kanan (PREVIEW FRAME) -->
            <div class="col-4 d-flex flex-column min-vh-0">
                <div class="bg-warning mb-4 flex-grow-1 mt-2 rounded"
                     style="max-height: 80vh; overflow: hidden;">
                     <div class="d-flex flex-column align-items-center justify-content-center h-100 gap-4">
                    <h2 class="fw-bold text-white">SELECT FILTER</h2>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <button id="normal" class="btn btn-info fw-bold btn-circle">NORMAL</button>
                        <button id="grayscale" class="btn btn-info fw-bold btn-circle">GRAYSCALE</button>
                        <button id="sephia" class="btn btn-info fw-bold btn-circle">SEPIA</button>
                        <button id="polaroid" class="btn btn-info fw-bold btn-circle">POLAROID</button>
                        <button id="bnw-glam" class="btn btn-info fw-bold btn-circle">BNW GLAM</button>
                        <button id="gotham" class="btn btn-info fw-bold btn-circle">GOTHAM</button>
                        <button id="brannan" class="btn btn-info fw-bold btn-circle">BRANNAN</button>
                        <button id="xpro" class="btn btn-info fw-bold btn-circle">X-II PRO</button>
                    </div>
                </div>

                </div>
                <div class="d-grid">
                    <button id="next" class="btn btn-primary fs-3">NEXT</button>
                </div>
            </div>
        </div>
    </div>
</div>
