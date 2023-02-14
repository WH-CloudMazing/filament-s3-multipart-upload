<?php

declare(strict_types=1);

namespace CloudMazing\FilamentS3MultipartUpload\Http\Controllers;

use Aws\S3\S3Client;
use Illuminate\Http\Request;

class MultipartUploadCompletionController
{
    public function __construct(private S3client $s3)
    {
    }

    public function store(Request $request, string $uploadId)
    {
        $result = $this->s3->completeMultipartUpload([
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => $request->query('key'),
            'UploadId' => $uploadId,
            'MultipartUpload' => ['Parts' => $request->input('parts')],
        ]);

        return response()->json([
            'path' => $result->get('Key'),
            'url'  => $result->get('Location'),
        ]);
    }
}
