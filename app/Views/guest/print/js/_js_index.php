<script src="<?= BASE_URL ?>assets/js/payment-check.js"></script>
<script>
    let videoBlob = null;
    const dir = btoa(<?= json_encode($dir) ?>);
    const print = sessionStorage.getItem('print') || 1;
    console.log(print);


    function redirecTo() {
        save();
    }

    $(function async () {
        // variabel for record video
        let mediaRecorder;
        let recordedChunks = [];
        let recordingStarted = false; // Pastikan hanya merekam sekali

        const img = $("#photo").attr('src');
        const autoPrint = <?= json_encode($auto_print) ?>;
        const frame_selected = sessionStorage.getItem("selected_frame") || null;
        const frame = new Image();
        const positions = [];
        const canvas = document.getElementById("frame-video");
        const ctx = canvas.getContext("2d", {
            willReadFrequently: true
        });

        const videoSources = <?= json_encode($videos) ?> || [];
        const videos = videoSources.map((src) => {
            const video = document.createElement("video");
            video.src = src;
            video.autoplay = true;
            video.loop = true;
            video.muted = true;
            video.playsInline = true;
            video.play();
            return video;
        });

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


        if (frame) {
            frame.src = "<?= BASE_URL ?>assets/img/" + decodeURIComponent(frame_selected);
            frame.onload = async () => {
                // Set ukuran canvas berdasarkan gambar yang sudah dimuat
                canvas.width = frame.width;
                canvas.height = frame.height;
                await getCoordinates(frame_selected);
                updateCanvas();
            };
        }

        function updateCanvas() {
            requestAnimationFrame(updateCanvas);

            // Bersihkan canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Gambar video pada koordinat yang sudah diambil
            positions.forEach((pos, index) => {
                if (videos[index]) {
                    const {
                        x,
                        y,
                        width,
                        height,
                        rotation
                    } = pos;

                    // Buat canvas sementara untuk rotasi
                    const tempCanvas = document.createElement("canvas");
                    const tempCtx = tempCanvas.getContext("2d");

                    // Atur ukuran canvas sementara sesuai rotasi
                    if (rotation % 180 === 90) {
                        tempCanvas.width = height; // Tukar width & height jika rotasi 90° atau 270°
                        tempCanvas.height = width;
                    } else {
                        tempCanvas.width = width;
                        tempCanvas.height = height;
                    }

                    // Pindahkan titik pusat ke tengah canvas sementara
                    tempCtx.translate(tempCanvas.width / 2, tempCanvas.height / 2);
                    tempCtx.rotate((rotation * Math.PI) / 180); // Konversi derajat ke radian

                    // Gambar video di tengah canvas sementara
                    tempCtx.drawImage(videos[index], -width / 2, -height / 2, width, height);

                    // Gambar hasil rotasi ke canvas utama
                    ctx.drawImage(tempCanvas, x, y, tempCanvas.width, tempCanvas.height);
                }
            });

            // Gambar frame utama
            ctx.drawImage(frame, 0, 0, canvas.width, canvas.height);
            startRecording();
        }

        function printImage() {
            console.log(print);

            $.ajax({
                url: "<?= BASE_URL ?>home/cetakPDF/" + dir + "/" + print,
                type: "GET",
                success: function(response) {
                    const mdata = JSON.parse(response)
                    if (mdata.status === "success") {
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'Foto berhasil dicetak.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        })

                    } else {
                        alert("Gagal print Foto!");
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan saat menyimpan PDF.");
                }
            })
        }

        // Cetak otomatis jika session print aktif
        if (autoPrint) {
            setTimeout(printImage, 1000);
        } else {
            $("#print").removeAttr("hidden");
        }

        // Cetak manual jika tombol ditekan
        $("#print").on('click', function() {
            printImage();
        });

        function startRecording() {
            if (recordingStarted) return;

            let stream = canvas.captureStream(30);
            mediaRecorder = new MediaRecorder(stream, {
                mimeType: "video/webm; codecs=vp9"
            });

            mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    recordedChunks.push(event.data);
                }
            };

            mediaRecorder.onstop = async () => {
                const webmBlob = new Blob(recordedChunks, {
                    type: "video/webm"
                });
                console.log("WebM video saved as Blob:", webmBlob);

                // Simpan hasil dalam variabel global
                videoBlob = webmBlob;

                recordedChunks = [];
            };

            mediaRecorder.start();
            recordingStarted = true;
            console.log("Perekaman dimulai...");

            // Hentikan rekaman setelah 5 detik
            setTimeout(() => {
                mediaRecorder.stop();
                $('#submit').removeAttr('disabled');
                console.log("Perekaman selesai.");
            }, 4000);
        }

    });

    function save() {
        Swal.fire({
            title: "Please be patient",
            text: "finishing..",
            didOpen: () => {
                Swal.showLoading();
            },
        });
        const formData = new FormData();
        formData.append('video', videoBlob);

        $.ajax({
            url: "<?= BASE_URL ?>home/updateRecord/" + dir,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                const mdata = JSON.parse(response)
                console.log(mdata);
                if (mdata.success) {
                    window.location.href = "<?= BASE_URL ?>finish"
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
</script>