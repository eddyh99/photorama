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
</style>';
if (isset($content)) {
    echo view($content);
}
require_once(__DIR__ . '/../admin/layout/footer.php');

