const overlay = document.getElementById("offlineOverlay");

function updateOverlayState() {
    if (!navigator.onLine) {
        overlay.style.display = "flex"; // Show when offline
    } else {
        overlay.style.display = "none"; // Hide when online
    }
}

// Initial check when page loads
updateOverlayState();

// Listen for network status changes
window.addEventListener("offline", function() {
    alert("Terjadi gangguan koneksi internet, silahkan tunggu!");
    updateOverlayState();
});

window.addEventListener("online", function() {
    alert("Kembali online! silahkan melanjutkan.");
    updateOverlayState();
});