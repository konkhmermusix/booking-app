<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function edit()
    {
        return view('frontend.setting');
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'nullable|string|max:20',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:20480',
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
        $user = User::find(Auth::id());

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'សូមបញ្ចូលលេខសម្ងាត់បច្ចុប្បន្ន',
            'password.required' => 'សូមបញ្ចូលលេខសម្ងាត់ថ្មី',
            'password.confirmed' => 'ការបញ្ជាក់លេខសម្ងាត់ថ្មីមិនត្រូវគ្នានោះទេ!',
        ]);

        // ផ្ទៀងផ្ទាត់លេខសម្ងាត់ចាស់ជាមួយពាក្យសម្ងាត់ក្នុង Database
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'លេខសម្ងាត់បច្ចុប្បន្នមិនត្រឹមត្រូវទេ!']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'លេខសម្ងាត់ត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យ!');
    }

    public function destroy(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'delete_password' => 'required',
        ], [
            'delete_password.required' => 'សូមបញ្ចូលលេខសម្ងាត់ដើម្បីបញ្ជាក់ការលុបគណនី',
        ]);

        // ត្រូវវាយលេខសម្ងាត់ត្រូវទើបអនុញ្ញាតឱ្យលុប
        if (!Hash::check($request->delete_password, $user->password)) {
            return back()->withErrors(['delete_password' => 'លេខសម្ងាត់មិនត្រឹមត្រូវទេ! មិនអាចលុបគណនីបានឡើយ។']);
        }

        // លុបរូបភាព Avatar ពី Storage មុនលុប User
        if ($user->avatar) {
            $this->imageService->deleteImage($user->avatar);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'គណនីរបស់អ្នកត្រូវបានលុបដោយជោគជ័យ!');
    }
}
