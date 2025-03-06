<script src="<?= BASE_URL ?>assets/js/payment-check.js"></script>
<script>
    let videoBlob = null;
    const dir = btoa(<?= json_encode($dir) ?>);

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
        const print = <?= json_encode(session()->get('print')) ?> || 1;
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
            frame.src = "<?= BASE_URL ?>assets/img/" + frame_selected;
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
                        height
                    } = pos;
                    ctx.drawImage(videos[index], x, y, width, height);
                }
            });
            // Gambar frame utama
            ctx.drawImage(frame, 0, 0, canvas.width, canvas.height);
            startRecording();
        }

        function printImage() {
            var printWindow = window.open('', '_blank', 'width=600,height=600');

            if (!printWindow) {
                alert("Pop-up terblokir! Izinkan pop-up untuk mencetak.");
                return;
            }

            printWindow.document.write(`
            <html>
            <head>
                <title>Print Image</title>
                <style>
                    body { margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
                    @page { size: 4in 6in; margin: 0; } /* Ukuran 4R */
                    img { width: 4in; height: 6in; object-fit: contain; }
                </style>
            </head>
            <body>
        `);

            for (var i = 0; i < print; i++) {
                printWindow.document.write(`<img src="${img}" />`);
            }

            printWindow.document.write(`</body></html>`);
            printWindow.document.close();

            printWindow.focus();

            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 500);
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
            url: "<?= BASE_URL ?>home/updateRecord/" +dir,
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