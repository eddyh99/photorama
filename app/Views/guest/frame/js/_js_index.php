<script>
    // custom background
    $(function() {
        const bg = <?= $background ? json_encode($background) : 'null'; ?>;
        $("#content-bg").css("background-image", bg ? `url('${bg}')` : 'none');

        $('.frame').hover(
            function() { 
                // Ketika mouse masuk ke elemen
                $(this).css({
                    'transform': 'scale(1.1)',  // Membesarkan elemen
                    'box-shadow': '0 8px 16px rgba(0, 0, 0, 0.2)'  // Menambahkan bayangan
                });
            }, 
            function() { 
                // Ketika mouse meninggalkan elemen
                $(this).css({
                    'transform': 'scale(1)',  // Kembalikan ukuran semula
                    'box-shadow': 'none'  // Hilangkan bayangan
                });
            }
        );

        $(function() {
        $('.frame').click(function() {
            $('#preview-frame').attr('src', $(this).find('img').attr('src'));
        });
    });
    });
</script>