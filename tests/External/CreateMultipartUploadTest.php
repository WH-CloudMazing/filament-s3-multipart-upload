<?php

declare(strict_types=1);

namespace Tests\External;

use Illuminate\Testing\Fluent\AssertableJson;

it('creates a multipart upload id', function () {
    createMultipartUpload([
        'filename' => 'i-am-an-image.jpg',
        'metadata' => [
            'type' => 'image/jpg',
        ],
    ])->assertJson(fn (AssertableJson $json) => $json
        ->has('uploadId')
        ->where('key', 'i-am-an-image.jpg')
        ->etc()
    );
});

function createMultipartUpload(array $attributes)
{
    return test()->postJson(
        route('filament.multipart-upload.store'),
        array_replace_recursive([
            'filename' => 'some-file-name.png',
            'metadata' => [
                'type' => 'image/png',
            ],
        ], $attributes),
    );
}
