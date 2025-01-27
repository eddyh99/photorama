<div class="h-100 w-100" id="content-bg">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="container text-black">
            <div class="row">
                <div class="col">
                    <!-- 1 of 3 -->
                </div>
                <div class="col-6">
                    <div class="card text-white" style="background-color: blue;">
                        <div class="card-body text-center px-0 py-0">
                            <!-- Judul Card -->
                            <h2 class="card-title text-uppercase fw-bold py-4" style="background-color: yellow;">Scan to pay</h2>

                            <!-- Logo QRIS -->
                            <img class="d-block mx-auto mb-3" src="<?= BASE_URL ?>assets/img/qris-logo.png" alt="Logo Qris" style="max-width: 40%;">

                            <!-- Logo QRIS -->
                            <img class="d-block mx-auto w-50" src="<?= BASE_URL ?>assets/img/qris.png" alt="Order">

                            <!-- Total Pembayaran -->
                            <p class="fs-5 mt-2">PAYMENT TOTAL</p>
                            <h2 class="text-white">IDR <span id="price"><?= number_format($price, 0, ',', '.') ?></span></h2>

                            <!-- Partner QRIS -->
                            <img class="mx-0" src="<?= BASE_URL ?>assets/img/qris-partner.jpeg" alt="" style="max-width: 100%; height: auto;">
                        </div>
                    </div>
                </div>

                <div class="col">
                </div>
            </div>
        </div>
    </div>
</div>