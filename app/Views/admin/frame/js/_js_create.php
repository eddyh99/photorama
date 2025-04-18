<!-- SELECT2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        setTimeout(() => {
            $("#failedtoast").toast('show')
            $("#successtoast").toast('show')
        }, 0)

        $('.cabangselect2').select2({
            placeholder: "Pilih Cabang",
            allowClear: true,
            width: "100%"
        });
    });
</script>

<script>
    const imgPreview = document.querySelector('.img-preview');
    const canvas = document.getElementById("canvas");
    const koordinat = document.getElementById("koordinat");
    const predefinedAreas = []; // Store predefined areas
    let selectedArea = null; // Track the currently selected area

    $("#canvasbackground").change(function () {
        let selectedValue = $(this).val();
        
        if (selectedValue === "light") {
            $("#canvas").css("background-color", "white");
        } else if (selectedValue === "dark") {
            $("#canvas").css("background-color", "black");
        }
    });
    
    // Add a new area
    document.getElementById("addArea").addEventListener("click", function() {
        createArea(50, 50, 100, 100);
        $('#saveButton').prop('disabled', false);
    });

    // Create a draggable and resizable area
    async function createArea(x, y, width, height) {
        const {
            value: index
        } = await Swal.fire({
            title: "#Photos",
            input: "number",
            inputLabel: "(hanya angka)",
            inputPlaceholder: "Contoh: 1",
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return "Index tidak boleh kosong!";
                }
                if (isNaN(value)) {
                    return "Harus berupa angka!";
                }
            }
        });

        if (!index) return;

        const area = document.createElement("div");
        area.className = "frame-area";
        area.style.left = `${x}px`;
        area.style.top = `${y}px`;
        area.style.width = `${width}px`;
        area.style.height = `${height}px`;
        area.dataset.index = index;
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
        makeRotatable(area);

        // Add double-click event to remove the area
        area.addEventListener("dblclick", function() {
            canvas.removeChild(area);
            predefinedAreas.splice(predefinedAreas.indexOf(area), 1);
        });

        // Add the area to predefinedAreas
        predefinedAreas.push(area);
    }

    function makeRotatable(area) {
        let isRotating = false;
        let initialAngle = 0;
        let centerX, centerY, startAngle;

        // Tambahkan handle untuk rotasi
        const rotateHandle = document.createElement("div");
        rotateHandle.className = "rotate-handle";
        rotateHandle.style.position = "absolute";
        rotateHandle.style.width = "20px";
        rotateHandle.style.height = "20px";
        rotateHandle.style.top = "-25px";
        rotateHandle.style.left = "50%";
        rotateHandle.style.transform = "translateX(-50%)";
        rotateHandle.style.cursor = "grab";
        rotateHandle.innerHTML = '<i class="bx bx-rotate-right"></i>';
        area.appendChild(rotateHandle);

        rotateHandle.addEventListener("mousedown", function(e) {
            e.preventDefault();
            isRotating = true;
            area.classList.add("no-drag");
            const rect = area.getBoundingClientRect();
            centerX = rect.left + rect.width / 2;
            centerY = rect.top + rect.height / 2;
            const dx = e.clientX - centerX;
            const dy = e.clientY - centerY;
            startAngle = Math.atan2(dy, dx);
            initialAngle = parseFloat(area.dataset.angle) || 0;
        });

        document.addEventListener("mousemove", function(e) {
            if (!isRotating) return;
            const dx = e.clientX - centerX;
            const dy = e.clientY - centerY;
            const currentAngle = Math.atan2(dy, dx);
            const rotation = (currentAngle - startAngle) * (180 / Math.PI) + initialAngle;
            area.style.transform = `rotate(${rotation}deg)`;
            area.dataset.angle = rotation;
        });

        document.addEventListener("mouseup", function() {
            area.classList.remove("no-drag");
            isRotating = false;
        });
    }

    // Make an area draggable
    function makeDraggable(area) {
        let offsetX, offsetY, isDragging = false;

        area.addEventListener("mousedown", function(e) {
            if (e.target.classList.contains("resize-handle") || area.classList.contains("no-drag")) return;
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
                height: parseInt(area.style.height) * scaleY,
                index: parseInt(area.dataset.index),
                rotation: parseFloat(area.dataset.angle)
            }))
        };
        console.log("Frame Data:", frameData); // Log the data for debugging
        koordinat.value = JSON.stringify(frameData['areas']);
        alert("Frame areas saved successfully!");
    });

    function previewImage() {
        canvas.style.width = "600px";
        canvas.style.height = "400px";
        const img = document.querySelector('#file');
        const blob = URL.createObjectURL(img.files[0]);
        imgPreview.src = blob;
        imgPreview.onload = function() {
            canvas.style.width = imgPreview.offsetWidth + "px";
            canvas.style.height = imgPreview.offsetHeight + "px";

            // Simpan ukuran asli gambar
            imgPreview.dataset.originalWidth = imgPreview.naturalWidth;
            imgPreview.dataset.originalHeight = imgPreview.naturalHeight;
        };
    }
    
    document.getElementById("duplicateArea").addEventListener("click", function() {
        if (predefinedAreas.length === 0) {
            alert("No area to duplicate!");
            return;
        }
    
        const lastArea = predefinedAreas[predefinedAreas.length - 1]; // Get the last created area
        const rect = lastArea.getBoundingClientRect();
        
        // Create a new area slightly offset from the last one
        createArea(rect.left - canvas.getBoundingClientRect().left + 20, 
                   rect.top - canvas.getBoundingClientRect().top + 20, 
                   rect.width, 
                   rect.height);
    });
    
    // Shift + Drag Duplication
    document.addEventListener("mousedown", function(e) {
        if (e.shiftKey && e.target.classList.contains("frame-area")) {
            e.preventDefault();
            const originalArea = e.target;
            const rect = originalArea.getBoundingClientRect();
            
            // Create a duplicate where the original is
            createArea(rect.left - canvas.getBoundingClientRect().left + 20, 
                       rect.top - canvas.getBoundingClientRect().top + 20, 
                       rect.width, 
                       rect.height);
        }
    });

</script>