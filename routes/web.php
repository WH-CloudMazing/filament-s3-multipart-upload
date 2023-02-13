<?php

use CloudMazing\FilamentS3MultipartUpload\Http\Controllers\MultipartUploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('filament/multipart-upload/s3')->name('filament.')->group(function () {
    Route::post('multipart', [MultipartUploadController::class, 'store'])->name('multipart-upload.store');
});
