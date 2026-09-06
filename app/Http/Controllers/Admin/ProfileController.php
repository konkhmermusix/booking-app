<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;
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

    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'nullable|string|max:20',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ], [
            'name.required'  => 'សូមបញ្ចូលឈ្មោះរបស់អ្នក',
            'email.required' => 'សូមបញ្ចូលអ៊ីមែល',
            'email.unique'   => 'អ៊ីមែលនេះមានគេប្រើប្រាស់រួចហើយ',
            'avatar.image'   => 'រូបថតផ្ទាល់ខ្លួនត្រូវតែជារូបភាព (JPEG, PNG, JPG, WEBP)',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;

        if ($request->boolean('remove_avatar') && $user->avatar) {
            $this->imageService->deleteImage($user->avatar);
            $user->avatar = null;
        } elseif ($request->hasFile('avatar')) {
            if ($user->avatar) {
                $this->imageService->deleteImage($user->avatar);
            }
            $user->avatar = $this->imageService->uploadImage($request->file('avatar'), 'avatars');
        }

        $user->save();

        return back()->with('success', 'ព័ត៌មានប្រវត្តិរូបត្រូវបានប្តូរដោយជោគជ័យ!');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'សូមបញ្ចូលលេខសម្ងាត់បច្ចុប្បន្ន',
            'password.required'         => 'សូមបញ្ចូលលេខសម្ងាត់ថ្មី',
            'password.confirmed'        => 'ការបញ្ជាក់លេខសម្ងាត់ថ្មីមិនត្រូវគ្នានោះទេ',
            'password.min'              => 'លេខសម្ងាត់ថ្មីត្រូវមានយ៉ាងតិច ៦ តួអក្សរ',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'លេខសម្ងាត់បច្ចុប្បន្នមិនត្រឹមត្រូវឡើយ']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'លេខសម្ងាត់ត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យ!');
    }

    public function updatePreferences(Request $request)
    {
        /** @var User $user */
        $user = User::findOrFail(Auth::id());

        // Save preferences or settings
        return back()->with('success', 'ការកំណត់ផ្សេងៗត្រូវបានរក្សាទុកដោយជោគជ័យ!');
    }
}
