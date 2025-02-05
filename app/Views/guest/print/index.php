<div class="h-100 w-100" id="content-bg">
    <div class="mx-5 py-5 h-100">
        <div class="row text-black h-100">
            <!-- Bagian kiri (SELECT FRAME) -->
            <div class="col-8 d-flex flex-column">
                <div class="flex-grow-1 mt-2 d-flex-column justify-content-center align-items-center" style="background-color: green;">
                <img id="photo" class="img-fluid px-4 py-4" src="<?= BASE_URL ?>assets/photobooth/<?= $dir ?>/my-photo.png">
                <p class="text-white text-center">THANK YOU</p>
                </div>
            </div>
            <!-- Bagian kanan (PREVIEW FRAME) -->
            <div class="col-4 d-flex flex-column">
                <div class="mb-4 flex-grow-1 mt-2 rounded" >
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