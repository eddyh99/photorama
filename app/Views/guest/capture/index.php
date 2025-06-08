<div class="container-fluid d-flex flex-column" id="content-bg">
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
        <div id="previewkanan" class="col-lg-12 col-md-12 d-flex justify-content-center p-3 d-none">
            <div class=" w-100 p-3 rounded d-flex flex-row flex-wrap" style="overflow-y: auto; max-height: 95vh; gap: 1rem;">
    
            <!-- LEFT: Canvas Section -->
            <div style="flex: 1 1 60%; max-width: 60%;">
                <div id="photos" class="row row-cols-2 mx-2 my-2"></div>

                <canvas id="frame" class="w-100 p-2" style="height: 90vh; max-width: 100%; object-fit: contain;" hidden></canvas>
        
                <canvas id="frame-video" class="w-100 p-2" style="max-width: 100%; object-fit: contain; aspect-ratio: 16/9;" hidden></canvas>
            </div>

            <!-- RIGHT: Buttons Section -->
            <div style="flex: 1 1 35%; max-width: 35%;" class="d-flex flex-column justify-content-center align-items-center">
            
              <!-- Retake Buttons -->
                <div id="btn-action" class="text-center w-100 mb-3 d-none">
                    <div class="<?= $retake ? '' : 'd-none' ?>">
                        <span class="d-block mb-2 text-white">Retake:</span>
                        <div id="btn-retake" class="d-flex flex-wrap justify-content-center gap-2 mb-3 p-2 pe-none"
                            style="overflow-y: auto; max-height: 20vh;"></div>
                        </div>
                    </div>
            
              <!-- Action Buttons -->
                    <div class="d-flex flex-column align-items-center gap-2 w-100">
                        <button id="select" class="btn btn-danger w-75 fs-4 py-2" disabled>SELECT</button>
                        <button id="select-filter" class="btn btn-danger w-75 fs-4 py-2" disabled>Menyiapkan...</button>
                    </div>
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
        object-fit: contain;
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
        object-fit: contain;
        border: 2px solid #ccc;
        border-radius: 5px;
    }

    #mergedPhoto,
    #recordedVideo {
        width: 100%;
        max-height: 300px;
    }
</style>