<script src="<?= BASE_URL ?>assets/js/payment-check.js"></script>
<script src="<?= BASE_URL ?>assets/js/qz-tray.js"></script>
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
        const printer = <?= json_encode($printer) ?>;
        console.log(printer);

        function printImage() {
            console.log(print);

            $.ajax({
                url: "<?= BASE_URL ?>home/cetakPDF/" + dir + "/" + print,
                type: "GET",
                success: function(response) {
                    const mdata = JSON.parse(response)
                    if (mdata.status === "success") {
                        console.log(mdata.pdf_url);
                        printPDF(mdata.pdf_url, printer);
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
                inputAttributes: {
                    min: 1
                },
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


        function printPDF(pdfUrl, printerName) {
            qz.security.setCertificatePromise(function(resolve, reject) {
               fetch("assets/certs/digital-certificate.txt", {cache: 'no-store', headers: {'Content-Type': 'text/plain'}})
                  .then(function(data) { data.ok ? resolve(data.text()) : reject(data.text()); });
            });
            
            
            qz.security.setSignaturePromise(function(toSign) {
               return function(resolve, reject) {
                   fetch('/sign', {
                       method: 'POST',
                       body: JSON.stringify({ data: toSign }),
                       headers: { 'Content-Type': 'application/json' }
                   })
                   .then(response => {
                       if (!response.ok) throw new Error("Signing request failed");
                       return response.text();
                   })
                   .then(resolve)
                   .catch(reject);
               };
            });
            
            qz.websocket.connect().then(() => {
                const config = qz.configs.create(printerName);
                return qz.print(config, [{
                    type: 'pdf',
                    data: pdfUrl
                }]);
            }).then(() => {
                console.log("Print job sent successfully!");
                qz.websocket.disconnect();
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Foto berhasil dicetak.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }).catch(err => {
                console.error("Print error: ", err);
                qz.websocket.disconnect();
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal mencetak foto.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }

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