<div class="w-100 vh-100 overflow-hidden" id="content-bg">
    <div class="mx-5 py-5 h-100">
        <div class="row text-black h-100">
            <!-- Bagian kiri (SELECT FRAME) -->
            <div class="col-8 d-flex flex-column justify-content-center h-100">
                <div class="mt-2 d-flex justify-content-center align-items-center h-100" style="background-color: green;">
                    <div class="text-center w-100" style="max-height: 100%;">
                        <img id="photo" class="img-fluid mt-4" style="max-height: 75vh; object-fit: contain;" src="<?= BASE_URL ?>assets/photobooth/<?= $dir ?>/photos.png">
                        <p class="text-white mt-1 fs-3">- THANK YOU -</p>
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
                        <button class="btn btn-warning">EMAIL SOFT COPY</button>
                        <a href="<?= BASE_URL ?>finish" class="btn btn-primary">DONE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
