<script src="<?= BASE_URL ?>assets/js/payment-check.js"></script>
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

    const frame = sessionStorage.getItem("selected_frame") || null;
    const frameImageSrc = '<?= BASE_URL ?>assets/img/' + frame;
    const frameImage = new Image();
    frameImage.src = frameImageSrc;

    const capturedPhotos = [];
    const capturedVideos = [];
    let blobResultImage;
    let pictureCount = 0;
    let mediaRecorder, recordedChunks;
    const positions = [];

    function redirecTo() {
        save();
    }

    async function getCoordinates(frame) {
        try {
            let url = encodeURIComponent(frame);
            const response = await $.get(`<?= BASE_URL ?>home/get_coordinates?frame=${url}`);
            const pos = JSON.parse(response);
            positions.push(...pos);
        } catch (error) {
            alert('Failed to get coordinates from frame');
            window.location.reload();
        }
    }
    // Access the webcam
    async function startWebcam() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: {
                        ideal: 1080
                    },
                    height: {
                        ideal: 768
                    },
                    deviceId: {
                        exact: camera
                    }
                },
                audio: false
            });
            video.srcObject = stream;

            // Overlay frame on video in real-time
            const context = overlayCanvas.getContext('2d');
            overlayCanvas.width = video.videoWidth || 1080;
            overlayCanvas.height = video.videoHeight || 768;

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
            if (confirm('No camera selected. Do you want to go back?')) {
                window.location.href = "<?= BASE_URL ?>camera"
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
    async function startPictureCountdown(idx = null) {

        if (pictureCount < positions.length) {
            pictureCount += 1;

            let countdown = 3;

            // Show the countdown overlay and set the initial value
            countdownOverlay.style.display = 'flex';
            countdownOverlay.textContent = countdown;

            // Pilih audio secara acak dari daftar
            let randomIndex = Math.floor(Math.random() * listAudio.length);
            let audio = new Audio('<?= BASE_URL ?>assets/audio/' + listAudio[randomIndex]);

            // Coba mainkan audio dengan error handling
            let playPromise = audio.play();

            if (playPromise !== undefined) {
                playPromise.then(() => {
                    console.log("Audio is playing successfully!");
                }).catch(error => {
                    // bug chrome
                    console.warn("Audio play was prevented:", error);
                });
            }

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
                    snapshotCanvas.width = video.videoWidth || 1080;
                    snapshotCanvas.height = video.videoHeight || 768;

                    const snapshotContext = snapshotCanvas.getContext('2d');
                    snapshotContext.drawImage(video, 0, 0, snapshotCanvas.width, snapshotCanvas.height);

                    const photo = new Image();
                    snapshotCanvas.toBlob(function(blob) {
                        let photoURL = URL.createObjectURL(blob);
                        photo.src = photoURL;

                        if (idx !== null && idx !== undefined) {

                            capturedPhotos[idx] = blob
                            selectedPhotos[idx] = photo;
                            $('#select').click();

                        } else {
                            selectedPhotos.push(photo);
                            capturedPhotos.push(blob);
                        }

                        photosContainer.innerHTML += `
                        <div class="col px-2">
                        <img src="${photo.src}" class="img-fluid rounded shadow-sm mx-0 selected">
                        </div>`;

                        if (selectedPhotos.length === positions.length) {
                            $('#select').prop('disabled', false);
                        } else {
                            $('#select').prop('disabled', true);
                        }
                    }, 'image/jpeg', 0.7);
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
        }).then(async (result) => {
            if (result.isConfirmed) {
                await getCoordinates(frame);

                startWebcam(); // Memulai kamera hanya jika pengguna menekan OK
            } else {
                window.location.href = "<?= BASE_URL ?>"; // Arahkan ke home jika Cancel
            }
        });
    });

    $("#select").on('click', function() {
        $('#frame').removeAttr('hidden');
        $('#btn-action').removeClass('d-none');
        $("#photos").hide();
        $(this).hide();
        $('#select-filter').removeAttr('hidden');

        const ctx = frameCanvas.getContext('2d');
        frameCanvas.width = frameImage.width;
        frameCanvas.height = frameImage.height;
        ctx.clearRect(0, 0, frameCanvas.width, frameCanvas.height);

        let loadedImages = 0;
        selectedPhotos.forEach((photo, index) => {
            const selectedImage = new Image();
            selectedImage.src = photo.src;

            selectedImage.onload = function() {
                const {
                    x,
                    y,
                    width,
                    height
                } = positions[index];
                ctx.drawImage(selectedImage, x, y, width, height);
                loadedImages++;

                // Jika semua foto sudah dimuat, gambar frame
                if (loadedImages === selectedPhotos.length) {
                    const frame = new Image();
                    frame.src = frameImageSrc;

                    frame.onload = function() {
                        ctx.drawImage(frame, 0, 0, frame.width, frame.height);
                        frameCanvas.toBlob(blob => blobResultImage = blob, 'image/jpeg', 0.7);
                    };
                }
            };

            // Tambahkan tombol retake jika belum ada
            let buttonId = "retake-btn-" + index;
            if ($("#" + buttonId).length === 0) {
                let retakeButton = $("<button>")
                    .attr("id", buttonId)
                    .text("Photo #" + (index + 1))
                    .addClass("btn btn-danger")
                    .on("click", function() {
                        retake_photo(index);
                    });

                $('#btn-retake').append(retakeButton);
            }
        });
    });


    $('#select-filter').on('click', function() {
        save();
    });

    function save() {
        Swal.fire({
            title: "Menyimpan foto",
            text: "Loading..",
            didOpen: () => {
                Swal.showLoading();
            },
        });
        const formData = new FormData();
        formData.append('photos', blobResultImage);

        // fix->retake
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

    }

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

    function retake_photo(index) {
        Swal.fire({
            title: "Retake Photo #" + (index + 1),
            text: "Are you sure you want to retake this photo?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, retake!",
            cancelButtonText: "Cancel"
        }).then(async (result) => {
            if (result.isConfirmed) {
                pictureCount = (positions.length - 1);
                startPictureCountdown(index);
            }
        });
    }

    function change_frame() {
        localStorage.removeItem("sisa_waktu");
        window.location.href = "<?= BASE_URL ?>frame";
    }
</script>