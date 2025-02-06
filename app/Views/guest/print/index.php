<div class="w-100 vh-100" id="content-bg">
    <div class="mx-5 py-5 h-100">
        <div class="row text-black">
            <!-- Bagian kiri (SELECT FRAME) -->
            <div class="col-8 d-flex flex-column justify-content-center">
                <div class="mt-2 d-flex justify-content-center align-items-center h-100" style="background-color: green;">
                    <div class="text-center" style="overflow-y: auto; max-height: 100%;">
                        <img id="photo" class="img-fluid mt-4" style="max-height: 75vh; object-fit: contain;" src="<?= BASE_URL ?>assets/photobooth/<?= $dir ?>/photos.png">
                        <p class="text-white mt-4 fs-1">- THANK YOU -</p>
                    </div>
                </div>
            </div>
            <!-- Bagian kanan (PREVIEW FRAME) -->
            <div class="col-4 d-flex flex-column">
                <div class="mb-4 flex-grow-1 mt-2 rounded">
                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                        <div class="bg-white p-4 mb-2">
                            <?= $qrcode ?>
                        </div>
                        <p class="text-white">SCAN QR CODE</p>
                        <p class="text-white">OR</p>
                        <button class="btn btn-warning">EMAIL SOFT COPY</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
