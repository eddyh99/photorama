<style>
    #canvas {
    position: relative;
    width: 600px;
    height: 400px;
    border: 2px solid black;
    background-size: cover;
    padding: 0;
    box-sizing: border-box; /* Changed to border-box for better sizing */
    margin: 0 auto;
    overflow: hidden; /* Prevent handles from overflowing */
}

.frame-area {
    position: absolute;
    border: 2px dashed red;
    min-width: 50px;
    min-height: 50px;
    background: rgba(255, 0, 0, 0.2);
    transform-origin: center center; /* Ensure rotation happens around the center */
    box-sizing: border-box; /* Include border in width/height calculations */
    user-select: none; /* Prevent text selection during drag/resize */
}

.resize-handle {
    position: absolute;
    width: 10px;
    height: 10px;
    background: blue;
    z-index: 1; /* Ensure handles are above the area */
}

/* Corner Handles */
.top-left {
    top: -5px;
    left: -5px;
    cursor: nwse-resize;
}

.top-right {
    top: -5px;
    right: -5px;
    cursor: nesw-resize;
}

.bottom-left {
    bottom: -5px;
    left: -5px;
    cursor: nesw-resize;
}

.bottom-right {
    bottom: -5px;
    right: -5px;
    cursor: nwse-resize;
}

/* Side Handles */
.top {
    top: -5px;
    left: 50%;
    transform: translateX(-50%);
    cursor: ns-resize;
}

.bottom {
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    cursor: ns-resize;
}

.left {
    left: -5px;
    top: 50%;
    transform: translateY(-50%);
    cursor: ew-resize;
}

.right {
    right: -5px;
    top: 50%;
    transform: translateY(-50%);
    cursor: ew-resize;
}

    </style>

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
                        <a href="<?= BASE_URL ?>admin/frame" class="me-2">
                            <i class="bx bx-chevron-left fs-2"></i>
                            Back
                        </a>
                        <h5 class="mb-1">Tambah Frame</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>admin/frame/store" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                                    <label class="form-label" for="namabarang">Cabang</label>
                                    <div class="input-group input-group-merge">
                                        <select name="cabang_id[]" class="text-center form-control cabangselect2" multiple='multiple' required>
                                            <?php foreach($cabang as $c): ?>
                                            <option value="<?= $c->id ?>"><?= $c->nama_cabang ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>    
                        <div class="row row-cols-2">
                                <div id="canvas">
                                    <img class="img-preview mw-100 mh-100">
                                </div>
                                <div>
                                    <button class="btn btn-info d-block" id="addArea" type="button">Add Area</button>
                                    <small class="text-danger my-3">* Double-tap to select the area you want to delete.</small>
                                    <button class="btn btn-danger d-block mb-3" id="clearButton" type="button">Clear All Areas </button>
                                    <button class="btn btn-warning d-block" id="duplicateArea" type="button">Duplicate Last Area</button>
                                    <small class="text-danger my-3">* Shift+Click the area you want to Duplicate.</small>
                                    <div class="row align-items-center  mb-3">
                                        <div class="col-auto">
                                            <label for="canvasbackground" class="form-label">Canvas Background</label>
                                        </div>
                                        <div class="col-3">
                                            <select id="canvasbackground" class="form-select">
                                                <option value="light">Light</option>
                                                <option value="dark">Dark</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary d-block mt-4" id="saveButton" type="button" disabled>Save Areas</button>
                                </div>
                                <div class="my-3">
                                    <label class="form-label" for="upload foto">Upload Frame</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            type="file"
                                            class="form-control"
                                            id="file"
                                            name="file"
                                            accept=".png"
                                            onchange="previewImage()" />
                                    </div>
                                </div>
                                <div class="my-3">
                                    <label class="form-label" for="name">Name</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="name"
                                            name="name" required/>
                                    </div>
                                </div>
                                <input type="hidden" id="koordinat" name="koordinat">
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