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
            ctx.fillStyle = "red"; // Warna angka
            ctx.font = "bold 20px Arial";

            coordinates.forEach(coord => {
                let scaleX = canvas.width / img[0].naturalWidth;
                let scaleY = canvas.height / img[0].naturalHeight;

                let x = parseFloat(coord.x) * scaleX;
                let y = parseFloat(coord.y) * scaleY;
                ctx.fillText(coord.index, x, y);
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
            $('.frame').click(function() {
                $('#preview-frame').attr('src', $(this).find('img').attr('src'));
                selectedFrame = $(this).find('img').attr('data-frame');
                console.log(selectedFrame);
                id = btoa($(this).find('img').attr('id'));
            });

            $('#start').click(function() {
                sessionStorage.setItem("selected_frame", selectedFrame);
                window.location.href = "<?= BASE_URL ?>camera";
            })
        });
    });
</script>