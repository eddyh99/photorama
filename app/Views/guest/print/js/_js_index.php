<script>
$(function() {
    const img = $("#photo").attr('src');
    const autoPrint = <?= json_encode($auto_print) ?>;
    const print = <?= json_encode(session()->get('print')) ?> || 1;

    function printImage() {
        var printWindow = window.open('', '_blank', 'width=600,height=600');

        if (!printWindow) {
            alert("Pop-up terblokir! Izinkan pop-up untuk mencetak.");
            return;
        }

        printWindow.document.write(`
            <html>
            <head>
                <title>Print Image</title>
                <style>
                    body { margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
                    @page { size: 4in 6in; margin: 0; } /* Ukuran 4R */
                    img { width: 4in; height: 6in; object-fit: contain; }
                </style>
            </head>
            <body>
        `);

        for (var i = 0; i < print; i++) {
            printWindow.document.write(`<img src="${img}" />`);
        }

        printWindow.document.write(`</body></html>`);
        printWindow.document.close();

        printWindow.focus();

        setTimeout(function() {
            printWindow.print();
            printWindow.close();
        }, 500);
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


</script>