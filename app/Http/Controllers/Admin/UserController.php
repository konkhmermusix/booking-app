<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(5);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,staff,customer',
            'status' => 'required|in:active,inactive,pending',
            'password' => 'required|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:20480'
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['password'] = Hash::make($request->password);
        User::create($data);

        return back()->with('success', 'បង្កើតអ្នកប្រើប្រាស់បានជោគជ័យ!');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,staff,customer',
            'status' => 'required|in:active,inactive,pending',
            'password' => 'nullable|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:20480'
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return back()->with('success', 'ធ្វើបច្ចុប្បន្នភាពបានជោគជ័យ!');
    }

    public function destroy(User $user)
    {
        if ($user->avatar) Storage::disk('public')->delete($user->avatar);
        $user->delete();
        return back()->with('success', 'លុបអ្នកប្រើប្រាស់រួចរាល់!');
    }
}
