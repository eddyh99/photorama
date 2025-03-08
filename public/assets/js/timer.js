let waktu = parseInt(localStorage.getItem("sisa_waktu")) || parseInt($('#timer').data('time')) || 1;
let countdownElement = document.getElementById("countdown");
const notyf = new Notyf({
  duration: 5000,  // Notifikasi tampil selama 5 detik
  position: {
    x: "center",
    y: "top"
  },
  ripple: true,  // Efek animasi
  dismissible: true  // Bisa ditutup manual
});

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
    localStorage.removeItem("sisa_waktu");
    redirecTo();
  }
}, 1000);

function alertSwal() {
  notyf.open({
    type: "warning",
    message: "⚠️ Peringatan! Waktu akan segera berakhir."
});
  
}
