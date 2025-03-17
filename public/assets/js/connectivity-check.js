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
    alert("Network connection lost. Please check your internet.");
    updateOverlayState();
});

window.addEventListener("online", function() {
    alert("Back online! You can continue browsing.");
    updateOverlayState();
});