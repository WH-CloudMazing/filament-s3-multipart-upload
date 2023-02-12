<?php

namespace CloudMazing\FilamentS3MultipartUpload;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentS3MultipartUploadServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-s3-multipart-upload')
            ->hasConfigFile()
        ;
    }
}
