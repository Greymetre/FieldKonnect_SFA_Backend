<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ValidateUploadedFiles
{
    // Documents, media and archives only. Executable / script / markup types
    // (php, exe, js, html, svg...) stay blocked because uploads are web-served.
    protected array $allowedExtensions = [
        // images
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'heic', 'heif', 'tif', 'tiff',
        // documents
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'xlsm', 'csv', 'txt', 'rtf',
        'ppt', 'pptx', 'odt', 'ods', 'odp', 'eml', 'msg',
        // drawings
        'dwg', 'dxf',
        // archives
        'zip', 'rar', '7z',
        // audio / video
        'mp3', 'wav', 'm4a', 'aac', 'ogg', 'amr', 'mp4', 'mov', 'avi', 'mkv', 'webm', '3gp',
    ];

    protected int $maxBytes = 25 * 1024 * 1024;

    public function handle(Request $request, Closure $next)
    {
        foreach ($request->allFiles() as $file) {
            $this->validateFile($file);
        }

        return $next($request);
    }

    private function validateFile($file): void
    {
        if (is_array($file)) {
            foreach ($file as $item) {
                $this->validateFile($item);
            }

            return;
        }

        if (! $file instanceof UploadedFile || ! $file->isValid()) {
            abort(422, 'Invalid uploaded file.');
        }

        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, $this->allowedExtensions, true) || $file->getSize() > $this->maxBytes) {
            abort(422, 'Uploaded file type or size is not allowed.');
        }
    }
}
