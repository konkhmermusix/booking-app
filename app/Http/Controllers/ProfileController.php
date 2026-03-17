<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function edit()
    {
        return view('frontend.profile');
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'nullable|string|max:20', // បន្ថែមលេខទូរស័ព្ទ
            'email'  => 'required|email|unique:users,email,' . $user->id, // បន្ថែមអ៊ីមែល និងការពារការជាន់គ្នា
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'email.unique' => 'អ៊ីមែលនេះមានគេប្រើរួចហើយ!',
            'name.required' => 'សូមបញ្ចូលឈ្មោះរបស់អ្នក',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                $this->imageService->deleteImage($user->avatar);
            }

            $user->avatar = $this->imageService->uploadImage($request->file('avatar'), 'avatars');
        }

        $user->save();

        return back()->with('success', 'ព័ត៌មានត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'លេខសម្ងាត់ចាស់មិនត្រឹមត្រូវទេ!',
            'password.confirmed' => 'ការបញ្ជាក់លេខសម្ងាត់ថ្មីមិនត្រូវគ្នានោះទេ!',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'លេខសម្ងាត់ត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យ!');
    }
}
