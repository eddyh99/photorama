<script>
    let selectedFrame;
    function redirecTo() {
        sessionStorage.setItem("selected_frame", selectedFrame);
        window.location.href = '<?= BASE_URL ?>camera';
    }
    // custom background
    $(function() {
        let id;

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