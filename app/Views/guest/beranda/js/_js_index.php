<script>
    sessionStorage.removeItem('is_paid');
    sessionStorage.removeItem('camera');
    sessionStorage.removeItem('selected_frame');
    sessionStorage.removeItem("print");
    localStorage.removeItem("sisa_waktu");

    $('#btn-start').on('click', function(e) {
    if (!navigator.onLine) {
        e.preventDefault(); // Mencegah navigasi jika offline
        alert('No internet connection. Please check your network and try again.');
    } else {
        window.location.href = "<?= BASE_URL . ($payment_on ? 'order' : 'frame') ?>";
    }
});

</script>