<?php

use CloudMazing\FilamentS3MultipartUpload\Http\Controllers\MultipartUploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('filament')->name('filament.')->group(function () {
    Route::resource('multipart-upload', MultipartUploadController::class)->only('store');
//    Route::get('multipart-upload', [MultipartUploadController::class, 'store']);
});
