<?php

declare(strict_types=1);

use CloudMazing\FilamentS3MultipartUpload\Http\Controllers\MultipartUploadCompletionController;
use CloudMazing\FilamentS3MultipartUpload\Http\Controllers\MultipartUploadController;
use CloudMazing\FilamentS3MultipartUpload\Http\Controllers\TemporarySignedUrlController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('filament-s3-multipart-upload.prefix').'/s3')->name('filament.')->group(function () {
    Route::post('multipart', [MultipartUploadController::class, 'store'])->name('multipart-upload.store');

    Route::get('multipart/{uploadId}/{id}', [TemporarySignedUrlController::class, 'show'])->name('multipart-upload.parts.show');

    Route::post('multipart/{uploadId}/complete', [MultipartUploadCompletionController::class, 'store'])->name('multipart-upload.completion.store');
});
