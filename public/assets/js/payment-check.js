const isPaid = sessionStorage.getItem('is_paid');

if(!isPaid) {
    alert('Harap lakukan pembayaran');
    window.location.href = window.location.origin;
}