<div class="UppyDragDrop">
</div>

<link href="https://releases.transloadit.com/uppy/v2.3.0/uppy.min.css" rel="stylesheet">
<script src="https://releases.transloadit.com/uppy/v2.3.0/uppy.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        var uppy = new Uppy.Core()
        uppy.use(Uppy.DragDrop, { target: '.UppyDragDrop' })
        uppy.use(Uppy.AwsS3Multipart)
    })
</script>
