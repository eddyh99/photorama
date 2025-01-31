<script>
    // custom background
    $(function() {
        const bg = <?= $background ? json_encode($background) : 'null'; ?>;
        $("#content-bg").css("background-image", bg ? `url('${bg}')` : 'none');

        setTimeout(() => {
            $("#successtoast").toast('show')
        }, 0)

        let count = 1;
        const IDR = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        });

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

        function checkPaymentStatus() {
            $.get("<?= BASE_URL?>payment/check/" +<?= $inv ?>, function(data, status) {
                mdata = JSON.parse(data);
                if(mdata.status == 'paid') {
                    window.location.href = '<?= BASE_URL ?>frame';
                }
        });
        }
        setInterval(checkPaymentStatus, 5000);
    });

</script>