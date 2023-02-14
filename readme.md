# Filament S3 Multipart Upload
A filament component that uses Uppy for multi-part uploads.

## Installation
```sh
composer require cloudmazing/filament-s3-multipart-upload
```

```php
use CloudMazing\FilamentS3MultipartUpload\Components\FileUpload;

FileUpload::make('column_name')
    ->maxFileSize(10 * 1024 * 1024 * 1024)
    ->multiple()
    ->maxNumberOfFiles(5);
```
