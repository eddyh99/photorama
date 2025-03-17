<script src="<?= BASE_URL ?>assets/js/payment-check.js"></script>
<script>
    let selectedFrame;

    function redirecTo() {
        sessionStorage.setItem("selected_frame", selectedFrame);
        window.location.href = '<?= BASE_URL ?>camera';
    }
    // custom background
    $(function() {
        let id;

        $(".frame img").each(function() {
            let img = $(this);
            let canvas = img.siblings("canvas")[0];
            let ctx = canvas.getContext("2d");

            function setCanvasSize() {
                // Tunggu sebentar agar ukuran gambar stabil
                setTimeout(() => {
                    canvas.width = img.width();
                    canvas.height = img.height();
                    drawPosition(ctx, img, canvas);
                }, 50); // Delay kecil agar ukuran img sudah final
            }

            if (img[0].complete) {
                setCanvasSize();
            } else {
                img.on("load", setCanvasSize);
            }
        });

        function drawPosition(ctx, img, canvas) {
            let coordinates = JSON.parse(img.attr("data-coordinates") || "[]");

            let scaleX = canvas.width / img[0].naturalWidth;
            let scaleY = canvas.height / img[0].naturalHeight;

            ctx.fillStyle = "red"; // Warna angka
            ctx.font = "bold 20px Arial";
            ctx.strokeStyle = "black"; // Warna kotak hitam
            ctx.lineWidth = 2;

            coordinates.forEach(coord => {
                let x = parseFloat(coord.x) * scaleX;
                let y = parseFloat(coord.y) * scaleY;
                let width = parseFloat(coord.width) * scaleX;
                let height = parseFloat(coord.height) * scaleY;
                let rotation = parseFloat(coord.rotation) || 0; // Default 0 jika tidak ada rotasi

                ctx.save(); // Simpan state canvas sebelum transformasi
                ctx.translate(x + width / 2, y + height / 2); // Pindah ke tengah kotak
                ctx.rotate(rotation * Math.PI / 180); // Ubah derajat menjadi radian

                // Gambar kotak hitam (dikurangi setengah lebar & tinggi agar tetap di tengah)
                ctx.fillStyle = "black";
                ctx.fillRect(-width / 2, -height / 2, width, height);

                // Tulis angka di tengah kotak
                ctx.fillStyle = "red";
                ctx.font = "bold 20px Arial";
                let text = coord.index.toString();
                let textWidth = ctx.measureText(text).width;
                let textX = -textWidth / 2; // Pusatkan teks horizontal
                let textY = 10; // Sesuaikan teks di tengah vertikal

                ctx.fillText(text, textX, textY);
                ctx.restore(); // Kembalikan state canvas
            });


        }

        $('.frame').hover(
            function() {
                // Ketika mouse masuk ke elemen
                $(this).css({
                    'transform': 'scale(1.1)', // Membesarkan elemen
                    'box-shadow': '0 8px 16px rgba(0, 0, 0, 0.2)' // Menambahkan bayangan
                });
            },
            function() {
                // Ketika mouse meninggalkan elemen
                $(this).css({
                    'transform': 'scale(1)', // Kembalikan ukuran semula
                    'box-shadow': 'none' // Hilangkan bayangan
                });
            }
        );

        $(function() {
            $(".frame").click(function () {
        let img = $(this).find("img");
        let previewImg = $("#preview-frame");
        let previewCanvas = $("#preview-canvas")[0];
        let previewCtx = previewCanvas.getContext("2d");

        // Setel gambar preview
        let newSrc = img.attr("src");
        previewImg.attr("src", newSrc);

        // Tunggu sampai gambar preview selesai dimuat
        previewImg.on("load", function () {
            // Sesuaikan ukuran canvas dengan gambar
            previewCanvas.width = previewImg.width();
            previewCanvas.height = previewImg.height();

            // Gambar ulang posisi kotak pada canvas preview
            drawPosition(previewCtx, img, previewCanvas);
        });

        console.log("Selected frame:", img.attr("data-frame"));
    });


            $('#start').click(function(e) {
                if (!navigator.onLine) {
                    e.preventDefault(); // Mencegah navigasi jika offline
                    alert('No internet connection. Please check your network and try again.');
                } else {
                    sessionStorage.setItem("selected_frame", selectedFrame);
                    window.location.href = "<?= BASE_URL ?>camera";
                }
            })
        });
    });
</script>