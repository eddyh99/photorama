<script>
    sessionStorage.removeItem('is_paid');
    sessionStorage.removeItem('camera');
    sessionStorage.removeItem('selected_frame');
    localStorage.removeItem("sisa_waktu");
    const disable_payment = localStorage.getItem("disable_payment") === "true";
    $("#start-btn").attr("href", '<?= BASE_URL ?>' + (disable_payment ? 'frame' : 'order' ));
</script>