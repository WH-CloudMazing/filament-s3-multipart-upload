<?php

declare(strict_types=1);

namespace CloudMazing\FilamentS3MultipartUpload\Http\Controllers;

use Aws\S3\S3Client;
use Illuminate\Http\Request;

class TemporarySignedUrlController
{
    public function __construct(private S3Client $s3)
    {
    }

    public function show(Request $request, string $uploadId, int $index)
    {
        $command = $this->s3->getCommand('uploadPart', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => $request->query('key'),
            'UploadId' => $uploadId,
            'PartNumber' => $index,
            'Body' => '',
        ]);

        $url = (string) $this->s3->createPresignedRequest(
            $command,
            '+1 hour',
        )->getUri();

        return [
            'url' => $url,
        ];
    }
}
