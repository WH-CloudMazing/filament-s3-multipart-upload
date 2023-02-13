<?php

declare(strict_types=1);

namespace Tests\Feature;

use Aws\Result;
use Aws\S3\S3Client;
use CloudMazing\FilamentS3MultipartUpload\Http\Controllers\MultipartUploadController;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery;

it('creates a multipart upload id', function () {
    $result = Mockery::mock(Result::class);
    $result->shouldReceive('get')->with('UploadId')->andReturn('some-upload-id');
    $result->shouldReceive('get')->with('Bucket')->andReturn('some-bucket-name');
    $result->shouldReceive('get')->with('Key')->andReturn('tmp-abc-123/i-am-an-image.jpg');

    $s3 = Mockery::mock(S3Client::class);
    $s3->shouldReceive('createMultipartUpload')->andReturn($result);

    $this->app
        ->when(MultipartUploadController::class)
        ->needs(S3Client::class)
        ->give(fn () => $s3);

    createMultipartUpload([
        'filename' => 'i-am-an-image.jpg',
        'metadata' => [
            'type' => 'image/jpg',
        ],
    ])->assertJson(fn (AssertableJson $json) => $json
        ->where('uploadId', 'some-upload-id')
        ->where('key', 'tmp-abc-123/i-am-an-image.jpg')
        ->etc()
    );
});

function createMultipartUpload(array $attributes)
{
    return test()->postJson(
        route('filament.multipart-upload.store'),
        $attributes,
    );
}
