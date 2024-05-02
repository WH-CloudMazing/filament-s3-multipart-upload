@assets
<link href="https://releases.transloadit.com/uppy/v2.3.0/uppy.min.css" rel="stylesheet">
@endassets

@php($id = 'uppy-' . Str::random(8))

@script
<script>

    document.addEventListener('DOMContentLoaded', () => {
        Livewire.hook('morph.updated', ({component}) => {
            if (component.id === document.getElementById('uppy-{{ $id }}').getAttribute('wire:id')) {
                console.log('uppy updated');
            }
        })
    });

</script>
@endscript

<div class="uppy-component" @if($getInvisible()) style="display:none" @endif>
    <x-dynamic-component
        :component="$getFieldWrapperView()"
        :field="$field"
    >
        <div
                class="uppy"
                x-ignore
                ax-load
                ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('uppy', 'cloudmazing/filament-s3-multipart-upload') }}"
                x-data="uppy({
                    state: $wire.entangle('{{ $getStatePath() }}'),
                    maxFiles: {{ $getMaxNumberOfFiles() }},
                    maxSize: {{ $getMaxFileSize() }},
                    directory: '{{ $getDirectory() }}',
                    companionUrl: '{{ $companionUrl() }}',
                    csrfToken: '{{ csrf_token() }}',
                })"
                wire:key="{{ $id }}"
        >

            <div class="uppy__input">
            </div>

            <div class="uppy__progress-bar">
            </div>

            <div class="uppy__files mt-2">
                <template x-for="file in uploadedFiles" :key="file.name">
                    <div class="uppy__file file py-2 px-4 text-sm dark:text-white">
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
</div>
