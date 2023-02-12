<?php

namespace CloudMazing\FilamentS3MultipartUpload;

use Aws\S3\S3Client;
use Spatie\LaravelPackageTools\Package;
use Filament\PluginServiceProvider;
use Illuminate\Filesystem\FilesystemManager;
use CloudMazing\FilamentS3MultipartUpload\Http\Controllers\MultipartUploadController;

class FilamentS3MultipartUploadServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-s3-multipart-upload')
            ->hasConfigFile()
            ->hasRoutes('web');
    }

    public function boot(): void
    {
        parent::boot();

        $this->app
            ->when(MultipartUploadController::class)
            ->needs(S3Client::class)
            ->give(function ($app) {
                return $app->make(FilesystemManager::class)->disk('s3')->getClient();
            });
    }
}
