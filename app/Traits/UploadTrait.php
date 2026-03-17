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
            // លុបឯកសារចាស់ចេញពី Storage ដើម្បីកុំឱ្យចង្អៀត Disk
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            // បង្កើតឈ្មោះថ្មីការពារការជាន់គ្នា (Optional: ប្រើឈ្មោះដើមក៏បាន)
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();

            // រក្សាទុកក្នុង Folder ដែលបានកំណត់ (public disk)
            return $file->storeAs($folder, $fileName, 'public');
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