<script src="<?= BASE_URL ?>assets/js/qz-tray.js"></script>
<script>
    function detectDefaultPrinter() {
        qz.websocket.connect().then(() => {
            return qz.printers.getDefault();
        }).then((printer) => {
            $('#printer_name').val(printer)
            console.log("Default printer:", printer);
        }).catch((err) => {
            console.error("Printer detection error:", err);
        });
    }
    
    $(function() {
		setTimeout(() => {
			$("#failedtoast").toast('show')
			$("#successtoast").toast('show')
		}, 0);
	});
	
	$('#detect-printer').on('click', function() {
	    detectDefaultPrinter();
	})
</script>