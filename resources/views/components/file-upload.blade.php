<div x-data="uppy">
    <input type="hidden" name="{{ $getName() }}" x-model="path">

    <div class="input--uppy">
    </div>

    @unless($hasAwsConfigured())
        <p>No AWS S3 configuration found.</p>
    @endunless
</div>

<link href="https://releases.transloadit.com/uppy/v2.3.0/uppy.min.css" rel="stylesheet">

<script type="module">
    import { Uppy, AwsS3Multipart, DragDrop } from "https://releases.transloadit.com/uppy/v3.4.0/uppy.min.mjs"

    document.addEventListener('alpine:init', () => {
        Alpine.data('uppy', () => ({
            uppy: null,
            path: '',
            init () {
                this.uppy = new Uppy({
                    id: 'uppy',
                    debug: true,
                    restrictions: {
                        maxNumberOfFiles: 1,
                        maxFileSize: {{ $getMaxFileSize() }},
                        minNumberOfFiles: 1
                    },
                })

                uppy
                    .use(DragDrop, {target: '.input--uppy'})
                    .use(AwsS3Multipart, {
                        limit: 6,
                        companionUrl: "{{ $companionUrl() }}",
                        companionHeaders: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                    })

                uppy.on("file-added", file => {
                    uppy.upload()
                    console.log("----- on file added -----");
                });

                uppy.on("file-removed", file => {
                    console.log("----- on file removed -----");
                });

                uppy.on("upload", file => {
                    console.log("----- on upload -----");
                });

                uppy.on("upload-progress", file => {
                    console.log("----- on file added -----");
                });

                uppy.on("upload-success", (file, response) => {
                    this.path = response.body.location
                    console.log("----- on upload success -----");
                });

                uppy.on("error", (file, error, response) => {
                    console.log("----- on error -----");
                });

                uppy.on("upload-error", err => {
                    console.log("----- on upload error -----");
                });
            },
        }))
    })
</script>
