<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;

it('creates a signed url', function () {
    $response = Storage::disk('s3')->getClient()->createMultipartUpload([
        'Bucket' => config('filesystems.disks.s3.bucket'),
        'Key' => urlencode(
            config('filament-s3-multipart-upload.s3.temporary_directory').'/'.'some-file-name.jpg',
        ),
        'ContentType' => 'image/jpg',
        'ContentDisposition' => 'inline',
    ]);

    createTemporarySignedUrl($response->get('UploadId'), 0, $response->get('Key'))
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('url')
            ->etc()
        );
});

function createTemporarySignedUrl(string $uploadId, int $part, string $key)
{
    return test()->getJson(
        route('filament.multipart-upload.temporary-signed-url.store', [
            $uploadId,
            $part,
            'key' => $key,
        ]),
    );
}
