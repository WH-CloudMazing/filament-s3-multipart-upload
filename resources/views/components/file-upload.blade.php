<div>
    {{ $property }}
    <div class="input--uppy">
    </div>
</div>

<link href="https://releases.transloadit.com/uppy/v2.3.0/uppy.min.css" rel="stylesheet">
<script src="https://releases.transloadit.com/uppy/v2.3.0/uppy.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        const { Core, DragDrop, AwsS3Multipart } = Uppy

        const uppy = new Core()

        uppy.use(DragDrop, {
            target: '.input--uppy' ,
        })

        uppy.use(AwsS3Multipart, {
            limit: 1,
        })
    })
</script>
