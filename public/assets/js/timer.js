let waktu = parseInt($('#timer').data('time')) || 1;
let countdownElement = document.getElementById("countdown");

function formatWaktu(seconds) {
  let menit = Math.floor(seconds / 60);
  let detik = seconds % 60;
  return `${String(menit).padStart(2, "0")}:${String(detik).padStart(2, "0")}`; // Format 00:00
}

countdownElement.textContent = formatWaktu(waktu);

let interval = setInterval(() => {
  waktu--;
  countdownElement.textContent = formatWaktu(waktu);

  if (waktu == 30) {
    alertSwal();
  }

  if (waktu <= 0) {
    clearInterval(interval);
    window.location.href = window.location.origin;
  }
}, 1000);

function alertSwal() {
  Swal.fire({
    title: "Peringatan!",
    text: "Waktu akan segera berakhir",
    icon: "warning",
    showConfirmButton: true,
  });
}
