<script>
    $(function() {
		setTimeout(() => {
			$("#failedtoast").toast('show')
			$("#successtoast").toast('show')
		}, 0)
	});
    
    function previewImage(input) {
        const file = input.files[0];

        if (file) {
            const imgPreview = document.querySelector('.img-preview');
            const blob = URL.createObjectURL(file);
            imgPreview.classList.add("mb-3");
            imgPreview.src = blob;
        }
        }
</script>