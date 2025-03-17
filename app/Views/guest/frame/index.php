<style>
/* Full-Screen Overlay */
.offline-overlay {
    display: none; /* Ensure it's hidden initially */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7); /* Dark overlay */
    color: white;
    font-size: 18px;
    display: flex; /* Prevents it from being removed */
    align-items: center;
    justify-content: center;
    z-index: 9999;
    text-align: center;
    font-weight: bold;
}
</style>

<div id="offlineOverlay" class="offline-overlay">
    <p>No internet connection. Please check your network.</p>
</div>
<div class="min-vh-100 w-100 d-flex flex-column overflow-hidden" id="content-bg">
    <div class="mx-5 my-3 flex-grow-1 d-flex" style="max-height: 95vh;">
        <div class="row text-black flex-grow-1 w-100">
            <!-- Bagian kiri (SELECT FRAME) -->
            <div class="col-8 d-flex flex-column h-100">
                <div class="mt-auto fs-3 fw-bold">SELECT FRAME</div>
                <div class="flex-grow-1 mt-2 rounded" style="background-image: url(<?= $bg_container ?>); overflow-y: auto; max-height: 100%;">
                    <div class="row row-cols-4 mx-2 g-3 my-3">
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
            <div class="col-4 d-flex flex-column h-100">
                <div class="mt-auto fs-3 fw-bold">PREVIEW FRAME</div>
                <div class="bg-warning mb-4 flex-grow-1 mt-2 d-flex align-items-center justify-content-center" style="height: 500px;">
                    <img class="px-5 py-5" id="preview-frame" style="max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                </div>
                <div class="d-grid">
                    <button id="start" class="btn btn-danger fs-3">START PHOTO</button>
                </div>
            </div>
        </div>
    </div>
</div>