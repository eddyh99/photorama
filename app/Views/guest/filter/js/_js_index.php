<script>
    $(function() {
        const bg = <?= $background ? json_encode($background) : 'null'; ?>;
        $("#content-bg").css("background-image", bg ? `url('${bg}')` : 'none');
        const dir = btoa(<?= $dir ?>);
        const canvasToBlob = (canvas) => new Promise((resolve) => canvas.toBlob(resolve));

        let img = document.getElementById("photo");
        let canvas = document.getElementById("canvas");
        let ctx = canvas.getContext("2d", {
            willReadFrequently: true
        });

        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0, img.width, img.height);

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
        })

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