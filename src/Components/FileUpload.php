<?php

declare(strict_types=1);

namespace CloudMazing\FilamentS3MultipartUpload\Components;

use Filament\Forms\Components\Field;

class FileUpload extends Field
{
    protected string $view = 'filament-s3-multipart-upload::components.file-upload';

    public string $property = 'hello';

    public function companionUrl()
    {
        return '/'.config('filament-s3-multipart-upload.prefix');
    }
}
