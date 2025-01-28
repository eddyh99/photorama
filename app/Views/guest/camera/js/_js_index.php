<script>
    // custom background
    $(function() {
        const bg = <?= $background ? json_encode($background) : 'null'; ?>;
        $("#content-bg").css("background-image", bg ? `url('${bg}')` : 'none');

        function startCamera(id) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                const videoElement = document.getElementById(id);
                videoElement.srcObject = stream;
            })
            .catch(function(error) {
                console.log("Error accessing camera: ", error);
            });
    }

    // Start cameras
    startCamera("camera1");
    startCamera("camera2");
    });
</script>