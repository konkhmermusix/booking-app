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


    public function uploadBase64($base64String, $folder = 'uploads', $oldFile = null)
    {
        if ($base64String) {
            // លុបឯកសារចាស់ចេញពី Storage (បើមាន)
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            // កាត់ក្បាល Data URL (ឧទាហរណ៍៖ data:image/png;base64,) ដើម្បីយកសាច់កូដរូបភាពសុទ្ធ
            $replace = substr($base64String, 0, strpos($base64String, ',') + 1);
            $image = str_replace($replace, '', $base64String);
            $image = str_replace(' ', '+', $image);

            // ចាប់យក Extension (png, jpg, jpeg, webp) ដោយស្វ័យប្រវត្តិចេញពីក្បាល Base64
            $extension = 'png'; // តម្លៃលំនាំដើម
            if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
                $extension = strtolower($type[1]);
            }

            // បង្កើតឈ្មោះឯកសារថ្មីការពារការជាន់គ្នា
            $fileName = Str::random(20) . '.' . $extension;
            $path = $folder . '/' . $fileName;

            // រក្សាទុកចូលក្នុង Storage Public Disk
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
