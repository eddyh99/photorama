    <div id="failedtoast" class="bs-toast toast toast-placement-ex m-3 fade bg-danger top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="1000">
        <div class="toast-header">
            <i class="bx bx-x me-2"></i>
            <div class="me-auto fw-semibold">Error</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
        </div>
    </div>

    <div id="successtoast" class="bs-toast toast toast-placement-ex m-3 fade bg-success top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="1000">
        <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">Berhasil</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
        </div>
    </div>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-lg-12 mb-4 order-1">
                    <div class="card border-expat w-100">
                        <div class="card-body">
                            <div class="row row-4 form-group">
                                <label class="col-form-label col-sm-1">Diskon</label>
                                <div class="col-sm-2">
                                    <input type="number" name="diskon" id="diskon" class="form-control" placeholder="Rp">
                                </div>
                                <label class="col-form-label col-sm-1">Cabang:</label>
                                <div class="col-2">
                                    <select class="form-control" name="cabang" id="cabang">
                                        <option value="">Semua Cabang</option>
                                        <?php foreach($cabang as $c): ?>
                                            <option value="<?= $c->id ?>"><?= $c->nama_cabang ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <label class="col-form-label col-sm-1">Expired</label>
                                <div class="col-2">
                                    <input type="text" class="form-control" name="tgl" id="tgl" value="<?= date("Y-m-d") ?>" />
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-primary w-100" id="generate" disabled>Generate Voucher</button>
                                </div>
                            </div>
                            <h5 class="card-title fw-semibold my-4">List Voucher</h5>
                            <table id="table_list" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Kode Voucher</th>
                                        <th>Cabang</th>
                                        <th>Expired</th>
                                        <th>Diskon</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
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