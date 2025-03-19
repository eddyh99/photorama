<?php if (!empty(session('failed'))): ?>
    <div id="failedtoast" class="bs-toast toast toast-placement-ex m-3 fade bg-danger top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="1000">
        <div class="toast-header">
            <i class="bx bx-x me-2"></i>
            <div class="me-auto fw-semibold">Error</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?= session('failed') ?>
        </div>
    </div>
<?php endif; ?>


<div class="h-100 w-100" id="content-bg">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="container text-black h-100">
            <div class="row h-100">
                <div class="col d-none d-lg-block">
                    <!-- 1 of 3 -->
                </div>
                <div class="col-lg-6 col-md-8 d-flex justify-content-center align-items-center">
                    <div class="card h-75 w-100" style="background-image: url('<?= $image ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                        <div class="card-body text-center d-flex flex-column justify-content-end">
                            <div>
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    <!-- Button kurang -->
                                    <button class="btn btn-lg btn-primary me-5 rounded-circle" id="decrease"><i class="bx bx-minus"></i></button>
                                    <span class="fw-bold fs-2" id="count">1</span>
                                    <!-- Button tambah -->
                                    <button class="btn btn-lg btn-primary ms-5 rounded-circle" id="increase"><i class="bx bx-plus"></i></button>
                                </div>
                            </div>
                            <h2>IDR <span id="price"><?= number_format($price ?? 0, 0, ',', '.') ?></span></h2>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="d-flex flex-column justify-content-center align-items-center h-100">
                        <div class="text-center">
                            <label class="form-label text-uppercase text-black fs-3" for="upload foto">Voucher Code</label>
                            <div class="input-group input-group-merge mb-2">
                                <input
                                    autocomplete="off"
                                    type="text"
                                    class="form-control text-center"
                                    id="voc"
                                    name="voc" placeholder="(opsional)" />
                            </div>
                        </div>
                        <button id="next" class="btn btn-danger text-white fs-5 mt-3 text-center">NEXT</button>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>