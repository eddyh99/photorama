<script>
    function redirecTo() {
        window.location.href = '<?= BASE_URL ?>';
    }
    // custom background
    $(function() {
        const prints = <?= json_encode($print) ?>;

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
            $.get("<?= BASE_URL ?>payment/check/" + <?= $inv ?> + "/" + prints, function(data, status) {
                mdata = JSON.parse(data);
                if (mdata.status == 'paid') {
                    sessionStorage.setItem('is_paid', true);
                    if (!navigator.onLine) {
                        alert('No internet connection. Please check your network and try again.');
                        return;
                    } else {
                        window.location.href = '<?= BASE_URL ?>frame';
                    }
                }
            });
        }
        setInterval(checkPaymentStatus, 5000);
    });
</script>