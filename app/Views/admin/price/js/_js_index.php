<script>
	$(function() {
		setTimeout(() => {
			$("#failedtoast").toast('show')
			$("#successtoast").toast('show')
		}, 0)
	});
	$('#table_list_bg').DataTable({
		"scrollX": true,
		"dom": 'lBfrtip',
		"lengthMenu": [
			[10, 25, 50, -1],
			['10 rows', '25 rows', '50 rows', 'Show all']
		],
		"ajax": {
			"url": "<?= BASE_URL ?>admin/price/get_all",
			"type": "GET",
			"dataSrc": function(data) {
				console.log(data);
				return data;
			}
		},
		"columns": [{
				data: 'nama_cabang'
			},
			{
				data: 'lokasi'
			},
			{ 
                data: 'harga',
                render: function(data, type, row) {
                    return 'Rp ' + parseInt(data).toLocaleString('id-ID');
                }
            },
			{
				data: null,
				"mRender": function(data, type, full, meta) {
					var edit = `<a href="#">
                                                <i class="bx bx-edit bx-md fs-5 text-black"></i>
                                          </a>`;
					var del = `<a href="<?= BASE_URL ?>admin/background/destroy/${encodeURI(btoa(data.id))}" class="del-data">
                                                <i class="bx bx-trash bx-md fs-5 text-danger"></i>
                                          </a>`;
					return `${edit} ${del}`;
				}
			},
		],
	});

	$(document).on("click", ".del-data", function(e) {
		e.preventDefault();
		let url_href = $(this).attr('href');
		Swal.fire({
			text: "Apakah anda yakin menghapus data ini?",
			type: "warning",
			position: 'center',
			showCancelButton: true,
			confirmButtonText: "Hapus",
			cancelButtonText: "Batal",
			confirmButtonColor: '#FA896B',
			closeOnConfirm: true,
			showLoaderOnConfirm: true,
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				document.location.href = url_href;
			}
		})
	});
</script>