<div class="container-fluid vh-100 d-flex flex-column" id="content-bg">
    <div class="row flex-grow-1 text-black">
        <!-- Bagian kiri (SELECT FRAME) -->
        <div class="col-md-8 d-flex flex-column p-3">
            <div class="video-container">
                <video id="webcam" autoplay playsinline muted></video>
                <canvas id="overlay"></canvas>
                <div id="countdown" class="countdown-overlay"></div>
            </div>
        </div>

        <!-- Bagian kanan (PREVIEW FRAME) -->
        <div class="col-md-4 d-flex flex-column p-3">
            <div class="bg-warning overflow-auto" style="height: 85vh">
                <div class="row row-cols-2 mx-2 my-2" id="photos"></div>
                <canvas id="frame" class="w-100 p-2" hidden></canvas>
            </div>
            <div class="mt-4">
                <button id="select" class="btn btn-danger w-100 fs-4 py-2" disabled>SELECT</button>
                <button id="select-filter" class="btn btn-primary w-100 fs-4 py-2" hidden>SELECT FILTER</button>
            </div>
        </div>
    </div>
    <div id="recordedVideoContainer" hidden></div>
</div>
</div>

<style>
    .video-container {
        position: relative;
        height: 95%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 10px;
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