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
            <div class="col-lg-8 col-md-12 d-flex flex-column">
                <!-- <div class="flex-grow-1 mt-2 d-flex justify-content-center align-items-center" 
                     style="background-image: url(<?= $bg_container ?>); max-height: 90vh; overflow: hidden;"> -->
                     <div class="flex-grow-1 mt-2 d-flex justify-content-center align-items-center" 
                     style="max-height: 90vh; overflow: hidden;">
                    <img id="photo" class="img-fluid d-none" 
                        src="<?= BASE_URL ?>assets/photobooth/<?= $dir ?>/photos.jpg"
                        style="max-width: 100%; max-height: 100%; object-fit: contain; aspect-ratio: 4/3;">
                    <canvas class="img-fluid p-4" id="canvas"
                        style="max-width: 100%; max-height: 100%; object-fit: contain;"></canvas>
                </div>
            </div>
            <!-- Bagian kanan (PREVIEW FRAME) -->
            <div class="col-lg-4 col-md-12 d-flex flex-column">
                <div class="mb-4 flex-grow-1 mt-2 rounded" style="max-height: 80vh; overflow: hidden;">
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 gap-4">
                        <!--<h2 class="fw-bold text-white">SELECT FILTER</h2>-->
                        <!-- Updated Layout: 2 columns, 4 rows -->
                        <div class="d-grid gap-3" style="grid-template-columns: repeat(2, 1fr);">
                            <button id="normal" class="btn btn-danger fw-bold btn-circle btn-lg">NORMAL</button>
                            <button id="grayscale" class="btn btn-danger fw-bold btn-circle btn-lg">GRAYSCALE</button>
                            <button id="sephia" class="btn btn-danger fw-bold btn-circle btn-lg">SEPIA</button>
                            <button id="polaroid" class="btn btn-danger fw-bold btn-circle btn-lg">POLAROID</button>
                            <button id="bnw-glam" class="btn btn-danger fw-bold btn-circle btn-lg">BNW GLAM</button>
                            <button id="gotham" class="btn btn-danger fw-bold btn-circle btn-lg">GOTHAM</button>
                            <button id="brannan" class="btn btn-danger fw-bold btn-circle btn-lg">BRANNAN</button>
                            <button id="xpro" class="btn btn-danger fw-bold btn-circle btn-lg">X-II PRO</button>
                        </div>
                    </div>
                </div>
                <div class="d-grid">
                    <button id="next" class="btn btn-danger fs-3">NEXT</button>
                </div>
            </div>
            
        </div>
    </div>
</div>
