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
			"url": "<?= BASE_URL ?>admin/photo/list",
			"type": "GET",
			"dataSrc": function(data) {
				console.log(data);
				return data;
			}
		},
		"columns": [{
				data: 'user'
			},
			{
				data: 'date'
			},
			{
				data: null,
				"mRender": function(data, type, full, meta) {
					var download = `<a href="${data.url}" target="_blank">
                                                <i class="bx bx-link-external bx-md fs-5 text-primary"></i>
                                          </a>`;
					return download;
				}
			},
		],
	});

</script>