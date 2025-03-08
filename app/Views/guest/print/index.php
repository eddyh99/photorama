<div class="w-100 vh-100 overflow-hidden" id="content-bg">
    <div class="mx-5 py-5 h-100">
        <div class="row text-black h-100">
            <!-- Bagian kiri (SELECT FRAME) -->
            <div class="col-8 d-flex flex-column justify-content-center h-100">
                <div class="mt-2 d-flex justify-content-center align-items-center h-100" style="background-image: url(<?= $bg_container ?>); ">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-6 text-center">
                                <img id="photo" class="img-fluid mt-4" style="max-height: 75vh; object-fit: contain;"
                                    src="<?= BASE_URL ?>assets/photobooth/<?= $dir ?>/photos.jpg"
                                    alt="Photo Booth 1">
                            </div>
                            <div class="col-md-6 text-center">
                                <canvas id="frame-video" class="img-fluid mt-4" style="max-height: 75vh; object-fit: contain;"></canvas>
                            </div>
                        </div>
                        <p class="text-white text-center mt-3 fs-3">- THANK YOU -</p>
                    </div>
                </div>

            </div>
            <!-- Bagian kanan (PREVIEW FRAME) -->
            <div class="col-4 d-flex flex-column h-100">
                <div class="mb-4 flex-grow-1 mt-2 rounded d-flex flex-column align-items-center justify-content-center">
                    <div class="bg-white p-4 mb-2">
                        <?= $qrcode ?>
                    </div>
                    <p class="text-white">SCAN QR CODE</p>
                    <p class="text-white">OR</p>
                    <div class="d-grid gap-2">
                        <!-- <button class="btn btn-warning">EMAIL SOFT COPY</button> -->
                        <button id="print" class="btn btn-info" hidden>PRINT</button>
                        <button id="submit" href="#" class="btn btn-primary" onclick="save()" disabled>DONE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>