<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthWebController extends Controller
{

    // បង្ហាញទំព័រចុះឈ្មោះ
    public function showRegister()
    {
        return view('auth.register');
    }

    // កត្ដវិទ្យាសម្រាប់ដំណើរការទិន្នន័យពេលចុះឈ្មោះចូលគេហទំព័រ
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'ចុះឈ្មោះ និងចូលប្រើជោគជ័យ!');
    }

    // បង្ហាញទំព័រចូលប្រើ
    public function showLogin()
    {
        return view('auth.login');
    }

    // កត្ដវិទ្យាសម្រាប់ដំណើរការទិន្នន័យពេលចូលប្រើគេហទំព័រ
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // ចូលទៅគេហទំព័រសិនទើបចូលទៅ Dashboard
        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();
        //     return redirect()->intended('/')->with('success', 'ចូលបានជោគជ័យ!');
        // }

        // Admin ចូល Dashboard ស្រាប់ 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // បែងចែកការ Redirect តាម Role
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard')->with('success', 'សួស្តី Admin!');
            }

            return redirect()->intended('/')->with('success', 'ចូលបានជោគជ័យ!');
        }

        return back()->withErrors([
            'email' => 'អ៊ីមែល ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ!',
        ])->onlyInput('email');
    }
}
