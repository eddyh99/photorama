<script>
    // custom background
    $(function() {

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