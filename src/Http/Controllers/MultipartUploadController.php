<?php

declare(strict_types=1);

namespace CloudMazing\FilamentS3MultipartUpload\Http\Controllers;

use Aws\S3\S3Client;
use Illuminate\Http\Request;

class MultipartUploadController
{
    public function __construct(private s3client $s3)
    {
    }

    public function store(Request $request)
    {
        $response = $this->s3->createMultipartUpload([
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => 'some-key',
            'ContentType' => 'some-content-type',
            'ContentDisposition' => 'inline',
        ]);

        return response()->json([
            'uploadId' => $response->get('UploadId'),
            'key' => $response->get('Key'),
        ]);
    }
}
