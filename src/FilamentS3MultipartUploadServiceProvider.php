<?php

declare(strict_types=1);

namespace CloudMazing\FilamentS3MultipartUpload;

use Aws\S3\S3Client;
use CloudMazing\FilamentS3MultipartUpload\Http\Controllers\MultipartUploadCompletionController;
use CloudMazing\FilamentS3MultipartUpload\Http\Controllers\MultipartUploadController;
use CloudMazing\FilamentS3MultipartUpload\Http\Controllers\TemporarySignedUrlController;
use Filament\PluginServiceProvider;
use Illuminate\Filesystem\FilesystemManager;
use Spatie\LaravelPackageTools\Package;

class FilamentS3MultipartUploadServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-s3-multipart-upload')
            ->hasConfigFile()
            ->hasViews()
            ->hasAssets()
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

        $this->app
            ->when(TemporarySignedUrlController::class)
            ->needs(S3Client::class)
            ->give(function ($app) {
                return $app->make(FilesystemManager::class)->disk('s3')->getClient();
            });

        $this->app
            ->when(MultipartUploadCompletionController::class)
            ->needs(S3Client::class)
            ->give(function ($app) {
                return $app->make(FilesystemManager::class)->disk('s3')->getClient();
            });
    }
}
