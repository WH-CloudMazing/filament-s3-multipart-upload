<?php

namespace CloudMazing\FilamentS3MultipartUpload\Http\Controllers;

use Illuminate\Http\Request;
use Aws\S3\S3Client;

class MultipartUploadController
{
    public function __construct(private S3Client $s3)
    {
    }

    public function store(Request $request)
    {
        $response = $this->s3->createMultipartUpload([
            'Bucket'             => config('filesystems.disks.s3.bucket'),
            'Key'                => 'some-key',
            'ContentType'        => 'some-content-type',
            'ContentDisposition' => 'inline',
        ]);

        return response()->json([
            'uploadId' => $response->get('UploadId'),
            'key' => $response->get('Key'),
        ]);
    }
}
