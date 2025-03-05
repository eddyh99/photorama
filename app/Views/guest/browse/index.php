<div class="container mt-5">
    <h2 class="text-center fw-bold text-dark mb-4">🗂 <?= esc($event) ?></h2>
    
    <!-- Tombol Download Semua File -->
    <div class="text-end mb-4">
        <a href="<?= base_url('home/download_all/' . $event) ?>" class="btn btn-primary btn-sm rounded-pill">
            <i class="bi bi-file-earmark-zip me-2"></i> Download All as ZIP
        </a>
    </div>

    <!-- Daftar File -->
    <div class="list-group rounded-4 overflow-hidden border">
        <?php foreach ($files as $file): ?>
            <a href="<?= base_url('download/' . base64_encode($event . '/' . $file)) ?>" 
               class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 px-4 border-0 file-item"
               target="_self">
                <div class="d-flex align-items-center">
                    <i class="bi bi-file-earmark-text fs-5 me-3 text-secondary"></i>
                    <span class="fw-medium text-dark"><?= esc($file) ?></span>
                </div>
                <span class="badge bg-primary rounded-pill px-3 py-2">⬇ Download</span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<style>
    /* Gaya seperti macOS */
    body {
        background-color: #f5f5f7; /* Warna latar belakang macOS */
    }

    .container {
        max-width: 800px;
    }

    .list-group {
        background-color: #ffffff; /* Warna latar belakang item */
        border: 1px solid #e0e0e0; /* Border halus */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Shadow halus */
    }

    .file-item {
        background-color: #ffffff;
        border-bottom: 1px solid #e0e0e0; /* Garis pemisah antar item */
        transition: all 0.2s ease-in-out;
    }

    .file-item:last-child {
        border-bottom: none; /* Hapus border pada item terakhir */
    }

    .file-item:hover {
        background-color: #f8f9fa; /* Warna latar saat hover */
        transform: translateX(5px); /* Sedikit bergeser ke kanan saat hover */
    }

    .btn-primary {
        background-color: #007aff; /* Warna biru macOS */
        border: none;
        font-size: 14px;
        padding: 8px 16px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0063cc; /* Warna biru lebih gelap saat hover */
    }

    .badge {
        font-size: 12px;
        font-weight: 500;
        background-color: #007aff; /* Warna biru macOS */
    }

    .rounded-4 {
        border-radius: 12px; /* Sudut melengkung seperti macOS */
    }

    .rounded-pill {
        border-radius: 20px; /* Sudut melengkung untuk tombol dan badge */
    }

    .text-dark {
        color: #1d1d1f !important; /* Warna teks gelap seperti macOS */
    }

    .text-secondary {
        color: #6e6e73 !important; /* Warna teks sekunder */
    }
</style>