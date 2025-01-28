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
            <div class="col-12 order-0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="row form-group">
                                    <label class="col-form-label col-sm-1">Diskon</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="diskon" id="diskon" class="form-control" placeholder="Rp">
                                    </div>
                                    <label class="col-form-label col-sm-1">Expired</label>
                                    <div class="col-3">
                                    <input type="text" class="form-control" name="tgl" id="tgl" value="<?=date("Y-m-d")?>" />
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary" id="generate">Generate</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-12 mb-4 order-1">
                    <div class="card border-expat w-100">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-4">List Background</h5>
                            <table id="table_list_bg" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Tampilan</th>
                                        <th>Background</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> -->
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