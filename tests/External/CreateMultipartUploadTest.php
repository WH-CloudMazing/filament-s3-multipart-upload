<?php

namespace Tests\External;

use Illuminate\Testing\Fluent\AssertableJson;

it("creates a multipart upload id", function () {
    createMultipartUpload([])->dd();
});

function createMultipartUpload(array $attributes)
{
    return test()->postJson(
        route('filament.multipart-upload.store'),
        $attributes,
    );
}
