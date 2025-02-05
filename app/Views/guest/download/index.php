<div class="container">
    <div class="card">
        <div class="card-header">
            <h4><code>Filemu tersedia!</code></h4>
        </div>
        <div class="card-body">
            <?php if (empty($files)) : ?>
                <p class="no-files-message">Tidak ada file dalam folder ini.</p>
            <?php else : ?>
                <div class="list-group">
                    <?php $no = 1;
                    foreach ($files as $file) : ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?= esc($file) ?></span>
                            <a href="<?= base_url("assets/photobooth/$folder/$file") ?>" class="btn btn-download btn-sm" download>
                                <i class="bx bxs-download"></i>Unduh
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

