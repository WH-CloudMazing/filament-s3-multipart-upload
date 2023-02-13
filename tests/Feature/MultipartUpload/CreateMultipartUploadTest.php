<?php

namespace Tests\Feature\MultipartUpload;

use Aws\Result;
use Aws\S3\S3Client;
use CloudMazing\FilamentS3MultipartUpload\Http\Controllers\MultipartUploadController;
use Mockery;
use Illuminate\Testing\Fluent\AssertableJson;

it("creates a multipart upload id", function () {
    $result = Mockery::mock(Result::class);
    $result->shouldReceive('get')->with('UploadId')->andReturn('some-upload-id');
    $result->shouldReceive('get')->with('Bucket')->andReturn('some-bucket-name');
    $result->shouldReceive('get')->with('Key')->andReturn('some-key-name');

    $s3 = Mockery::mock(S3Client::class);
    $s3->shouldReceive('createMultipartUpload')->andReturn($result);

    $this->app
        ->when(MultipartUploadController::class)
        ->needs(S3Client::class)
        ->give(fn () => $s3);

    createMultipartUpload([
    ])->assertJson(fn (AssertableJson $json) => $json
        ->where('uploadId', 'some-upload-id')
        ->where('key', 'some-key-name')
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
