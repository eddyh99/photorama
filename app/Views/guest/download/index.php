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
        position: relative;
    }

    .swiper-slide img,
    .swiper-slide video {
        display: block;
        width: 85%;
        height: auto;
        object-fit: contain;
    }

    .mySwiper {
        width: 50%;
        height: auto;
        box-sizing: border-box;
        padding: 10px 0;
    }

    .mySwiper .swiper-slide {
        height: 20%;
        width: 100%;
    }
    .download-btn {
        position: absolute;
        top: 10px;
        right: 50px;
        padding: 5px 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }
</style>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <?php if (empty($files)): ?>
            <h2 class="text-center mt-4">404</h2>
            <p class="text-center">Not Found</p>
        <?php else: ?>
            <h4 class="text-center my-0"><?= $folder ?></h4>

            <!-- Main slider -->
            <div class="swiper mySwiper2">
                <div class="swiper-wrapper">
                    <?php foreach ($files as $file): ?>
                        <div class="swiper-slide">
                            <?php
                            $ext = pathinfo($file, PATHINFO_EXTENSION);
                            $fileUrl = base_url("assets/photobooth/$folder/$file");
                            if (in_array(strtolower($ext), ['mp4', 'webm'])):
                            ?>
                                <video controls playsinline>
                                    <source src="<?= $fileUrl ?>" type="video/<?= $ext ?>">
                                    Your browser does not support the video tag.
                                </video>
                                <a href="<?= $fileUrl ?>" class="download-btn" download><i class="bx bx-download"></i></a>
                            <?php else: ?>
                                <img src="<?= $fileUrl ?>" alt="Random Image">
                                <a href="<?= $fileUrl ?>" class="download-btn" download><i class="bx bx-download"></i></a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-prev text-primary"></div>
                <div class="swiper-button-next text-primary"></div>
            </div>

            <!-- Thumbnail slider -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($files as $file): ?>
                        <div class="swiper-slide">
                            <?php
                            $ext = pathinfo($file, PATHINFO_EXTENSION);
                            $fileUrl = base_url("assets/photobooth/$folder/$file");
                            if (in_array(strtolower($ext), ['mp4', 'webm'])):
                            ?>
                                <img src="<?= base_url('assets/img/thumb-video.jpg') ?>" alt="Video">
                            <?php else: ?>
                                <img src="<?= $fileUrl ?>" alt="Random Image">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>
