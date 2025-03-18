<script src="<?= BASE_URL ?>assets/js/payment-check.js"></script>
<script>
    const camera = JSON.parse(sessionStorage.getItem("camera")) || null;
    const selectedPhotos = [];
    const video = document.getElementById('webcam');
    const overlayCanvas = document.getElementById('overlay');
    const countdownOverlay = document.getElementById('countdown-camera');
    const photosContainer = document.getElementById('photos');
    const recordedVideoContainer = document.getElementById('recordedVideoContainer');
    const frameCanvas = document.getElementById('frame');
    const frameVideoCanvas = document.getElementById('frame-video');
    const cameraSound = new Audio('<?= BASE_URL ?>assets/audio/camera-13695.mp3');
    const cameraRotation = <?= json_encode($camera_rotation); ?>;
    const listAudio = [
        "get-ready.mp3",
        "smile.mp3",
        "say-cheese.mp3",
        "last-one.mp3",
        "next-pose.mp3"
    ];

    const frame = sessionStorage.getItem("selected_frame") || null;
    const frameImageSrc = '<?= BASE_URL ?>assets/img/' + decodeURIComponent(frame);
    const frameImage = new Image();
    frameImage.src = frameImageSrc;

    const capturedPhotos = [];
    const capturedVideos = [];
    let blobResultImage;
    let pictureCount = 0;
    let mediaRecorder, recordedChunks;
    const positions = [];
    let totalPhotos = 0;

    function redirecTo() {
        save();
    }


    async function getCoordinates(frame) {
        try {
            let url = encodeURIComponent(frame);
            const response = await $.get(`<?= BASE_URL ?>home/get_coordinates?frame=${url}`);
            const pos = JSON.parse(response);
            positions.push(...pos);
            totalPhotos = Math.max(...pos.map(obj => Number(obj.index)));
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
                        exact: camera.device
                    }
                },
                audio: false
            });
            video.srcObject = stream;
            video.style.transform = `rotate(${cameraRotation[camera.id]}deg)`;

            // Overlay frame on video in real-time
            const context = overlayCanvas.getContext('2d');
            overlayCanvas.width = video.videoWidth || 1080;
            overlayCanvas.height = video.videoHeight || 768;

            video.addEventListener('play', () => {
                function renderFrame() {
                    if (!video.paused && !video.ended) {
                        context.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
                        context.save(); // Simpan keadaan konteks
                        context.scale(-1, 1); // Membalikkan secara horizontal
                        context.rotate((cameraRotation[camera.id] * Math.PI) / 180);
                        context.drawImage(video, -overlayCanvas.width, 0, overlayCanvas.width, overlayCanvas.height);
                        context.restore();
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
        $("#previewkanan").addClass("d-none");
        $("#videoarea").removeClass("col-md-8");
        $("#videoarea").addClass("col-md-12");

        if (pictureCount < totalPhotos) {
            pictureCount += 1;

            let countdown = <?= json_encode($countdown) ?> ?? 4;

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

                    snapshotContext.save();
                    snapshotContext.scale(-1, 1);
                    snapshotContext.drawImage(video, -snapshotCanvas.width, 0, snapshotCanvas.width, snapshotCanvas.height);
                    snapshotContext.restore();

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

                        if (selectedPhotos.length === totalPhotos) {
                            $('#select').prop('disabled', false);
                            $("#previewkanan").removeClass("d-none");
                            $("#videoarea").removeClass("col-md-12");
                            $("#videoarea").addClass("col-md-8");
                            $("#select").click();
                        } else {
                            $('#select').prop('disabled', true);
                        }
                    }, 'image/jpeg', 0.7);
                    mediaRecorder.stop();
                    // Prepare for the next photo
                    if (pictureCount < totalPhotos) {
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
        $("#previewkanan").addClass("d-none");
        $("#videoarea").removeClass("col-md-8");
        $("#videoarea").addClass("col-md-12");

        Swal.fire({
            title: "Are you ready?",
            text: "Click OK to start",
            icon: "question",
            showCancelButton: false,
            confirmButtonText: "OK"
        }).then(async (result) => {
            if (result.isConfirmed) {
                await getCoordinates(frame);

                startWebcam(); // Memulai kamera hanya jika pengguna menekan OK
            } else {
                return;
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
        const ctxVideo = frameVideoCanvas.getContext("2d");
        frameCanvas.width = frameImage.width;
        frameCanvas.height = frameImage.height;
        frameVideoCanvas.width = frameImage.width;
        frameVideoCanvas.height = frameImage.height
        ctx.clearRect(0, 0, frameCanvas.width, frameCanvas.height);
        ctx.clearRect(0, 0, frameVideoCanvas.width, frameVideoCanvas.height);

        let loadedImages = 0;
        let loadedVideos = 0;
        positions.forEach((pos) => {
            const selectedImage = new Image();
            const selectedVideo = document.createElement("video");
            selectedImage.src = selectedPhotos[pos.index - 1].src;
            selectedVideo.src = URL.createObjectURL(capturedVideos[pos.index - 1]);
            selectedVideo.autoplay = true;
            selectedVideo.muted = true;
            selectedVideo.playsInline = true;
            selectedVideo.loop = true;

            const rotation = pos.rotation || 0;

            selectedImage.onload = function() {
                const tempCanvas = document.createElement("canvas");
                const tempCtx = tempCanvas.getContext("2d");

                // Atur ukuran canvas sementara sesuai rotasi
                if (rotation % 180 === 90) {
                    tempCanvas.width = pos.height;
                    tempCanvas.height = pos.width;
                } else {
                    tempCanvas.width = pos.width;
                    tempCanvas.height = pos.height;
                }

                // Pusatkan titik rotasi di tengah gambar
                tempCtx.save();
                tempCtx.translate(tempCanvas.width / 2, tempCanvas.height / 2);
                tempCtx.rotate((rotation * Math.PI) / 180); // Konversi derajat ke radian

                // Gambar gambar dengan posisi yang dikoreksi
                tempCtx.drawImage(selectedImage, -pos.width / 2, -pos.height / 2, pos.width, pos.height);
                tempCtx.restore();
                ctx.drawImage(tempCanvas, pos.x, pos.y, pos.width, pos.height);
                loadedImages++;

                // Jika semua foto sudah dimuat, gambar frame
                if (loadedImages === positions.length) {
                    const frame = new Image();
                    frame.src = frameImageSrc;

                    frame.onload = function() {
                        ctx.drawImage(frame, 0, 0, frame.width, frame.height);
                        frameCanvas.toBlob(blob => blobResultImage = blob, 'image/jpeg', 0.7);
                    };
                }
            };

            selectedVideo.addEventListener("loadeddata", function() {
                selectedVideo.play();
                const frameVideo = new Image();
                frameVideo.src = frameImageSrc;

                function drawVideoFrame() {
                    const tempVideoCanvas = document.createElement("canvas");
                    const tempVideoCtx = tempVideoCanvas.getContext("2d");

                    if (rotation % 180 === 90) {
                        tempVideoCanvas.width = pos.height;
                        tempVideoCanvas.height = pos.width;
                    } else {
                        tempVideoCanvas.width = pos.width;
                        tempVideoCanvas.height = pos.height;
                    }

                    tempVideoCtx.save();
                    tempVideoCtx.translate(tempVideoCanvas.width / 2, tempVideoCanvas.height / 2);
                    tempVideoCtx.rotate((rotation * Math.PI) / 180);
                    tempVideoCtx.drawImage(selectedVideo, -pos.width / 2, -pos.height / 2, pos.width, pos.height);
                    tempVideoCtx.restore();

                    ctxVideo.drawImage(tempVideoCanvas, pos.x, pos.y, pos.width, pos.height);
                    ctxVideo.drawImage(frameVideo, 0, 0, frameVideoCanvas.width, frameVideoCanvas.height);
                    requestAnimationFrame(drawVideoFrame);

                }

                drawVideoFrame();
                loadedVideos++;

            });

            // Tambahkan tombol retake jika belum ada
            let buttonId = "retake-btn-" + (pos.index - 1);
            if ($("#" + buttonId).length === 0) {
                let retakeButton = $("<button>")
                    .attr("id", buttonId)
                    .text("Photo " + (pos.index))
                    .addClass("btn btn-danger")
                    .on("click", function() {
                        retake_photo(pos.index - 1);
                    });

                $('#btn-retake').append(retakeButton);
            }
        });
    });


    $('#select-filter').on('click', function(e) {
        if (!navigator.onLine) {
            e.preventDefault(); // Mencegah navigasi jika offline
            alert('No internet connection. Please check your network and try again.');
        } else {
            save();
        }
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
                pictureCount = (totalPhotos - 1);
                startPictureCountdown(index);
            }
        });
    }

    function change_frame() {
        localStorage.removeItem("sisa_waktu");
        window.location.href = "<?= BASE_URL ?>frame";
    }
</script>