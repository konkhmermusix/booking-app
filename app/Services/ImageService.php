<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * @param $file - រូបភាពដែលបាន upload
     * @param $folder - ឈ្មោះ folder (avatars, rooms, room_types)
     * @return string - ឈ្មោះ file ដែលបានរក្សាទុក
     */
    public function uploadImage($file, $folder)
    {
        // បង្កើតឈ្មោះ file ប្លែកៗ ដើម្បីការពារការជាន់គ្នា
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // រក្សាទុកក្នុង storage/app/public/$folder
        $path = $file->storeAs($folder, $fileName, 'public');

        return $path;
    }

    public function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
