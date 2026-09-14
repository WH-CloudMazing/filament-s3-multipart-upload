# Filament S3 Multipart Upload

> [!WARNING]
> **This package is unmaintained and archived. Please do not use it in new projects.**
>
> The last release is `0.1` from February 2023.
>
> What is actually broken:
>
> - **The frontend bundle can no longer be built.** `@uppy/aws-s3-multipart@4.1.2`
>   is deprecated and its published tarball ships no `lib/` directory, while its
>   `main` points at `lib/index.js`. `node bin/build.js` therefore fails with
>   `Could not resolve "@uppy/aws-s3-multipart"`. The committed `dist` was built
>   from an earlier, working version.
> - The Uppy dependencies are several majors behind (`@uppy/core` 5 while 6 is current).
> - There are no tests or CI running on this repository.
>
> If you depend on this, forking is the way forward. The migration is: replace
> `@uppy/aws-s3-multipart` with `@uppy/aws-s3` using `shouldUseMultipart`, and move
> `@uppy/core`, `@uppy/drag-drop` and `@uppy/status-bar` to 6 in the same change,
> since those two plugins peer on `@uppy/core`.

A filament component that uses Uppy for multi-part uploads.

## Installation
```sh
composer require cloudmazing/filament-s3-multipart-upload
```

```php
use CloudMazing\FilamentS3MultipartUpload\Components\FileUpload;

FileUpload::make('column_name')
    ->maxFileSize(10 * 1024 * 1024 * 1024)
    ->multiple()
    ->maxNumberOfFiles(5);
```
