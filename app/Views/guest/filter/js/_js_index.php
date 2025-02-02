<script>
        $(function() {
        const bg = <?= $background ? json_encode($background) : 'null'; ?>;
        $("#content-bg").css("background-image", bg ? `url('${bg}')` : 'none');
    });

    function grayScale() {
        document.getElementById("photo").style.filter = "grayscale(100%)";
    }

    function normal() {
        document.getElementById("photo").style.filter = "none";
    }
</script>