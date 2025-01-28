<script>
    $('#tgl').daterangepicker({
        singleDatePicker: true,
        endDate: moment()
    });

    $("#generate").on("click", function() {
        $.ajax({
            url: "<?= BASE_URL ?>admin/voucher/store",
            type: "POST", // Method POST
            data: {
                diskon: $('#diskon').val(),
                expired: $('#tgl').val()
            },
            success: function(response) {
                mdata = JSON.parse(response)
                if (mdata.code != 201) {
                    $("#failedtoast .toast-body").text(mdata.message);
                    $("#failedtoast").toast('show');
                } else {
                    $("#successtoast .toast-body").text(mdata.message);
                    $("#successtoast").toast('show');
                }
            },
            error: function(xhr, status, error) {
                // Jika terjadi error
                console.error("Error:", error);
                alert("Terjadi kesalahan saat mengirim data.");
            }
        });
    });
</script>