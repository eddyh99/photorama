<style>
    .swiper {
        width: 100%;
        height: 100%;
    }

    .mySwiper2 {
        height: 70vh; /* Tinggi slider utama */
        width: 100%;
        margin-bottom: 20px;
    }

    .swiper-slide {
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        background-color: #f8f9fa; /* Warna latar belakang slide */
        border-radius: 10px; /* Sudut melengkung */
        overflow: hidden; /* Memastikan konten tidak keluar dari area slide */
    }

    .swiper-slide img,
    .swiper-slide video {
        display: block;
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 10px; /* Sudut melengkung untuk media */
    }

    .mySwiper {
        width: 100%;
        height: 100px; /* Tinggi thumbnail slider */
        box-sizing: border-box;
        padding: 10px 0;
    }

    .mySwiper .swiper-slide {
        opacity: 0.6; /* Opacity untuk thumbnail non-aktif */
        transition: opacity 0.3s ease;
        cursor: pointer;
        border-radius: 5px; /* Sudut melengkung untuk thumbnail */
    }

    .mySwiper .swiper-slide:hover {
        opacity: 1; /* Opacity penuh saat dihover */
    }

    .mySwiper .swiper-slide-thumb-active {
        opacity: 1; /* Opacity penuh untuk thumbnail aktif */
    }

    .action-btn {
        position: absolute;
        top: 10px;
        padding: 8px 16px;
        background-color: #007bff; /* Warna biru */
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.3s ease;
        z-index: 10; /* Pastikan tombol di atas konten */
    }

    .download-btn:hover {
        background-color: #0056b3; /* Warna biru lebih gelap saat hover */
    }

    .swiper-button-prev,
    .swiper-button-next {
        color: #007bff; /* Warna biru untuk navigasi */
        background-color: rgba(255, 255, 255, 0.8); /* Latar belakang semi-transparan */
        padding: 20px;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease;
    }

    .swiper-button-prev:hover,
    .swiper-button-next:hover {
        background-color: rgba(255, 255, 255, 1); /* Latar belakang lebih solid saat hover */
    }

    .swiper-button-prev::after,
    .swiper-button-next::after {
        font-size: 20px; /* Ukuran ikon navigasi */
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
                            $fileUrl = base_url("assets/photobooth/$folder/$file")."?".time();
                            if (in_array(strtolower($ext), ['mp4', 'webm'])):
                            ?>
                                <video controls playsinline>
                                    <source src="<?= $fileUrl ?>" type="video/<?= $ext ?>">
                                    Your browser does not support the video tag.
                                </video>
                                <a href="<?= $fileUrl ?>" class="action-btn" style="right: 10px;" download><i class="bx bx-download"></i></a>
                            <?php else: ?>
                                <!-- <a href="#" class="download-btn"><i class="bx bx-print"></i> Print</a> -->
                                <img src="<?= $fileUrl ?>" alt="Random Image">
                               <?php if($file == 'photos.jpg'):  ?>
                                    <button class="action-btn" style="left: 10px;"><i class="bx bx-printer" onclick="printImage('<?= $fileUrl ?>')"></i></button>
                               <?php endif ?>
                                <a href="<?= $fileUrl ?>" class="action-btn" style="right: 10px;" download><i class="bx bx-download"></i></a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
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