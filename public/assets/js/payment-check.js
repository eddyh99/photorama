// Mengambil nilai dari sessionStorage
const isPaid = sessionStorage.getItem('is_paid');

fetch('home/get_payment_status')
    .then(response => {
        if (!response.ok) {
            throw new Error('Server is busy');
        }
        return response.json();
    })
    .then(data => {
        const payment_on = data.status;
        console.log(payment_on);

        if (!isPaid && payment_on) {
            Swal.fire({
                title: 'Harap lakukan pembayaran',
                text: 'Silakan bayar untuk melanjutkan.',
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = window.location.origin;
            });
        }
    })
    .catch(error => {
        alert('Server sibuk, coba lagi nanti');
        window.location.href = window.location.origin;
    });
