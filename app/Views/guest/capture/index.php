<div class="container-fluid vh-100 d-flex flex-column" id="content-bg">
    <div class="row flex-grow-1 text-black">
        <!-- Bagian kiri (SELECT FRAME) -->
        <div id="videoarea" class="col-lg-12 col-md-12 d-flex flex-column p-3">
            <div class="video-container d-flex justify-content-center align-items-center" style="background-color: black;">
                <span class="text-center text-white">Camera Loading...</span>
                <video class="invisible" id="webcam" autoplay playsinline muted style="max-width: 100%; height: auto;"></video>
                <canvas id="overlay"></canvas>
                <div id="countdown-camera" class="countdown-overlay"></div>
            </div>
        </div>

        <!-- Bagian kanan (PREVIEW FRAME) -->
        <div id="previewkanan" class="col-lg-4 col-md-12 d-flex justify-content-center p-3 d-none">
            <div class="bg-warning w-100 p-2 rounded d-flex flex-column" style="overflow-y: auto; max-height: 95vh;">
                <div class="row row-cols-2 mx-2 my-2" id="photos"></div>

                <!-- Pastikan aspect ratio tetap dan tidak stretch -->
                <div class="d-flex justify-content-center align-items-center" style="width: 100%;">
                    <canvas id="frame" class="w-100 p-2 h-75"
                        style="max-width: 100%; object-fit: contain;" hidden>
                    </canvas>
                    <canvas id="frame-video" class="w-100 p-2"
                        style="max-width: 100%; object-fit: contain; aspect-ratio: 16/9;" hidden>
                    </canvas>
                </div>

                <!-- Area tombol retake bisa di-scroll -->
                <div id="btn-action" class="row d-flex justify-content-center text-center w-100 d-none">
                    <div class="col-12 <?= $retake ? '' : 'd-none' ?>">
                        <span class="d-block mb-2 text-white">Retake:</span>
                        <div id="btn-retake" class="d-flex flex-wrap justify-content-center gap-2 mb-3 p-2"
                            style="overflow-y: auto; max-height: 20vh;">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button id="select" class="btn btn-danger w-75 fs-4 py-2" disabled>SELECT</button>
                    <button id="select-filter" class="btn btn-primary w-75 fs-4 py-2" disabled>Menyiapkan...</button>
                </div>
            </div>
        </div>
    </div>
    <div id="recordedVideoContainer" hidden></div>
</div>



<style>
    @media (min-width: 768px) and (max-width: 991.98px) {
        #frame {
            aspect-ratio: 16 / 9;
        }
    }

    .video-container {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 10px;
    }

    .video-container span {
        position: absolute;
        z-index: 2;
        /* Pastikan di atas video */
        font-size: 1.5rem;
    }

    video {
        width: auto;
        height: auto;
        object-fit: cover;
    }

    canvas#overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 2;
    }

    .countdown-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 3;
        background: rgba(0, 0, 0, 0.3);
        color: white;
        font-size: 100px;
        font-weight: bold;
        display: none;
    }

    #photos img {
        margin: 5px;
        width: auto;
        height: auto;
        object-fit: cover;
        border: 2px solid #ccc;
        border-radius: 5px;
    }

    #mergedPhoto,
    #recordedVideo {
        width: 100%;
        max-height: 300px;
    }
</style>