<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // ១. ទាញយកព័ត៌មាន User បច្ចុប្បន្ន
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    // ២. កែប្រែព័ត៌មាន Profile
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'phone'));

        return response()->json(['message' => 'ព័ត៌មានត្រូវបានកែប្រែ', 'user' => $user]);
    }

    // ៣. ប្តូរលេខសម្ងាត់
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return response()->json(['message' => 'លេខសម្ងាត់ចាស់មិនត្រឹមត្រូវ'], 422);
        }

        $request->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['message' => 'លេខសម្ងាត់ប្តូរជោគជ័យ']);
    }
}
