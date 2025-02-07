<script>
    $(function() {
        setTimeout(() => {
            $("#failedtoast").toast('show')
            $("#successtoast").toast('show')
        }, 0)
    });
</script>

<script>
        const imgPreview = document.querySelector('.img-preview');
        const canvas = document.getElementById("canvas");
        const koordinat = document.getElementById("koordinat");
        const predefinedAreas = []; // Store predefined areas
        let selectedArea = null; // Track the currently selected area

        // Add a new area
        document.getElementById("addArea").addEventListener("click", function() {
            createArea(50, 50, 100, 100);
        });

        // Create a draggable and resizable area
        function createArea(x, y, width, height) {
            const area = document.createElement("div");
            area.className = "frame-area";
            area.style.left = `${x}px`;
            area.style.top = `${y}px`;
            area.style.width = `${width}px`;
            area.style.height = `${height}px`;
            canvas.appendChild(area);

            // Add resize handles
            const handles = ["top-left", "top-right", "bottom-left", "bottom-right", "top", "bottom", "left", "right"];
            handles.forEach(handleType => {
                const handle = document.createElement("div");
                handle.className = `resize-handle ${handleType}`;
                handle.dataset.handle = handleType;
                area.appendChild(handle);
            });

            // Make the area draggable and resizable
            makeDraggable(area);
            makeResizable(area);

            // Add double-click event to remove the area
            area.addEventListener("dblclick", function() {
                canvas.removeChild(area);
                predefinedAreas.splice(predefinedAreas.indexOf(area), 1);
            });

            // Add the area to predefinedAreas
            predefinedAreas.push(area);
        }

        // Make an area draggable
        function makeDraggable(area) {
            let offsetX, offsetY, isDragging = false;

            area.addEventListener("mousedown", function(e) {
                if (e.target.classList.contains("resize-handle")) return;
                isDragging = true;
                const rect = area.getBoundingClientRect();
                offsetX = e.clientX - rect.left;
                offsetY = e.clientY - rect.top;
                area.style.cursor = "grabbing";
            });

            document.addEventListener("mousemove", function(e) {
                if (!isDragging) return;
                const x = e.clientX - offsetX - canvas.getBoundingClientRect().left;
                const y = e.clientY - offsetY - canvas.getBoundingClientRect().top;
                area.style.left = `${Math.max(0, Math.min(canvas.clientWidth - area.clientWidth, x))}px`;
                area.style.top = `${Math.max(0, Math.min(canvas.clientHeight - area.clientHeight, y))}px`;
            });

            document.addEventListener("mouseup", function() {
                isDragging = false;
                area.style.cursor = "default";
            });
        }

        // Make an area resizable
        function makeResizable(area) {
            let isResizing = false;
            let handleType = "";

            area.addEventListener("mousedown", function(e) {
                if (e.target.classList.contains("resize-handle")) {
                    isResizing = true;
                    handleType = e.target.dataset.handle;
                    e.preventDefault();
                }
            });

            document.addEventListener("mousemove", function(e) {
                if (!isResizing) return;
                const rect = area.getBoundingClientRect();
                const canvasRect = canvas.getBoundingClientRect();
                let newX = rect.left - canvasRect.left;
                let newY = rect.top - canvasRect.top;
                let newWidth = rect.width;
                let newHeight = rect.height;

                if (handleType.includes("left")) {
                    newWidth = rect.right - e.clientX;
                    newX = e.clientX - canvasRect.left;
                }
                if (handleType.includes("right")) {
                    newWidth = e.clientX - rect.left;
                }
                if (handleType.includes("top")) {
                    newHeight = rect.bottom - e.clientY;
                    newY = e.clientY - canvasRect.top;
                }
                if (handleType.includes("bottom")) {
                    newHeight = e.clientY - rect.top;
                }

                if (newWidth > 30) {
                    area.style.width = `${newWidth}px`;
                    if (handleType.includes("left")) area.style.left = `${newX}px`;
                }
                if (newHeight > 30) {
                    area.style.height = `${newHeight}px`;
                    if (handleType.includes("top")) area.style.top = `${newY}px`;
                }
            });

            document.addEventListener("mouseup", function() {
                isResizing = false;
            });
        }

        // Clear all areas
        document.getElementById("clearButton").addEventListener("click", function() {
            predefinedAreas.forEach(area => canvas.removeChild(area));
            predefinedAreas.length = 0; // Clear the array
        });

        // Save predefined areas (mock implementation)
        document.getElementById("saveButton").addEventListener("click", function() {
            if (predefinedAreas.length === 0) {
                alert("Please define at least one area.");
                return;
            }

            // Ambil ukuran asli dan ukuran tampilan gambar
            const originalWidth = parseInt(imgPreview.dataset.originalWidth);
            const originalHeight = parseInt(imgPreview.dataset.originalHeight);
            const displayWidth = imgPreview.clientWidth;
            const displayHeight = imgPreview.clientHeight;

            // Hitung skala gambar
            const scaleX = originalWidth / displayWidth;
            const scaleY = originalHeight / displayHeight;

            const frameData = {
                areas: predefinedAreas.map(area => ({
                    x: parseInt(area.style.left) * scaleX, // Sesuaikan dengan skala asli
                    y: parseInt(area.style.top) * scaleY,
                    width: parseInt(area.style.width) * scaleX,
                    height: parseInt(area.style.height) * scaleY
                }))
            };
            console.log("Frame Data:", frameData); // Log the data for debugging
            koordinat.value = JSON.stringify(frameData);
            alert("Frame areas saved successfully!");
        });

        function previewImage() {
            canvas.style.width = "600px";
            canvas.style.height = "400px";
            const img = document.querySelector('#file');
            const blob = URL.createObjectURL(img.files[0]);
            imgPreview.src = blob;
            imgPreview.onload = function() {
                console.log(imgPreview.clientWidth);
                canvas.style.width = imgPreview.offsetWidth + "px";
                canvas.style.height = imgPreview.offsetHeight + "px";

                // Simpan ukuran asli gambar
                imgPreview.dataset.originalWidth = imgPreview.naturalWidth;
                imgPreview.dataset.originalHeight = imgPreview.naturalHeight;
            };
        }
    </script>