<script>
    const camera = sessionStorage.getItem('camera') || null;
    const selectedPhotos = [];
    const video = document.getElementById('webcam');
    const overlayCanvas = document.getElementById('overlay');
    const countdownOverlay = document.getElementById('countdown-camera');
    const photosContainer = document.getElementById('photos');
    const recordedVideoContainer = document.getElementById('recordedVideoContainer');
    const frameCanvas = document.getElementById('frame');
    const cameraSound = new Audio('<?= BASE_URL ?>assets/audio/camera-13695.mp3');
    const listAudio = [
        "get-ready.mp3",
        "smile.mp3",
        "say-cheese.mp3",
        "last-one.mp3",
        "next-pose.mp3"
    ];

    const frameImageSrc = "<?= BASE_URL ?>assets/img/<?= $frame[0]->file ?>"; // Path to frame image
    const frameImage = new Image();
    frameImage.src = frameImageSrc;

    const capturedPhotos = [];
    const capturedVideos = [];
    let blobResultImage;
    let pictureCount = 0;
    let mediaRecorder, recordedChunks;
    const positions = <?= json_encode($frame) ?> || [];
    // Access the webcam
    async function startWebcam() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    deviceId: {
                        exact: camera
                    }
                },
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
                        requestAnimationFrame(renderFrame);
                    }
                }
                renderFrame();
                startRecording(stream);
                startPictureCountdown();
            });

        } catch (error) {
            console.error('Error accessing webcam: ', error);
            if (confirm('No camera selected. Do you want to go back to Home?')) {
                window.location.href = "<?= BASE_URL ?>"
            }
        }
    }

    function startRecording(stream) {
        recordedChunks = []; // Reset recorded chunks for new recording
        mediaRecorder = new MediaRecorder(stream);

        mediaRecorder.ondataavailable = event => {
            if (event.data.size > 0) {
                recordedChunks.push(event.data);
            }
        };

        mediaRecorder.onstop = () => {
            const blob = new Blob(recordedChunks, {
                type: 'video/mp4'
            });

            capturedVideos.push(blob);

            const recordedVideo = document.createElement('video');
            recordedVideo.src = URL.createObjectURL(blob);
            recordedVideo.controls = true;
            recordedVideoContainer.appendChild(recordedVideo);
        };

        mediaRecorder.start();
    }

    // Countdown and capture photo
    async function startPictureCountdown() {
        if (pictureCount < positions.length) {
            pictureCount += 1;

            let countdown = 3;

            // Show the countdown overlay and set the initial value
            countdownOverlay.style.display = 'flex';
            countdownOverlay.textContent = countdown;

            randomIndex = Math.floor(Math.random() * listAudio.length);
            new Audio('<?= BASE_URL ?>assets/audio/' + listAudio[randomIndex]).play();
            const countdownInterval = setInterval(async () => {
                countdown -= 1;

                if (countdown > 0) {
                    countdownOverlay.textContent = countdown;
                } else {
                    clearInterval(countdownInterval);
                    await flash();

                    // Hide the countdown overlay
                    // countdownOverlay.style.display = 'none';

                    // Capture photo
                    const snapshotCanvas = document.createElement('canvas');
                    snapshotCanvas.width = video.videoWidth || 640;
                    snapshotCanvas.height = video.videoHeight || 480;

                    const snapshotContext = snapshotCanvas.getContext('2d');
                    snapshotContext.drawImage(video, 0, 0, snapshotCanvas.width, snapshotCanvas.height);

                    const photo = new Image();
                    snapshotCanvas.toBlob(function(blob) {
                        let photoURL = URL.createObjectURL(blob);
                        capturedPhotos.push(blob);
                        photo.src = photoURL;

                        photosContainer.innerHTML += `
                        <div class="col px-2">
                            <img src="${photo.src}" class="img-fluid rounded shadow-sm mx-0 selected" onclick="selectPhoto(this)">
                        </div>
                    `;
                    }, 'image/png');
                    mediaRecorder.stop();
                    // Prepare for the next photo
                    if (pictureCount < positions.length) {
                        setTimeout(() => {
                            startRecording(video.srcObject); // Start a new recording
                            startPictureCountdown(); // Start the countdown for the next photo
                        }, 2000);
                    }
                }
            }, 1000);
        }
    }

    $(function() {
        Swal.fire({
            title: "Are you ready?",
            text: "Click OK to start",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "OK",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                startWebcam(); // Memulai kamera hanya jika pengguna menekan OK
            } else {
                window.location.href = "<?= BASE_URL ?>"; // Arahkan ke home jika Cancel
            }
        });
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
        if (selectedPhotos.length !== positions.length) {
            $('#select').prop('disabled', true);
        } else {
            $('#select').prop('disabled', false);
        }
    }

    $("#select").on('click', function() {
        $('#frame').removeAttr('hidden');
        $('#btn-action').removeClass('d-none');
        $("#photos").hide();
        $(this).hide();

        const ctx = frameCanvas.getContext('2d');
        frameCanvas.width = frameImage.width;
        frameCanvas.height = frameImage.height;

        selectedPhotos.forEach((photo, index) => {
            const selectedImage = new Image();
            selectedImage.src = photo.src;

            selectedImage.onload = function() {
                const selectedImageX = positions[index].x;
                const selectedImageY = positions[index].y;
                const selectedImageWidth = positions[index].width;
                const selectedImageHeight = positions[index].height;

                ctx.drawImage(selectedImage, selectedImageX, selectedImageY, selectedImageWidth, selectedImageHeight);
            };
        });

        const frame = new Image();
        frame.src = frameImageSrc;

        frame.onload = function() {
            ctx.drawImage(frame, 0, 0, frame.width, frame.height); // Gambar frame di depan gambar
            frameCanvas.toBlob(blob => blobResultImage = blob);
        };
        $('#select-filter').removeAttr('hidden');
    });

    $('#select-filter').on('click', function() {
        Swal.fire({
            title: "Menyimpan foto",
            text: "Loading..",
            didOpen: () => {
                Swal.showLoading();
            },
        });
        const formData = new FormData();
        formData.append('photos', blobResultImage);

        capturedVideos.forEach((blob, index) => {
            formData.append('video-' + (index + 1), blob);
        });

        capturedPhotos.forEach((blob, index) => {
            formData.append('photos-' + (index + 1), blob);
        });

        console.log("Isi FormData:");
        for (let [key, value] of formData.entries()) {
            console.log(`${key}:`, value);
        }

        $.ajax({
            url: "<?= BASE_URL ?>home/saveRecords",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                const mdata = JSON.parse(response)
                console.log(mdata);
                if (mdata.success) {
                    localStorage.removeItem("sisa_waktu");
                    window.location.href = "<?= BASE_URL ?>filter/" + mdata.folder
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Harap coba lagi.',
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Kesalahan pada server.',
                });
                console.error('Error saving videos:', error);
            }
        });


    });

    function flash() {
        return new Promise((resolve) => {
            $('#countdown-camera')
                .animate({
                    opacity: 0.5
                }, 300) // Redup
                .fadeOut(300, function() {
                    $(this).css('background-color', '#fff').show();
                    cameraSound.play();

                    setTimeout(() => {
                        $(this).css({
                            'background-color': '', // Hapus putih setelah 1 detik
                            'opacity': 1,
                            'display': 'none' // Sembunyikan elemen
                        });
                        resolve(); // Selesaikan Promise setelah efek selesai
                    }, 500);
                });
        });
    }

    function retake_photo() {
        localStorage.setItem("sisa_waktu", waktu);
        window.location.reload();
    }

    function change_frame() {
        localStorage.removeItem("sisa_waktu");
        window.location.href = "<?= BASE_URL ?>frame";
    }
</script>