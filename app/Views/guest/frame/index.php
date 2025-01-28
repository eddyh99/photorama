<div class="h-100 w-100" id="content-bg">
    <div class="mx-5 py-5 h-100">
        <div class="row text-black h-100">
            <!-- Bagian kiri (SELECT FRAME) -->
            <div class="col-8 d-flex flex-column">
                <div class="mt-auto fs-3 fw-bold">SELECT FRAME</div>
                <div class="flex-grow-1 mt-2" style="background-color: green;">
                    <div class="row row-cols-4 mx-2 g-3 my-3">
                        <?php foreach ($frame as $fr): ?>
                            <div class="col">
                                <button class="btn p-0 m-0 border-0 frame" style="width: 100%;">
                                    <img src="<?= BASE_URL ?>assets/img/<?= $fr->file ?>" alt="Frame" style="width: 100%; height: 100%; object-fit: contain;">
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Bagian kanan (PREVIEW FRAME) -->
            <div class="col-4 d-flex flex-column">
                <div class="mt-auto fs-3 fw-bold">PREVIEW FRAME</div>
                <div class="bg-warning mb-4 flex-grow-1 mt-2 d-flex align-items-center justify-content-center" style="height: 500px;">
                    <img class="px-5 py-5" id="preview-frame" style="max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                </div>
                <div class="d-grid">
                    <a href="#" class="btn btn-danger fs-3">START PHOTO</a>
                </div>
            </div>
        </div>
    </div>
</div>