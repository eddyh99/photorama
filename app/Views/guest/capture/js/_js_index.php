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
    const rotation = cameraRotation[camera.id] || 0;
    let blobResultImage;
    let videoBlob;
    let recordingStarted = false;
    let pictureCount = 0;
    let mediaRecorder, recordedChunks;
    const positions = [];
    let totalPhotos = 0;
    let animationFrameId;
    let isRotate = rotation == 90 || rotation == 270;

    function redirecTo() {
        save();
    }

    window.addEventListener("beforeunload", cleanup);
    window.addEventListener("pagehide", cleanup);


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

    async function startWebcam(idx = null) {

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: {
                        ideal: 1920
                    },
                    height: {
                        ideal: 1080
                    },
                    facingMode: "user",
                    deviceId: {
                        exact: camera.device
                    }
                },
                audio: false
            });

            video.srcObject = stream;

            video.addEventListener('play', () => {
                const {
                    targetWidth,
                    targetHeight,
                    x,
                    y
                } = getAspectRatio(idx);

                overlayCanvas.width = video.videoWidth;
                overlayCanvas.height = video.videoHeight;
                const context = overlayCanvas.getContext('2d');

                function renderFrame() {
                    if (!video.paused && !video.ended) {
                        context.fillStyle = "black";
                        context.fillRect(0, 0, overlayCanvas.width, overlayCanvas.height);

                        context.save();
                        context.translate(video.videoWidth / 2, video.videoHeight / 2);
                        context.drawImage(video, x, y, targetWidth, targetHeight, -targetWidth / 2, -targetHeight / 2, targetWidth, targetHeight);
                        context.restore();

                        requestAnimationFrame(renderFrame);
                    }
                }

                renderFrame();
                startRecording(stream, targetWidth, targetHeight, x, y);

                if (idx === null) {
                    startPictureCountdown();
                }
            }, {
                once: true
            }); // Gunakan once:true untuk menghindari duplikasi event listener

        } catch (error) {
            console.error('Error accessing webcam: ', error);
            if (confirm('No camera selected. Do you want to go back?')) {
                window.location.href = "<?= BASE_URL ?>camera"
            }
        }
    }

    // Countdown and capture photo
    async function startPictureCountdown(idx = null) {
        if (pictureCount < totalPhotos) {

            if (idx != null) {
                if (video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                    video.srcObject = null;
                }
                console.log('retake');

                await startWebcam(idx + 1);
            }

            const countdownValue = <?= json_encode($countdown ?? 1) ?>;

            countdownOverlay.style.display = 'flex';
            countdownOverlay.textContent = countdownValue;

            const audio = new Audio('<?= BASE_URL ?>assets/audio/' + listAudio[Math.floor(Math.random() * listAudio.length)]);
            audio.play().catch(err => console.warn("Audio play was prevented:", err));

            let countdown = countdownValue;
            console.log(mediaRecorder)

            // Start recording
            if (mediaRecorder && mediaRecorder.state === 'inactive') {
                setTimeout(() => {
                    mediaRecorder.start();
                }, 500);

            }

            const countdownInterval = setInterval(async () => {
                countdown -= 1;

                if (countdown > 0) {
                    countdownOverlay.textContent = countdown;
                } else {
                    clearInterval(countdownInterval);
                    await flash();

                    const {
                        targetWidth,
                        targetHeight,
                        x,
                        y
                    } = idx != null ? getAspectRatio(idx + 1) : getAspectRatio();

                    const snapshotCanvas = document.createElement('canvas');
                    snapshotCanvas.width = targetWidth;
                    snapshotCanvas.height = targetHeight;
                    const snapshotContext = snapshotCanvas.getContext('2d');

                    // Direct draw without flipping
                    snapshotContext.drawImage(video, x, y, targetWidth, targetHeight, 0, 0, targetWidth, targetHeight);

                    console.log('Rotation for camera', camera.id, 'is', rotation);

                    const rotatedCanvas = document.createElement('canvas');
                    const rotatedContext = rotatedCanvas.getContext('2d');

                    if (rotation == 90 || rotation == 270) {
                        rotatedCanvas.width = targetHeight;
                        rotatedCanvas.height = targetWidth;
                    } else {
                        rotatedCanvas.width = targetWidth;
                        rotatedCanvas.height = targetHeight;
                    }

                    rotatedContext.save();
                    rotatedContext.translate(rotatedCanvas.width / 2, rotatedCanvas.height / 2);
                    rotatedContext.scale(-1, 1);
                    rotatedContext.rotate((rotation * Math.PI) / 180);

                    rotatedContext.drawImage(
                        snapshotCanvas,
                        -targetWidth / 2,
                        -targetHeight / 2,
                        targetWidth,
                        targetHeight
                    );

                    rotatedContext.restore();


                    rotatedCanvas.toBlob(function(blob) {
                        const photoURL = URL.createObjectURL(blob);
                        const photo = new Image();
                        photo.src = photoURL;

                        if (idx != null) {
                            capturedPhotos[idx] = blob;
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
                            $("#videoarea").removeClass("col-lg-12").addClass("col-lg-8");
                            $('#select').click();
                        } else {
                            $('#select').prop('disabled', true);
                        }
                    }, 'image/jpeg', 0.7);

                    // Stop recording
                    if (mediaRecorder && mediaRecorder.state === 'recording') {
                        mediaRecorder.stop();
                    }

                    pictureCount += 1;
                    if (pictureCount < totalPhotos) {
                        setTimeout(async () => {
                            await startWebcam();
                        }, 2000);
                    }
                }
            }, 1000);
        }
    }


    function startRecording(stream, targetWidth, targetHeight, x, y) {
        recordedChunks = []; // Reset recorded chunks for new recording

        // Buat canvas rekaman sesuai ukuran aspect ratio
        const recordingCanvas = document.createElement('canvas');
        recordingCanvas.width = targetWidth;
        recordingCanvas.height = targetHeight;
        const recordingContext = recordingCanvas.getContext('2d');

        // Ambil video dari stream asli
        const videoTrack = stream.getVideoTracks()[0];
        const videoElement = document.createElement('video');
        videoElement.srcObject = new MediaStream([videoTrack]);
        videoElement.play();

        function drawFrame() {
            if (videoTrack.readyState === "live") {
                recordingContext.clearRect(0, 0, recordingCanvas.width, recordingCanvas.height);

                // Crop video sesuai aspect ratio
                recordingContext.save();
                recordingContext.translate(recordingCanvas.width / 2, recordingCanvas.height / 2);
                recordingContext.rotate((cameraRotation[camera.id] || 0) * Math.PI / 180); // Rotasi sesuai kamera
                recordingContext.drawImage(
                    videoElement,
                    x, y, targetWidth, targetHeight, // Area yang diambil dari video
                    -targetWidth / 2, -targetHeight / 2, targetWidth, targetHeight // Tempatkan ke recordingCanvas
                );
                recordingContext.restore();

                requestAnimationFrame(drawFrame);
            }
        }

        drawFrame();

        // Ambil stream dari recordingCanvas untuk MediaRecorder
        const recordedStream = recordingCanvas.captureStream();

        mediaRecorder = new MediaRecorder(recordedStream);

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

    function startVideoRecord() {
        if (recordingStarted) return;

        let stream = frameVideoCanvas.captureStream(30);
        mediaRecorder = new MediaRecorder(stream, {
            mimeType: "video/webm; codecs=vp9"
        });

        mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                recordedChunks = [];
                recordedChunks.push(event.data);
            }
        };

        mediaRecorder.onstop = async () => {
            stream.getTracks().forEach(track => track.stop());
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
            }
            const webmBlob = new Blob(recordedChunks, {
                mimeType: "video/webm; codecs=vp9"
            });
            console.log("WebM video saved as Blob:", webmBlob);

            // Simpan hasil dalam variabel global
            videoBlob = webmBlob;

            recordedChunks = [];
        };

        mediaRecorder.start();
        recordingStarted = true;
        console.log("Perekaman dimulai...");

        // Hentikan rekaman setelah 3 detik
        setTimeout(() => {
            mediaRecorder.stop();
            $('#btn-retake').removeClass('pe-none');
            $('#select-filter').text('Select Filter');
            $('#select-filter').removeAttr('disabled');
        }, 8000);
    }


    /*function getAspectRatio(idx) {
        const frame = idx ? positions[idx - 1] : positions[(pictureCount === 0 ? 1 : pictureCount) - 1];
        const aspectRatio = frame.width / frame.height;

        const videoWidth = video.videoWidth;
        const videoHeight = video.videoHeight;

        // Maintain aspect ratio without zooming
        let targetWidth, targetHeight;

        if (videoWidth / videoHeight > aspectRatio) {
            targetHeight = videoHeight;
            targetWidth = targetHeight * aspectRatio;
        } else {
            targetWidth = videoWidth;
            targetHeight = targetWidth / aspectRatio;
        }

        return {
            targetWidth,
            targetHeight,
            x: (videoWidth - targetWidth) / 2,
            y: (videoHeight - targetHeight) / 2
        };
    }
    */

    function getAspectRatio(idx) {

        const frame = idx ? positions[idx - 1] : positions[0];
        console.log('Posisi' + positions.findIndex(item => item.index == 3));
        
        let aspectRatio = frame.width / frame.height;

        if (isRotate) {
            aspectRatio = frame.height / frame.width;
        }

        // Ukuran asli video
        const videoWidth = video.videoWidth;
        const videoHeight = video.videoHeight;

        // Tentukan ukuran target berdasarkan aspect ratio
        let targetWidth = videoWidth;
        let targetHeight = targetWidth / aspectRatio;

        // Jika tinggi lebih besar dari tinggi video asli, sesuaikan berdasarkan tinggi
        if (targetHeight > videoHeight) {
            targetHeight = videoHeight;
            targetWidth = targetHeight * aspectRatio;
        }

        return {
            targetWidth,
            targetHeight,
            x: (videoWidth - targetWidth) / 2,
            y: (videoHeight - targetHeight) / 2
        };
    }


    $(function() {
        overlayCanvas.style.transform = `scaleX(-1) rotate(${cameraRotation[camera.id]}deg)`;
        overlayCanvas.style.transformOrigin = 'center';
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
                await cleanup();
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
                if (recordingStarted) return;
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

                    animationFrameId = requestAnimationFrame(drawVideoFrame);

                }

                drawVideoFrame();
                loadedVideos++;
                if (loadedVideos === positions.length) {
                    startVideoRecord();
                }

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

        capturedPhotos.forEach((blob, index) => {
            formData.append('photos-' + (index + 1), blob);
        });

        formData.append('video', videoBlob);

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

    function retake_photo(index) {
        Swal.fire({
            title: "Retake Photo #" + (index + 1),
            text: "Are you sure you want to retake this photo?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, retake!",
            cancelButtonText: "Cancel"
        }).then(async (result) => {
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
            }
            if (result.isConfirmed) {
                pictureCount = (totalPhotos - 1);
                startPictureCountdown(index);
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

    function change_frame() {
        localStorage.removeItem("sisa_waktu");
        window.location.href = "<?= BASE_URL ?>frame";
    }

    function cleanup() {
        // Hentikan semua track video jika ada
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }

        // Hentikan perekaman jika masih berjalan
        if (mediaRecorder && mediaRecorder.state === "recording") {
            mediaRecorder.stop();
        }

        // Hapus blob hasil rekaman
        if (videoBlob) {
            URL.revokeObjectURL(videoBlob);
        }

        // Hapus objek URL dari foto yang diambil
        selectedPhotos.forEach(photo => {
            if (photo.src) URL.revokeObjectURL(photo.src);
        });

        // Bersihkan elemen tampilan
        photosContainer.innerHTML = "";
        recordedVideoContainer.innerHTML = "";

        // Hentikan animasi jika ada
        if (animationFrameId) {
            cancelAnimationFrame(animationFrameId);
        }

        console.log("Cleanup completed before unload.");
    }
</script>