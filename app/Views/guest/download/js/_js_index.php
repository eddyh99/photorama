<script>
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true
    });
    var swiper = new Swiper(".mySwiper2", {
        loop: true,
        spaceBetween: 10,
        navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
        },
        thumbs: {
            swiper: swiper
        }
    });

    function printImage(url) {
        console.log(url);
        
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

            printWindow.document.write(`<img src="${url}"`);
            printWindow.document.write(`</body></html>`);
            printWindow.document.close();

            printWindow.focus();

            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 500);
        }
</script>