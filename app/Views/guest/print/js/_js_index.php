<script src="<?= BASE_URL ?>assets/js/payment-check.js"></script>
<script>
    let videoBlob = null;
    const dir = btoa(<?= json_encode($dir) ?>);
    const print = sessionStorage.getItem('print') || 1;
    console.log(print);


    function redirecTo() {
        save();
    }

    $(function async () {
        const img = $("#photo").attr('src');
        const autoPrint = <?= json_encode($auto_print) ?>;

        function printImage() {
            console.log(print);

            $.ajax({
                url: "<?= BASE_URL ?>home/cetakPDF/" + dir + "/" + print,
                type: "GET",
                success: function(response) {
                    const mdata = JSON.parse(response)
                    if (mdata.status === "success") {
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'Foto berhasil dicetak.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        })

                    } else {
                        alert("Gagal print Foto!");
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan saat menyimpan PDF.");
                }
            })
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
    });

    function save(e) {
        if (!navigator.onLine) {
            e.preventDefault(); // Mencegah navigasi jika offline
            alert('No internet connection. Please check your network and try again.');
        } else {
            Swal.fire({
                title: "Please be patient",
                text: "finishing..",
                didOpen: () => {
                    Swal.showLoading();
                },
            });
        }
    }
</script>