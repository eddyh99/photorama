<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video with Frame and Merged Captures</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        .video-container {
            position: relative;
            display: inline-block;
        }
        video, canvas {
            width: 640px;
            height: 480px;
            border: 1px solid #ccc;
        }
        canvas#overlay {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 2;
        }
        .countdown-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 640px;
            height: 480px;
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
            margin: 10px;
            border: 2px solid #ccc;
        }
        #mergedPhoto {
            display: block;
            margin: 20px auto;
            border: 2px solid #ccc;
        }
        button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>Video Recording with Photo Merging</h1>
    <div class="video-container">
        <video id="webcam" autoplay playsinline muted></video>
        <canvas id="overlay"></canvas>
        <div id="countdown" class="countdown-overlay"></div>
    </div>
    <div>
        <button id="startRecord">Start Recording</button>
    </div>
    <h2>Captured Photos</h2>
    <div id="photos"></div>
    <h2>Merged Photo</h2>
    <canvas id="mergedPhoto"></canvas>
    <h2>Recorded Video</h2>
    <video id="recordedVideo" controls></video>

    <script>
        const video = document.getElementById('webcam');
        const overlayCanvas = document.getElementById('overlay');
        const countdownOverlay = document.getElementById('countdown');
        const startRecordButton = document.getElementById('startRecord');
        const photosContainer = document.getElementById('photos');
        const mergedPhotoCanvas = document.getElementById('mergedPhoto');
        const recordedVideo = document.getElementById('recordedVideo');

        const frameImageSrc = 'frame.png'; // Path to your frame image
        const frameImage = new Image();
        frameImage.src = frameImageSrc;

        const capturedPhotos = [];
        let pictureCount = 0;
        let mediaRecorder, recordedChunks;

        // Access the webcam
        async function startWebcam() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                video.srcObject = stream;

                // Overlay frame on video in real-time
                const context = overlayCanvas.getContext('2d');
                overlayCanvas.width = video.videoWidth || 640;
                overlayCanvas.height = video.videoHeight || 480;

                video.addEventListener('play', () => {
                    function renderFrame() {
                        if (!video.paused && !video.ended) {
                            context.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
                            context.drawImage(video, 0, 0, overlayCanvas.width, overlayCanvas.height);
                            context.drawImage(frameImage, 0, 0, overlayCanvas.width, overlayCanvas.height);
                            requestAnimationFrame(renderFrame);
                        }
                    }
                    renderFrame();
                });

                // Start video recording
                recordedChunks = [];
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.ondataavailable = event => {
                    if (event.data.size > 0) {
                        recordedChunks.push(event.data);
                    }
                };
                mediaRecorder.onstop = () => {
                    const blob = new Blob(recordedChunks, { type: 'video/webm' });
                    recordedVideo.src = URL.createObjectURL(blob);
                };
            } catch (error) {
                console.error('Error accessing webcam: ', error);
            }
        }

        // Countdown and capture photo
        async function startPictureCountdown() {
            if (pictureCount < 3) {
                pictureCount += 1;

                let countdown = 3;

                // Show the countdown overlay and set the initial value
                countdownOverlay.style.display = 'flex';
                countdownOverlay.textContent = countdown;

                const countdownInterval = setInterval(() => {
                    countdown -= 1;

                    if (countdown > 0) {
                        countdownOverlay.textContent = countdown;
                    } else {
                        clearInterval(countdownInterval);

                        // Hide the countdown overlay
                        countdownOverlay.style.display = 'none';

                        // Capture photo
                        const snapshotCanvas = document.createElement('canvas');
                        snapshotCanvas.width = video.videoWidth || 640;
                        snapshotCanvas.height = video.videoHeight || 480;

                        const snapshotContext = snapshotCanvas.getContext('2d');
                        snapshotContext.drawImage(video, 0, 0, snapshotCanvas.width, snapshotCanvas.height);
                        snapshotContext.drawImage(frameImage, 0, 0, snapshotCanvas.width, snapshotCanvas.height);

                        const photo = new Image();
                        photo.src = snapshotCanvas.toDataURL('image/png');
                        capturedPhotos.push(photo.src);
                        photosContainer.appendChild(photo);

                        // Prepare for the next photo
                        if (pictureCount < 3) {
                            setTimeout(startPictureCountdown, 2000);
                        } else {
                            mergeCapturedPhotos();
                            mediaRecorder.stop();
                        }
                    }
                }, 1000);
            }
        }

        // Merge captured photos into a single vertical image
        function mergeCapturedPhotos() {
            const mergedContext = mergedPhotoCanvas.getContext('2d');
            const photoWidth = 640;
            const photoHeight = 480;
            mergedPhotoCanvas.width = photoWidth;
            mergedPhotoCanvas.height = photoHeight * capturedPhotos.length;

            capturedPhotos.forEach((photoSrc, index) => {
                const img = new Image();
                img.src = photoSrc;
                img.onload = () => {
                    mergedContext.drawImage(img, 0, index * photoHeight, photoWidth, photoHeight);
                };
            });
        }

        // Start the recording and countdown
        startRecordButton.addEventListener('click', () => {
            pictureCount = 0;
            mediaRecorder.start();
            startPictureCountdown();
        });

        // Initialize webcam and frame overlay
        startWebcam();
    </script>
</body>
</html>
