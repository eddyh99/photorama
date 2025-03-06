<script>
	$(function() {
		setTimeout(() => {
			$("#failedtoast").toast('show')
			$("#successtoast").toast('show')
		}, 0)
	});
	var table = $('#table_list_bg').DataTable({
		"scrollX": true,
		"dom": 'lBfrtip',
		"lengthMenu": [
			[10, 25, 50, -1],
			['10 rows', '25 rows', '50 rows', 'Show all']
		],
		"ajax": {
			"url": "<?= BASE_URL ?>admin/photo/list",
			"type": "POST",
			"data": function(d) {
				let selectedOption = $('#cabang').find(':selected');
				d.cabang = selectedOption.val();
				d.is_event = selectedOption.data('is-event');

			},
			"dataSrc": function(data) {
				console.log(data);
				return data;
			}
		},
		"columns": [{
				data: 'user',
				render: function(data, type, row) {
					return row.is_event ? data + '<span class="badge bg-primary text-lowercase ms-2">event</span>': data
				}
			},
			{
				data: 'thumbnail',
				render: function(data, type, row) {
					return `<img src="<?= BASE_URL ?>assets/photobooth/${data}" alt="thumbnail" style="width: 50px; height: 50px;" onclick="showPhoto('${encodeURIComponent(data)}')">`;
				}
			},
			{
				data: 'date'
			},
			{
				data: null,
				"mRender": function(data, type, full, meta) {
					var download = `<a href="${data.url_download}" target="_blank">
                                                <i class="bx bx-link-external bx-md fs-5 text-primary"></i>
                                          </a>`;
					var del = `<a href="${data.url_delete}">
                                                <i class="bx bx-trash bx-md fs-5 text-danger"></i>
                                          </a>`;
					return `${download} ${del}`;
				}
			},
		],
	});

	$("#lihat").on("click", function() {
		table.ajax.reload();
	});

	function showPhoto(src) {
		const photo = `<img src="<?= BASE_URL ?>assets/photobooth/${src}" alt="thumbnail" style="width: 100%; height: auto;">`;
		$('#modalDataBody').html(photo);
		$("#photoModal").modal('show');
	}


	$("#auto-print").on("change", function() {
		$("#form").submit();
	});
</script>