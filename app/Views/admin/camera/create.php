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

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-1">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <a href="<?= BASE_URL ?>admin/camera" class="me-2">
                            <i class="bx bx-chevron-left fs-2"></i>
                            Back
                        </a>
                        <h5 class="mb-1">Tambah Posisi Camera</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>admin/camera/store" method="POST">
                            <div class="mb-3">
                                <label class="form-label" for="cabang_id">Cabang</label>
                                <div class="input-group input-group-merge">
                                    <select name="cabang_id" class="form-control text-center" required>
                                        <option value="" selected disabled>--- Pilih Cabang ---</option>
                                        <?php foreach($cabang as $c): ?>
                                        <option value="<?= $c->id ?>"><?= $c->nama_cabang ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="camera1">Camera 1 Position</label>
                                <div class="input-group input-group-merge">
                                    <select id="camera1" name="camera1" class="form-select" required>
                                        <option value="0">0°</option>
                                        <option value="90">90°</option>
                                        <option value="180">180°</option>
                                        <option value="270">270°</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="camera2">Camera 2 Position</label>
                                <div class="input-group input-group-merge">
                                    <select id="camera2" name="camera2" class="form-select" required>
                                        <option value="0">0°</option>
                                        <option value="90">90°</option>
                                        <option value="180">180°</option>
                                        <option value="270">270°</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <!-- Footer -->
    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
            <div class="mb-2 mb-md-0">
                ©
                <?= date('Y') ?>
                , made with
                <a href="#" target="_blank" class="footer-link fw-bolder">Softwarebali.com</a>
            </div>
        </div>
    </footer>
    <!-- / Footer -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

</div>