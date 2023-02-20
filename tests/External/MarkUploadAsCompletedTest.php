<?php

declare(strict_types=1);

namespace Tests\External;

use Illuminate\Support\Facades\Storage;

it('marks the multipart upload as completed', function () {
    $response = Storage::disk('s3')->getClient()->createMultipartUpload([
        'Bucket' => config('filesystems.disks.s3.bucket'),
        'Key' => 'some-file-name.jpg',
        'ContentType' => 'image/jpg',
        'ContentDisposition' => 'inline',
    ]);

    // upload two parts of a file
    // get the parts

    markUploadAsCompleted($response->get('UploadId'), $response->get('Key'), [])->dd();
})->skip();

function markUploadAsCompleted(string $uploadId, string $key, array $attributes)
{
    return test()->postJson(
        route('filament.multipart-upload.completion.store', [$uploadId, 'key' => $key]),
        $attributes,
    );
}
