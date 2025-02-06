</div>
</div>
<style>
    .swiper {
        width: 100%;
        height: 100%;
    }

    .mySwiper2 {
        height: 80%;
        width: 50%;
    }

    .swiper-slide {
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .mySwiper {
        width: 20%;
        height: 50%;
        box-sizing: border-box;
        padding: 10px 0;
    }

    .mySwiper .swiper-slide {
        height: 20%;
        width: 100%;
    }
</style>
<?php if (empty($files)) : ?>
    <h2 class="text-center mt-4">404</h2>
    <p class="text-center">Not Found</p>
<?php else: ?>
    <!-- Slider main container -->
    <div class="swiper mySwiper2">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide1/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide2/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide3/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide4/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide5/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide6/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide7/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide8/800/600" alt="Random Image">
            </div>

        </div>
        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev text-white"></div>
        <div class="swiper-button-next text-white"></div>
    </div>

    <!-- Slider main container -->
    <div thumbSlider="" class="swiper mySwiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide1/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide2/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide3/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide4/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide5/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide6/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide7/800/600" alt="Random Image">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/seed/slide8/800/600" alt="Random Image">
            </div>
        <?php endif; ?>