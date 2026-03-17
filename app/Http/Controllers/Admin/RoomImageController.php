<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoomImageController extends Controller
{
    public function storeRoomImages(Request $request, $roomTypeId)
    {
        // ឆែកមើលថាតើមានការផ្ញើរូបភាពមកដែរឬទេ
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // ១. ប្រើ Service របស់អ្នកដើម្បី Upload ទៅ folder 'rooms'
                $path = $this->imageService->uploadImage($file, 'rooms');

                // ២. រក្សាទុកក្នុងតារាង room_images
                \App\Models\RoomImage::create([
                    'room_type_id' => $roomTypeId,
                    'image_path'   => $path,
                    'is_primary'   => false // ឬ logic កំណត់រូបមេរបស់អ្នក
                ]);
            }
        }

        return back()->with('success', 'រូបភាពបន្ទប់ត្រូវបានបញ្ចូលជោគជ័យ!');
    }

    public function update(Request $request, $id)
    {
        $roomType = RoomType::findOrFail($id);

        // ១. ការ Update ព័ត៌មានអក្សរ (Name, Price...)
        $roomType->update($request->only(['name', 'base_price', 'description']));

        // ២. ការបន្ថែមរូបភាពថ្មី (Create part of CRUD)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $this->imageService->uploadImage($file, 'rooms');

                $roomType->images()->create([
                    'image_path' => $path,
                    'is_primary' => false
                ]);
            }
        }

        return redirect()->back()->with('success', 'កែប្រែជោគជ័យ!');
    }

    public function destroyImage($id)
    {
        $image = \App\Models\RoomImage::findOrFail($id);

        // ១. ប្រើ Service របស់អ្នកដើម្បីលុប File ចេញពី Storage
        if ($image->image_path) {
            $this->imageService->deleteImage($image->image_path);
        }

        // ២. លុបទិន្នន័យចេញពី Database
        $image->delete();

        return back()->with('success', 'រូបភាពត្រូវបានលុប!');
    }
}
