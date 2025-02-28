<script src="<?= BASE_URL ?>assets/js/payment-check.js"></script>
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
</script>