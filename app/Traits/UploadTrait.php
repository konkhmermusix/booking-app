<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadTrait
{

    /**
     * Upload ឯកសារ និងលុបឯកសារចាស់ (បើមាន)
     */
    public function uploadFile($file, $folder = 'uploads', $oldFile = null)
    {
        if ($file) {
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();

            return $file->storeAs($folder, $fileName, 'public');
        }
        return $oldFile;
    }


    public function uploadBase64($base64String, $folder = 'uploads', $oldFile = null)
    {
        if ($base64String) {
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            $replace = substr($base64String, 0, strpos($base64String, ',') + 1);
            $image = str_replace($replace, '', $base64String);
            $image = str_replace(' ', '+', $image);

            $extension = 'png';
            if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
                $extension = strtolower($type[1]);
            }

            $fileName = Str::random(20) . '.' . $extension;
            $path = $folder . '/' . $fileName;

            Storage::disk('public')->put($path, base64_decode($image));

            return $path;
        }
        return $oldFile;
    }

    /**
     * លុបឯកសារ
     */
    public function deleteFile($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
