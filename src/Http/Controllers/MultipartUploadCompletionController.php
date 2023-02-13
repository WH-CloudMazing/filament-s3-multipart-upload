<?php

namespace CloudMazing\FilamentS3MultipartUpload\Http\Controllers;

use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MultipartUploadCompletionController
{
    public function __construct(private s3client $s3)
    {
    }

    public function store(Request $request, string $uploadId)
    {
        $result = $this->s3->completeMultipartUpload([
            'Bucket'          => config('filesystems.disks.s3.bucket'),
            'Key'             => $request->query('key'),
            'UploadId'        => $uploadId,
            'MultipartUpload' => ['Parts' => $request->input('parts')],
        ]);

        return response()->json([
            'location' => $result->get('Location'),
        ]);
    }
}
