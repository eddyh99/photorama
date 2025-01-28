<script>
    $(function() {
		setTimeout(() => {
			$("#failedtoast").toast('show')
			$("#successtoast").toast('show')
		}, 0)
	});
    
    function previewImage() {
            const img = document.querySelector('#file');
            const imgPreview = document.querySelector('.img-preview');
            const blob = URL.createObjectURL(img.files[0]);
            imgPreview.classList.add("mb-3");
            imgPreview.src = blob;
        }
</script>