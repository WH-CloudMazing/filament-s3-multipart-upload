<div x-data="uppy" class="uppy">
    <input type="hidden" name="{{ $getName() }}" x-model="path">

    <div class="uppy__input">
    </div>

    <div class="uppy__progress-bar">
    </div>

    <div class="uppy__files mt-2">
        <template x-for="file in uploadedFiles" :key="file.id">
            <div class="uppy__file file" class="p-4 text-sm bg-white">
                <div>
                    <span class="file__name font-bold text-sm" x-text="file.name"></span>
                </div>

                <div class="file__meta space-x-2 text-xs text-neutral-700">
                    <span class="file__size" x-text="bytesToSize(file.size)"></span>
                    <span class="file__type" x-text="file.type"></span>
                </div>
            </div>
        </template>
    </div>

    @unless($hasAwsConfigured())
        <p>No AWS S3 configuration found.</p>
    @endunless
</div>

<link href="https://releases.transloadit.com/uppy/v2.3.0/uppy.min.css" rel="stylesheet">

<script type="module">
    import { Uppy, AwsS3Multipart, DragDrop, StatusBar } from "https://releases.transloadit.com/uppy/v3.4.0/uppy.min.mjs"

    document.addEventListener('alpine:init', () => {
        Alpine.data('uppy', () => ({
            uppy: null,
            path: '',
            uploadedFiles: [],

            bytesToSize (bytes) {
                const sizes = ["Bytes", "KB", "MB", "GB", "TB"]
                if (bytes === 0) return "n/a"
                const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10)
                if (i === 0) return `${bytes} ${sizes[i]}`
                return `${(bytes / 1024 ** i).toFixed(1)} ${sizes[i]}`
            },

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
                    .use(DragDrop, {
                        target: '.uppy__input',
                    })
                    .use(StatusBar, {
                        target: '.uppy__progress-bar',
                    })
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
                    console.error(file)
                    this.path = response.body.location
                    this.uploadedFiles = [...this.uploadedFiles, file]

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
