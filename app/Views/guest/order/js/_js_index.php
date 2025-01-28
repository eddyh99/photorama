<script>
    // custom background
    $(function() {
        const bg = <?= $background ? json_encode($background) : 'null'; ?>;
        $("#content-bg").css("background-image", bg ? `url('${bg}')` : 'none');

        setTimeout(() => {
			$("#failedtoast").toast('show')
		}, 0)

        let count = 1;
        const IDR = new Intl.NumberFormat('id-ID');

        // Fungsi untuk menambah
        $("#increase").click(function() {
            count++;
            $("#count").text(count);
            $("#price").text(IDR.format(count * 17500));
        });

        // Fungsi untuk mengurangi
        $("#decrease").click(function() {
            if (count > 1) {
                count--;
                $("#count").text(count);
                $("#price").text(IDR.format(count * 17500));
            }
        });

        $("#next").on('click', () => {
            let price = $("#price").text();
            const voucher = $("#voc").val();
            let priceFormatted = parseInt(price.replace(/\./g, ''))
            let url = "<?= BASE_URL ?>payment/" + encodeURIComponent(btoa(priceFormatted));
            if (voucher) {
                url += `?voucher=${voucher}`;
            }
            window.location.href = url
        });
    });
</script>