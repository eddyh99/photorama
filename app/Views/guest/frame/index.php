</style>

<div class="min-vh-100 w-100 d-flex flex-column overflow-hidden" id="content-bg">
    <h2 class="text-center mt-3 mb-0">&nbsp;</h2>
    <div class="mx-5 my-3 flex-grow-1 d-flex flex-column flex-md-row" style="max-height: 95vh;">
        <div class="row text-black flex-grow-1 w-100">
            <!-- Bagian kiri (SELECT FRAME) -->
            <div class="col-md-12 col-lg-8 d-flex flex-column h-lg-100 h-md-50">
                <!--<div class="mt-auto fs-3 fw-bold">SELECT FRAME</div>-->
                <div class="flex-grow-1 mt-2 rounded" style="background-image: url(<?= $bg_container ?>); overflow-y: auto; max-height: 100%;">
                    <div class="row row-cols-2 row-cols-md-4 mx-2 g-3 my-3">
                        <?php foreach ($frame as $fr): ?>
                            <div class="col">
                                <button class="btn p-0 m-0 border-0 frame position-relative" style="width: 100%;">
                                    <img src="<?= BASE_URL ?>assets/img/<?= $fr->file ?>" data-frame="<?= rawurlencode($fr->file) ?>" alt="Frame" id="<?= $fr->id ?>" style="width: 100%; height: 100%; object-fit: contain;" data-coordinates='<?= json_encode($fr->coordinates) ?>'>
                                    <canvas class="position-absolute top-0 start-0 w-100 h-100" 
                                    id="canvas-<?= $fr->id ?>"></canvas>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Bagian kanan (PREVIEW FRAME) -->
            <div class="col-md-12 col-lg-4 d-flex flex-column mt-4 mt-md-0">
                <!--<div class="mt-auto fs-3 fw-bold">PREVIEW FRAME</div>-->
                <div class="mb-4 flex-grow-1 mt-2 d-flex align-items-center justify-content-center" style="height: 500px;">
                    <img class="px-5 py-5" id="preview-frame" style="max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                    <canvas id="preview-canvas" class="position-absolute"></canvas>
                </div>
                <div class="d-grid">
                    <button id="start" class="btn btn-danger fs-3">START PHOTO</button>
                </div>
            </div>
        </div>
    </div>
</div>