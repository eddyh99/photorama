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
                        <a href="<?= BASE_URL ?>admin/branch" class="me-2">
                            <i class="bx bx-chevron-left fs-2"></i>
                            Back
                        </a>
                        <h5 class="mb-1">Tambah Cabang</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>admin/branch/update/<?= $user->id ?>" method="POST" enctype="multipart/form-data">
                            <div class="row row-cols-2">
                            <div class="mb-3">
                                    <label class="form-label" for="namabarang">Nama Cabang</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" name="nama_cabang" type="text" value="<?= $user->nama_cabang ?? '' ?>"/>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="username">Username</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" name="username" type="text" value="<?= $user->username ?? '' ?>"/>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="namabarang">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" name="password" type="password"  placeholder="********"/>
                                    </div>
                                    <span class="text-sm text-danger opacity-25">*Kosongi jika tidak ingin mengubah</span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="namabarang">Konfirmasi Password</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" name="konfirmasi_password" type="password" placeholder="********"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-cols-2">
                                <div class="mb-3">
                                    <label class="form-label" for="private_key">Unggah Private Key</label>
                                    <input type="hidden" name="pk_name" value="<?= $user->private_key ?>">
                                        <input class="form-control" name="private_key" type="file" accept=".pem" />
                                        <small class="text-danger mt-1">Kosongkan jika tidak ingin mengubah</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="certificate">Unggah Sertifikat</label>
                                        <input type="hidden" name="cert_name" value="<?= $user->certificate ?>">
                                        <input class="form-control" name="certificate" type="file" accept=".txt" />
                                        <small class="text-danger mt-1">Kosongkan jika tidak ingin mengubah</small>
                                </div>
                                </div>
                            <div class="mb-3">
                                    <label class="form-label" for="printer_name">Nama Printer</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" name="printer_name" id="printer_name" type="text" value="<?= $user->printer_name ?? '' ?>"/>
                                        <button id="detect-printer" type="button" class="btn btn-danger">Detect</button>
                                    </div>
                                </div>
                            <div class="mb-3">
                                <label class="form-label" for="namabarang">Lokasi</label>
                                <div class="input-group input-group-merge">
                                    <textarea class="form-control" name="lokasi" rows="4"><?= $user->lokasi ?? '' ?></textarea>
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