<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoomImageController extends Controller
{
    public function storeRoomImages(Request $request, $roomTypeId)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $this->imageService->uploadImage($file, 'rooms');

                \App\Models\RoomImage::create([
                    'room_type_id' => $roomTypeId,
                    'image_path'   => $path,
                    'is_primary'   => false
                ]);
            }
        }

        return back()->with('success', 'រូបភាពបន្ទប់ត្រូវបានបញ្ចូលជោគជ័យ');
    }

    public function update(Request $request, $id)
    {
        $roomType = RoomType::findOrFail($id);

        $roomType->update($request->only(['name', 'base_price', 'description']));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $this->imageService->uploadImage($file, 'rooms');

                $roomType->images()->create([
                    'image_path' => $path,
                    'is_primary' => false
                ]);
            }
        }

        return redirect()->back()->with('success', 'កែប្រែជោគជ័យ');
    }

    public function destroyImage($id)
    {
        $image = \App\Models\RoomImage::findOrFail($id);

        if ($image->image_path) {
            $this->imageService->deleteImage($image->image_path);
        }

        $image->delete();

        return back()->with('success', 'រូបភាពត្រូវបានលុប');
    }
}
