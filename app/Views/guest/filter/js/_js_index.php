<script src="<?= BASE_URL ?>assets/js/payment-check.js"></script>
<script>
    const dir = btoa(<?= json_encode($dir) ?>);

    function redirecTo() {
        window.location.href = '<?= BASE_URL ?>print/' +dir;
    }

    $(function() {
        const canvasToBlob = (canvas) => new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', 0.7));
        const frame = new Image();
        const selectedFrame = sessionStorage.getItem("selected_frame");
        frame.src = selectedFrame ? '<?= BASE_URL ?>assets/img/' + selectedFrame : null;

        let img = document.getElementById("photo");
        let canvas = document.getElementById("canvas");
        let ctx = canvas.getContext("2d", {
            willReadFrequently: true
        });


        if (img.complete) {
            drawImage();
        } else {
            img.onload = drawImage;
        }

        function drawImage() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0, img.width, img.height);
            frame.onload = function() {
                ctx.drawImage(frame, 0, 0, canvas.width, canvas.height);
            };
        }

        $("#grayscale").on('click', function() {
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let data = imageData.data;

            for (let i = 0; i < data.length; i += 4) {
                let r = data[i]; // Red
                let g = data[i + 1]; // Green
                let b = data[i + 2]; // Blue

                // Konversi ke grayscale menggunakan rumus luminance
                let gray = 0.3 * r + 0.59 * g + 0.11 * b;

                data[i] = data[i + 1] = data[i + 2] = gray;
            }

            ctx.putImageData(imageData, 0, 0);
            ctx.drawImage(frame, 0, 0, canvas.width, canvas.height);
        });

        $("#normal").on('click', function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        })

        $("#sephia").on('click', function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let data = imageData.data;

            for (let i = 0; i < data.length; i += 4) {
                let r = data[i];
                let g = data[i + 1];
                let b = data[i + 2];

                // Rumus konversi sepia
                data[i] = (r * 0.393) + (g * 0.769) + (b * 0.189); // Red
                data[i + 1] = (r * 0.349) + (g * 0.686) + (b * 0.168); // Green
                data[i + 2] = (r * 0.272) + (g * 0.534) + (b * 0.131); // Blue
            }

            ctx.putImageData(imageData, 0, 0);
            ctx.drawImage(frame, 0, 0, canvas.width, canvas.height);
        })

        $("#polaroid").on('click', function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let data = imageData.data;

            for (let i = 0; i < data.length; i += 4) {
                let r = data[i];
                let g = data[i + 1];
                let b = data[i + 2];

                // Efek Polaroid klasik (meningkatkan biru, menurunkan merah, dan meningkatkan kontras)
                data[i] = Math.min(255, (r * 1.1) - 30); // Red
                data[i + 1] = Math.min(255, (g * 1.05) - 15); // Green
                data[i + 2] = Math.min(255, (b * 1.2) + 10); // Blue
            }

            ctx.putImageData(imageData, 0, 0);
            ctx.drawImage(frame, 0, 0, canvas.width, canvas.height);
        })

        $("#bnw-glam").on('click', function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let data = imageData.data;

            let minGray = 255;
            let maxGray = 0;

            // Pertama: Hitung nilai grayscale minimum dan maksimum
            for (let i = 0; i < data.length; i += 4) {
                let r = data[i];
                let g = data[i + 1];
                let b = data[i + 2];

                let gray = 0.3 * r + 0.59 * g + 0.11 * b; // Grayscale standard

                minGray = Math.min(minGray, gray);
                maxGray = Math.max(maxGray, gray);
            }

            // Kedua: Terapkan kontras dengan stretching
            for (let i = 0; i < data.length; i += 4) {
                let r = data[i];
                let g = data[i + 1];
                let b = data[i + 2];

                let gray = 0.3 * r + 0.59 * g + 0.11 * b;

                // Kontras lebih dramatis menggunakan kontras stretching
                gray = ((gray - minGray) / (maxGray - minGray)) * 255;

                // Optional: Tambahkan efek pencahayaan
                gray = gray > 128 ? Math.min(gray * 1.3, 255) : Math.max(gray * 0.8, 0);

                data[i] = data[i + 1] = data[i + 2] = gray;
            }

            ctx.putImageData(imageData, 0, 0);
            ctx.drawImage(frame, 0, 0, canvas.width, canvas.height);
        });



        $("#gotham").on("click", function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let data = imageData.data;

            for (let i = 0; i < data.length; i += 4) {
                let r = data[i];
                let g = data[i + 1];
                let b = data[i + 2];

                // Konversi ke grayscale
                let gray = 0.3 * r + 0.59 * g + 0.11 * b;

                // Tambahkan efek biru dengan sedikit peningkatan biru
                data[i] = gray * 0.9; // Red sedikit lebih gelap
                data[i + 1] = gray * 0.9; // Green sedikit lebih gelap
                data[i + 2] = gray * 1.2; // Biru lebih terang untuk efek dingin
            }

            ctx.putImageData(imageData, 0, 0);
            ctx.drawImage(frame, 0, 0, canvas.width, canvas.height);
        });

        $("#brannan").on("click", function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let data = imageData.data;

            for (let i = 0; i < data.length; i += 4) {
                let r = data[i];
                let g = data[i + 1];
                let b = data[i + 2];

                // Kurangi kontras dengan mencampurkan warna ke nilai rata-rata
                let avg = (r + g + b) / 3;
                r = (r + avg * 0.7) / 2;
                g = (g + avg * 0.6) / 2;
                b = (b + avg * 0.5) / 2;

                // Tambahkan efek sepia (hangat)
                r *= 1.2;
                g *= 1.1;
                b *= 0.9;

                data[i] = Math.min(255, r);
                data[i + 1] = Math.min(255, g);
                data[i + 2] = Math.min(255, b);
            }

            ctx.putImageData(imageData, 0, 0);
            ctx.drawImage(frame, 0, 0, canvas.width, canvas.height);
        });

        $("#xpro").on("click", function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let data = imageData.data;

            for (let i = 0; i < data.length; i += 4) {
                let r = data[i];
                let g = data[i + 1];
                let b = data[i + 2];

                // Tingkatkan kontras
                r = Math.min(255, r * 1.2);
                g = Math.min(255, g * 1.3);
                b = Math.min(255, b * 0.8);

                // Berikan sedikit efek hijau-kuning
                data[i] = r;
                data[i + 1] = g;
                data[i + 2] = b;
            }

            ctx.putImageData(imageData, 0, 0);
            ctx.drawImage(frame, 0, 0, canvas.width, canvas.height);
        });



        $("#next").on('click', async function() {
            Swal.fire({
                title: "Menerapkan filter",
                text: "Loading..",
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            const result = await updatePhoto();
            Swal.close();
            if (result) {
                window.location.href = "<?= BASE_URL ?>print/" + dir;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan.',
                });

            }
        });


        async function updatePhoto() {
            const formData = new FormData();
            formData.append('photo', await canvasToBlob(canvas));

            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "<?= BASE_URL ?>home/updateRecord/" + dir,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const mdata = JSON.parse(response)
                        resolve(mdata.success);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving videos:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan.',
                        });
                    }
                });
            });
        }

    });
</script>