<script src="<?= BASE_URL ?>assets/js/payment-check.js"></script>
<script>
    let videoBlob = null;
    const dir = btoa(<?= json_encode($dir) ?>);

    function redirecTo() {
        save();
    }

    $(function async () {
        const img = $("#photo").attr('src');
        let print = sessionStorage.getItem('print') || 1;
        const btnPrint = <?= json_encode($print) ?>;

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
        if (btnPrint) {
            $('#print').removeClass('d-none');
        } else {
            setTimeout(printImage, 1000);
        }

        // Cetak manual jika tombol ditekan
        $("#print").on('click', function() {
        Swal.fire({
            title: "Masukkan jumlah cetakan",
            input: "number",
            inputAttributes: { min: 1 },
            inputValue: 1,
            showCancelButton: true,
            confirmButtonText: "Cetak",
            cancelButtonText: "Batal",
            preConfirm: (value) => {
                if (!value || isNaN(value) || value <= 0) {
                    Swal.showValidationMessage("Masukkan angka yang valid!");
                }
                return value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                print = parseInt(result.value);
                printImage();
            }
        });
    });

    });

    function save(e) {
        if (!navigator.onLine) {
            e.preventDefault(); // Mencegah navigasi jika offline
            alert('No internet connection. Please check your network and try again.');
        } else {
            window.location.href = '<?= BASE_URL ?>finish';
        }
    }
</script>