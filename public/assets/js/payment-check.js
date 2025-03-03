const isPaid = sessionStorage.getItem('is_paid');
const disablePayment = localStorage.getItem("disable_payment") === "true";

if (!isPaid && !disablePayment) {
    alert('Harap lakukan pembayaran');
    window.location.href = window.location.origin;
}