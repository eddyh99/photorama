<script>
    $(function() {
        const img = $("#photo").attr('src');
        const print = <?= json_encode(session()->get('print')) ?> || 1;

        if (print) {
            var printWindow = window.open('', '', 'width=600,height=600');

            printWindow.document.write('<html><head><title>Print Image</title>');
            printWindow.document.write('<style>body { margin: 0; padding: 0; }');
            printWindow.document.write('@page { size: 4in 6in; margin: 0; }'); // Mengatur ukuran halaman ke 4R
            printWindow.document.write('.page { page-break-after: always; display: flex; justify-content: center; align-items: center; height: 100vh; }');
            printWindow.document.write('img { width: 4in; height: 6in; object-fit: contain; }</style>');
            printWindow.document.write('</head><body>');

            for (var i = 0; i < print; i++) {
                printWindow.document.write('<div class="page">');
                printWindow.document.write('<img src="' + img + '" />');
                printWindow.document.write('</div>');
            }

            printWindow.document.write('</body></html>');
            printWindow.document.close();
            // Setelah jendela dimuat, langsung mencetak gambar
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }
    });
</script>