<script>
        const frame = <?= json_encode($frame) ?> || null;
        let video1 = document.getElementById("camera1");
        let video2 = document.getElementById("camera2");
        let devices = [];

        window.onload = async function () {
            devices = await getCameraDevices();
            if (devices.length > 0) {
                captureCamera(video1, devices[0].deviceId);
                if (devices.length > 1) {
                    captureCamera(video2, devices[1].deviceId);
                } else {
                    setBlackScreen(video2);
                }
            } else {
                alert("Tidak ada kamera terdeteksi.");
            }
        }

        
        async function getCameraDevices() {
            try {
                await navigator.mediaDevices.getUserMedia({ video: true });
                let allDevices = await navigator.mediaDevices.enumerateDevices();
                return allDevices.filter(device => device.kind === 'videoinput');
            } catch (error) {
                console.error("Akses kamera ditolak:", error);
                return [];
            }
        }

        function captureCamera(video, deviceId) {
            navigator.mediaDevices.getUserMedia({
                video: { deviceId: { exact: deviceId } }
            }).then(stream => {
                video.srcObject = stream;
            }).catch(error => {
                console.error('Gagal mengakses kamera:', error);
                setBlackScreen(video);
            });
        }

        function setBlackScreen(video) {
            let blackCanvas = document.createElement("canvas");
            blackCanvas.width = 640;
            blackCanvas.height = 480;
            let ctx = blackCanvas.getContext("2d");
            ctx.fillStyle = "black";
            ctx.fillRect(0, 0, blackCanvas.width, blackCanvas.height);
            video.srcObject = blackCanvas.captureStream();
        }

        function setCamera(index) {
        if (devices.length >= index) {
            let selectedDeviceId = devices[index - 1].deviceId;
            sessionStorage.setItem("camera", selectedDeviceId);
            window.location.href = '<?= BASE_URL ?>capture/' +frame;
        } else {
            alert('cant select camera');
            
        }

    }
</script>