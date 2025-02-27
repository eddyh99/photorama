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

</script>