<script src="<?= BASE_URL ?>assets/js/qz-tray.js"></script>
<script>
        $(function() {
		setTimeout(() => {
			$("#failedtoast").toast('show')
			$("#successtoast").toast('show')
		}, 0);

		$('#detect-printer').on('click', function() {
			qz.printers.getDefault().then(function(data) {
				{ $('#printer_name').val(data)}
			}).catch(alert('Falied detect printer!'));
		})
	});
</script>