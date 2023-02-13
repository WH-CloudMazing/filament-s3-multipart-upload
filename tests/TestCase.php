<?php

declare(strict_types=1);

namespace Tests;

use CloudMazing\FilamentS3MultipartUpload\FilamentS3MultipartUploadServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            FilamentS3MultipartUploadServiceProvider::class,
        ];
    }
}
