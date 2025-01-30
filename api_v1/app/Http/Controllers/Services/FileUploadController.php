<?php

namespace App\Http\Controllers\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class FileUploadController
{
    public static function storeImage($image, $path)
    {
        // Generate a unique filename using the original file's extension
        $filename = time() . '-' . $image->getClientOriginalName();

        // Store the image at the specified path with the unique filename
        // You can store it anywhere (e.g., storage or public disk)
        $path = $image->storeAs($path, $filename, 'public');

        // Return the path to the image (URL accessible to the public)
        return Storage::url($path);
    }
}
