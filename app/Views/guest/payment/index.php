<?php if(!empty(session('success'))): ?>
    <div id="successtoast" class="bs-toast toast toast-placement-ex m-3 fade bg-success top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="1000">
        <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">Berhasil</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?= session('success')?>
        </div>
    </div>
<?php endif;?>

<div class="h-100 w-100" id="content-bg">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="container text-black">
            <div class="row">
                <div class="col">
                    <!-- 1 of 3 -->
                </div>
                <div class="col">
                    <div class="card text-white" style="background-image: url('<?= BASE_URL ?>assets/img/<?= $bg_qris ?? 'bg-qris.png' ?>');width: 400px;height: 500px;">
                        <div class="mx-auto" style="margin-top: 140px;">
                            <!-- Alamat -->
                             <h4 class="text-center py-0" style="color: black;"><?= $cabang ?></h4>

                            <!-- Logo QRIS -->
                            <?= $qris ?>

                            <!-- Total Pembayaran -->
                            <h2 class="text-center mt-5" style="color: black;">IDR <span id="price"><?= number_format($price, 0, ',', '.') ?></span></h2>
                        </div>
                    </div>
                </div>

                <div class="col">
                </div>
            </div>
        </div>
    </div>
</div>