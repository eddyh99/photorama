<script>
    const selectedPhotos = [];
    const video = document.getElementById('webcam');
    const overlayCanvas = document.getElementById('overlay');
    const countdownOverlay = document.getElementById('countdown');
    const startRecordButton = document.getElementById('startRecord');
    const photosContainer = document.getElementById('photos');
    const mergedPhotoCanvas = document.getElementById('mergedPhoto');
    const recordedVideo = document.getElementById('recordedVideo');
    const frameCanvas = document.getElementById('frame');

    const frameImageSrc = "<?= BASE_URL ?>assets/img/frame/nataru1738035603.png"; // Path to your frame image
    const frameImage = new Image();
    frameImage.src = frameImageSrc;

    const capturedPhotos = [];
    let pictureCount = 0;
    let mediaRecorder, recordedChunks;

    // Access the webcam
    async function startWebcam() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
            });
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
                        // context.drawImage(frameImage, 0, 0, overlayCanvas.width, overlayCanvas.height);
                        requestAnimationFrame(renderFrame);
                    }
                }
                renderFrame();
                mediaRecorder.start();
                startPictureCountdown();
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
                const blob = new Blob(recordedChunks, {
                    type: 'video/webm'
                });
                recordedVideo.src = URL.createObjectURL(blob);
            };
        } catch (error) {
            console.error('Error accessing webcam: ', error);
        }
    }

    // Countdown and capture photo
    async function startPictureCountdown() {
        if (pictureCount < 2) {
            pictureCount += 1;

            let countdown = 1;

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
                    // snapshotContext.drawImage(frameImage, 0, 0, snapshotCanvas.width, snapshotCanvas.height);

                    const photo = new Image();
                    photo.src = snapshotCanvas.toDataURL('image/png');
                    capturedPhotos.push(photo.src);
                    photosContainer.innerHTML += `
                    <div class="col px-2">
                        <img src="${photo.src}" class="img-fluid rounded shadow-sm mx-0 selected" onclick="selectPhoto(this)">
                    </div>
                `;

                    // Prepare for the next photo
                    if (pictureCount < 2) {
                        setTimeout(startPictureCountdown, 2000);
                    } else {
                        mediaRecorder.stop();
                    }
                }
            }, 1000);
        }
    }

    $(function() {
        const bg = <?= $background ? json_encode($background) : 'null'; ?>;
        $("#content-bg").css("background-image", bg ? `url('${bg}')` : 'none');
        startWebcam();
    });

    function selectPhoto(img) {
        const imgIndex = selectedPhotos.findIndex(photo => photo === img);
        if (imgIndex === -1) {
            selectedPhotos.push(img);
            img.classList.add('border-5', 'border-primary', 'shadow-lg');
        } else {
            selectedPhotos.splice(imgIndex, 1);
            img.classList.remove('border-5', 'border-primary', 'shadow-lg');
        }

        console.log(selectedPhotos);
    }

    $("#select").on('click', function() {
        $("#photos").hide(); // Menyembunyikan elemen foto

        const ctx = frameCanvas.getContext('2d');
        frameCanvas.width = frameImage.width;
        frameCanvas.height = frameImage.height;

        // koordinat
        const positions = [{
                x: 50,
                y: 100
            },
            {
                x: 132,
                y: 825
            }
        ];

        selectedPhotos.forEach((photo, index) => {
            const selectedImage = new Image();
            selectedImage.src = photo.src;

            selectedImage.onload = function() {
                const selectedImageX = positions[index].x;
                const selectedImageY = positions[index].y;

                ctx.drawImage(selectedImage, selectedImageX, selectedImageY, selectedImage.width, selectedImage.height);
            };
        });

        const frame = new Image();
        frame.src = frameImageSrc;

        frame.onload = function() {
            ctx.drawImage(frame, 0, 0, frame.width, frame.height); // Gambar frame di depan gambar
        };
    });
</script>