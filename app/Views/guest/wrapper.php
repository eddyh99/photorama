<?php 
require_once(__DIR__ . '/../admin/layout/header.php');
echo '<style>
    /* CSS Kustom untuk Notyf */
    .notyf__toast {
    background-color: #d9534f; /* Warna latar belakang kuning */
    color: white; /* Warna teks putih */
    border-radius: 8px; /* Sudut yang lebih bulat */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Bayangan yang lebih menonjol */
}
    .offline-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7); /* Dark overlay */
    color: white;
    font-size: 18px;
    display: flex; /* Prevents it from being removed */
    align-items: center;
    justify-content: center;
    z-index: 9999;
    text-align: center;
    font-weight: bold;
}
</style>
    <div id="offlineOverlay" class="offline-overlay">
        <p>No internet connection. Please check your network.</p>
    </div>
<script src="' . BASE_URL . 'assets/js/connectivity-check.js"></script>
';
if (isset($content)) {
    echo view($content);
}
require_once(__DIR__ . '/../admin/layout/footer.php');

