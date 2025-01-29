<script>
    $('#tgl').daterangepicker({
        singleDatePicker: true,
        endDate: moment()
    });

    var table = $('#table_list').DataTable({
		"scrollX": true,
		"dom": 'lBfrtip',
		"lengthMenu": [
			[10, 25, 50, -1],
			['10 rows', '25 rows', '50 rows', 'Show all']
		],
		"ajax": {
			"url": "<?= BASE_URL ?>admin/voucher/getAll",
			"type": "GET",
			"dataSrc": function(data) {
				console.log(data);
				return data;
			}
		},
		"columns": [
            {
				data: 'kode_voucher'
			},
            {
				data: 'expired'
			},
            { 
                data: 'potongan_harga',
                render: function(data, type, row) {
                    return parseInt(data).toLocaleString('id-ID');
                }
            },
			{
				data: null,
				"mRender": function(data, type, full, meta) {
					var del = `<button onclick='deleteVoucher("`+data.kode_voucher+`")' class="btn">
                                    <i class="bx bx-trash bx-md fs-5 text-danger"></i>
                                </button>`;
					return `${del}`;
				}
			},
		],
	});

    function deleteVoucher(kode) {
        $.get("<?= BASE_URL?>admin/voucher/destroy/" +kode, function(data, status) {
            mdata = JSON.parse(data);
            if(mdata.kode == 201) {
                $("#failedtoast .toast-body").text(mdata.message);
                $("#failedtoast").toast('show');
            } else {
                $("#successtoast .toast-body").text(mdata.message);
                $("#successtoast").toast('show');
                table.ajax.reload();
            }
        });
    }

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
                    table.ajax.reload();
                }
            },
            error: function(xhr, status, error) {
                // Jika terjadi error
                console.error("Error:", error);
                alert("Terjadi kesalahan saat mengirim data.");
            }
        });
    });

    $("#diskon").on("input", function () {
        $("#generate").prop("disabled", !$(this).val());
    });
    $("#price").on("input", function () {
        $("#update").prop("disabled", !$(this).val());
    });
</script>