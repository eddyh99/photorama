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
                        <a href="<?= BASE_URL ?>admin/background" class="me-2">
                            <i class="bx bx-chevron-left fs-2"></i>
                            Back
                        </a>
                        <h5 class="mb-1">Tambah Background</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>admin/background/update/<?= $id_cabang ?>" method="POST" enctype="multipart/form-data">
                            <img class="img-preview img-fluid col-sm-5 d-block">
                            <div class="mb-3">
                                <input type="hidden" name="cabang" value="<?= $nama_cabang ?>">
                                <label class="form-label" for="namabarang">Cabang</label>
                                <div class="input-group input-group-merge">
                                    <select name="idcabang" class="form-control text-center" readonly>
                                        <option value="<?= $id_cabang ?>" selected><?= $nama_cabang ?></option>
                                    </select>
                                </div>
                                <p class="text-danger text-sm text-center mt-2">Kosongi background yang tidak ingin diubah</p>
                            </div>
                            <div class="row row-cols-2">
                                <?php foreach($backgrounds as $bg): ?>
                                <div class="mb-3">
                                    <label class="form-label" for="upload foto"><?= str_replace('_', ' ', $bg->display) ?></label>
                                    <div classsz="input-group input-group-merge">
                                        <input
                                            type="file"
                                            class="form-control"
                                            id="file"
                                            name="<?= $bg->display ?>"
                                            accept=".png"
                                            onchange="previewImage(this)" />
                                    </div>
                                    <?php if($bg->file != 'default'): ?>
                                        <a href="<?= BASE_URL ?>assets/img/<?= $bg->file ?>" target="_blank"><small><u>Lihat Background</u></small></a>
                                    <?php else: ?>
                                        <small class="text-danger">No background provided</small>
                                    <?php endif ?>
                                </div>
                            <?php endforeach ?>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
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