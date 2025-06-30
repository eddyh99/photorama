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
    // const cameraSound = new Audio('<?= BASE_URL ?>assets/audio/camera-13695.mp3');
    const cameraRotation = <?= json_encode($camera_rotation); ?>;
    // const listAudio = [
    //     "get-ready.mp3",
    //     "smile.mp3",
    //     "say-cheese.mp3",
    //     "last-one.mp3",
    //     "next-pose.mp3"
    // ];

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
            const portrait = window.innerHeight > window.innerWidth;
            console.log(window.innerHeight);
            console.log(window.innerWidth);
            
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: {
                        ideal: portrait ? 1080 : 1920
                    },
                    height: {
                        ideal: portrait ? 1920 : 1080
                    },
                    aspectRatio: portrait ? { ideal: 9 / 16 } : { ideal: 16 / 9 },
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

            const countdownValue = <?= json_encode($countdown ?? 5) ?>;

            countdownOverlay.style.display = 'flex';
            countdownOverlay.textContent = countdownValue;

            // const audio = new Audio('<?= BASE_URL ?>assets/audio/' + listAudio[Math.floor(Math.random() * listAudio.length)]);
            // audio.play().catch(err => console.warn("Audio play was prevented:", err));

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
                            $("#videoarea").addClass("d-none");
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
                            const confirm = await showConfirmation();
                            if(confirm) {
                                await startWebcam();
                            }
                        }, 1000);
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
        const recordedStream = recordingCanvas.captureStream(60);

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
        const stream = frameVideoCanvas.captureStream(30);
        const mediaRecorder = new MediaRecorder(stream, {
            mimeType: "video/webm;codecs=vp9",
            videoBitsPerSecond: 5000000 
        });
        
        const chunks = [];
        mediaRecorder.ondataavailable = (e) => {
            if (e.data.size > 0) chunks.push(e.data);
        };
    
        mediaRecorder.onstop = () => {
            console.log("Recording stopped.");
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
            }
            const webmBlob = new Blob(chunks, { type: "video/webm" });
            videoBlob = webmBlob;
        };
    
        mediaRecorder.start();

        // Hentikan rekaman setelah 3 detik
        setTimeout(() => {
            mediaRecorder.stop();
            $('#btn-retake').removeClass('pe-none');
            $('#select-filter').text('Select Filter');
            $('#select-filter').removeAttr('disabled');
        }, 8000);
    }


/*    function getAspectRatio(idx) {

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
        
        targetWidth = video.videoWidth;
        targetHeight = video.videoHeight;
        const x = 0;
        const y = 0;

        return {
            targetWidth,
            targetHeight,
            x: x,
            y: y
        };
    }*/
    
    function getAspectRatio(idx) {
        const frame = idx ? positions[idx - 1] : positions.find(item => item.index == pictureCount +1);
        console.log("Fw : "+frame.width);
        console.log("Fh : "+frame.height);
        let aspectRatio = frame.width / frame.height;
        if (isRotate) {
            aspectRatio = frame.height / frame.width;
        }
    
        // Assume video is being rendered in portrait mode (rotated 90deg)
        let videoWidth = video.videoWidth;
        let videoHeight = video.videoHeight;

        const isPortrait = videoHeight > videoWidth;
        console.log(isPortrait);
        if (!isPortrait && videoWidth < videoHeight) {
            // Rotate video logic: swap width/height if you're rendering portrait
            [videoWidth, videoHeight] = [videoHeight, videoWidth];
        }

        if (isPortrait && videoWidth > videoHeight) {
            // Rotate video logic: swap width/height if you're rendering portrait
            [videoWidth, videoHeight] = [videoHeight, videoWidth];
        }
        
        
    
        // if (videoWidth > videoHeight) {
        //     // Rotate video logic: swap width/height if you're rendering portrait
        //     [videoWidth, videoHeight] = [videoHeight, videoWidth];
        // }
    
        // Scale frame to fit video while preserving aspect ratio
        let targetWidth = videoWidth;
        let targetHeight = targetWidth / aspectRatio;
    
        if (targetHeight > videoHeight) {
            targetHeight = videoHeight;
            targetWidth = targetHeight * aspectRatio;
        }
    
        const x = (videoWidth - targetWidth) / 2;
        const y = (videoHeight - targetHeight) / 2;
    
        console.log("Frame:", frame.width, frame.height);
        console.log("Video (portrait):", videoWidth, videoHeight);
        console.log("Target:", targetWidth, targetHeight);
        console.log("Offset:", x, y);
    
        return {
            targetWidth,
            targetHeight,
            x,
            y
        };
    }

    


    $(function() {
        overlayCanvas.style.transform = `scaleX(-1) rotate(${cameraRotation[camera.id]}deg)`;
        overlayCanvas.style.transformOrigin = 'center';
        $("#previewkanan").addClass("d-none");
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
    
    let frameOverlayImage = null;

    $("#select").on('click', function () {
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
        frameVideoCanvas.height = frameImage.height;
    
        ctx.clearRect(0, 0, frameCanvas.width, frameCanvas.height);
        ctxVideo.clearRect(0, 0, frameVideoCanvas.width, frameVideoCanvas.height);
    
        let loadedImages = 0;
        let loadedVideos = 0;
        const videoElements = [];
        frameOverlayImage = new Image();
        frameOverlayImage.src = frameImageSrc;

        positions.forEach((pos, idx) => {
            // === IMAGE SETUP ===
            const selectedImage = new Image();
            selectedImage.src = selectedPhotos[pos.index - 1].src;
    
            const rotation = pos.rotation || 0;
    
            selectedImage.onload = function () {
                const tempCanvas = document.createElement("canvas");
                const tempCtx = tempCanvas.getContext("2d");
    
                if (rotation % 180 === 90) {
                    tempCanvas.width = pos.height;
                    tempCanvas.height = pos.width;
                } else {
                    tempCanvas.width = pos.width;
                    tempCanvas.height = pos.height;
                }
    
                tempCtx.save();
                tempCtx.translate(tempCanvas.width / 2, tempCanvas.height / 2);
                tempCtx.rotate((rotation * Math.PI) / 180);
                tempCtx.drawImage(selectedImage, -pos.width / 2, -pos.height / 2, pos.width, pos.height);
                tempCtx.restore();
    
                ctx.drawImage(tempCanvas, pos.x, pos.y, pos.width, pos.height);
                loadedImages++;
    
                if (loadedImages === positions.length) {
                    const frame = new Image();
                    frame.src = frameImageSrc;
    
                    frame.onload = function () {
                        ctx.drawImage(frame, 0, 0, frame.width, frame.height);
                        frameCanvas.toBlob(blob => blobResultImage = blob, 'image/jpeg', 0.7);
                    };
                }
            };
    
            // === VIDEO SETUP ===
            const selectedVideo = document.createElement("video");
            selectedVideo.src = URL.createObjectURL(capturedVideos[pos.index - 1]);
            selectedVideo.autoplay = false;
            selectedVideo.muted = true;
            selectedVideo.playsInline = true;
            selectedVideo.loop = true;
    
            selectedVideo.addEventListener("loadeddata", function () {
                loadedVideos++;
                selectedVideo.play();
                videoElements[idx] = { video: selectedVideo, pos };
    
                if (loadedVideos === positions.length && !recordingStarted) {
                    recordingStarted = true;

                    // Ensure all videos are explicitly played
                    Promise.all(videoElements.map(({ video }) =>video.play().catch((e) => {console.warn("Play error", e);})))
                        .then(() => {
                            drawVideoFrameLoop(); // Start drawing once all are playing
                            startVideoRecord();
                        });

                }

            });
    
            // Append retake button if not exist
            let buttonId = "retake-btn-" + (pos.index - 1);
            if ($("#" + buttonId).length === 0) {
                let retakeButton = $("<button>")
                    .attr("id", buttonId)
                    .text("Photo " + (pos.index))
                    .addClass("btn btn-danger")
                    .on("click", function () {
                        retake_photo(pos.index - 1);
                    });
                $('#btn-retake').append(retakeButton);
            }
        });
    
        // === DRAW LOOP USING EXISTING VIDEOS ===
        function drawVideoFrameLoop() {
            // Get original frame size
            const frameWidth = frameCanvas.width;
            const frameHeight = frameCanvas.height;
            const frameAspectRatio = frameWidth / frameHeight;
        
            // Set a max canvas size for performance (e.g., max 720p)
            const maxCanvasWidth = 1280;
            const maxCanvasHeight = 720;
        
            // Scale down frameCanvas while maintaining aspect ratio
            let targetWidth = maxCanvasWidth;
            let targetHeight = targetWidth / frameAspectRatio;
        
            if (targetHeight > maxCanvasHeight) {
                targetHeight = maxCanvasHeight;
                targetWidth = targetHeight * frameAspectRatio;
            }
        
            frameVideoCanvas.width = targetWidth;
            frameVideoCanvas.height = targetHeight;
        
            const ctxVideo = frameVideoCanvas.getContext("2d");
        
            // Precompute scaling ratio from original frame to video canvas
            const scaleX = targetWidth / frameWidth;
            const scaleY = targetHeight / frameHeight;
        
            function draw() {
                ctxVideo.clearRect(0, 0, targetWidth, targetHeight);
        
                videoElements.forEach(({ video, pos }) => {
                    if (!video.paused && !video.ended) {
                        const x = pos.x * scaleX;
                        const y = pos.y * scaleY;
                        const width = pos.width * scaleX;
                        const height = pos.height * scaleY;
        
                        ctxVideo.drawImage(video, x, y, width, height);
                    }
                });
        
                if (frameOverlayImage && frameOverlayImage.complete) {
                    ctxVideo.drawImage(frameOverlayImage, 0, 0, targetWidth, targetHeight);
                }
        
                requestAnimationFrame(draw);
            }
        
            draw();
        }



        /*function drawVideoFrameLoop() {
            console.log("Drawing frame...");

            const ctxVideo = frameVideoCanvas.getContext("2d");
            ctxVideo.clearRect(0, 0, frameVideoCanvas.width, frameVideoCanvas.height);
        
            videoElements.forEach(({ video, pos }) => {
                // Just draw directly — no offscreen canvas yet
                if (!video.paused && !video.ended) {
                    ctxVideo.drawImage(video, pos.x, pos.y, pos.width, pos.height);
                } 
            });
        
            // Use preloaded frame overlay if available
            if (frameOverlayImage && frameOverlayImage.complete) {
                ctxVideo.drawImage(frameOverlayImage, 0, 0, frameVideoCanvas.width, frameVideoCanvas.height);
            }
            
            requestAnimationFrame(drawVideoFrameLoop);
        }*/

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
                $("#previewkanan").addClass("d-none");
                $("#videoarea").removeClass("d-none");
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
                    // cameraSound.play();

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

    function showConfirmation() {
        return new Promise((resolve) => {
            let countdown = 3;
        Swal.fire({
            title: "Ready to next pose!",
            html: `Click OK to start`,
            icon: "question",
            showCancelButton: false,
            confirmButtonText: `OK (${countdown})`,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                const confirmButton = Swal.getConfirmButton();

                const timerInterval = setInterval(() => {
                    countdown--;
                    Swal.update({
                        confirmButtonText: `OK (${countdown})`
                    });

                    if (countdown <= 0) {
                        clearInterval(timerInterval);
                        Swal.close();
                        resolve(true);
                    }
                }, 1000);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                resolve(true);
            }
        });
        })
    }
</script>