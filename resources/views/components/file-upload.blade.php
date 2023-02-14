<link href="https://releases.transloadit.com/uppy/v2.3.0/uppy.min.css" rel="stylesheet">

<script type="module">
    import { Uppy, AwsS3Multipart, DragDrop, StatusBar } from "https://releases.transloadit.com/uppy/v3.4.0/uppy.min.mjs"

    window.Uppy = Uppy
    window.AwsS3Multipart = AwsS3Multipart
    window.DragDrop = DragDrop
    window.StatusBar = StatusBar
</script>

<script>
    const useUppy = ($wire) => ({
        uppy: null,

        state: $wire.entangle('{{ $getStatePath() }}').defer,

        uploadedFiles: [],

        bytesToSize (bytes) {
            const sizes = ["Bytes", "KB", "MB", "GB", "TB"]
            if (bytes === 0) return "n/a"
            const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10)
            if (i === 0) return `${bytes} ${sizes[i]}`
            return `${(bytes / 1024 ** i).toFixed(1)} ${sizes[i]}`
        },

        init () {
            if (this.state) {
                this.uploadedFiles = [{ name: this.state, type: null, size: null }]
            }

            this.uppy = new Uppy({
                id: 'uppy',
                debug: true,
                restrictions: {
                    maxNumberOfFiles: {{ $getMaxNumberOfFiles() }},
                    maxFileSize: {{ $getMaxFileSize() }},
                    minNumberOfFiles: 1
                },
            })

            this.uppy
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

            this.uppy.on("file-added", file => {
                uppy.upload()
            });

            this.uppy.on("upload-success", (file, response) => {
                this.state = response.body.path

                if ({{ $getMaxNumberOfFiles() }} === 1) {
                    this.uploadedFiles = [file]
                } else {
                    this.uploadedFiles = [...this.uploadedFiles, file]
                }
            });
        },
    })
</script>

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div x-data="useUppy($wire)" class="uppy">
        <input
             type="hidden"
            name="{{ $getName() }}"
            {!! $isRequired() ? 'required' : null !!}
            {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
            x-model="state"
        >

        <div class="uppy__input">
        </div>

        <div class="uppy__progress-bar">
        </div>

        <div class="uppy__files mt-2">
            <template x-for="file in uploadedFiles" :key="file.name">
                <div class="uppy__file file py-2 px-4 text-sm bg-white">
                    <div>
                        <span class="file__name font-bold text-sm" x-text="file.name"></span>
                    </div>

                    <div class="file__meta space-x-2 text-xs text-neutral-700" x-show="file.type">
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
</x-dynamic-component>
