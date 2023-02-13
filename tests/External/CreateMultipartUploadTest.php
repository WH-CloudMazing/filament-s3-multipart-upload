<?php

declare(strict_types=1);

namespace Tests\External;

use Illuminate\Testing\Fluent\AssertableJson;

it('creates a multipart upload id', function () {
    createMultipartUpload([])->assertJson(fn (AssertableJson $json) => $json
        ->has('uploadId')
        ->has('key')
    );
});

function createMultipartUpload(array $attributes)
{
    return test()->postJson(
        route('filament.multipart-upload.store'),
        $attributes,
    );
}
