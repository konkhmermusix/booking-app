<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthWebController extends Controller
{

    protected function authenticated(Request $request, $user)
    {
        if ($request->has('redirect')) {
            return redirect()->to($request->input('redirect'))
                ->with('success', 'ស្វាគមន៍មកវិញ អ្នកអាចបន្តការកក់បន្ទប់បានហើយ។');
        }

        return redirect()->intended($this->redirectPath());
    }


    // បង្ហាញទំព័រចុះឈ្មោះ
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'សូមបញ្ចូលឈ្មោះពេញរបស់អ្នក',
            'email.required' => 'សូមបញ្ចូលអាសយដ្ឋានអ៊ីមែល',
            'email.email' => 'ទម្រង់អាសយដ្ឋានអ៊ីមែលមិនត្រឹមត្រូវឡើយ',
            'email.unique' => 'អាសយដ្ឋានអ៊ីមែលនេះមានគណនីក្នុងប្រព័ន្ធរួចហើយ',
            'password.required' => 'សូមបញ្ចូលពាក្យសម្ងាត់',
            'password.min' => 'ពាក្យសម្ងាត់ត្រូវមានយ៉ាងហោចណាស់ ៦ ខ្ទង់',
            'password.confirmed' => 'ពាក្យសម្ងាត់ និងការបញ្ជាក់ពាក្យសម្ងាត់មិនត្រូវគ្នាឡើយ',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
        ]);

        Auth::login($user);

        if ($request->has('redirect')) {
            return redirect()->to($request->input('redirect'))
                ->with('success', 'ចុះឈ្មោះ និងចូលប្រើជោគជ័យ អ្នកអាចបន្តការកក់បន្ទប់បានហើយ។');
        }

        return redirect('/')->with('success', 'ចុះឈ្មោះ និងចូលប្រើជោគជ័យ');
    }

    // បង្ហាញទំព័រចូលប្រើ
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'សូមបញ្ចូលអាសយដ្ឋានអ៊ីមែល',
            'email.email' => 'ទម្រង់អាសយដ្ឋានអ៊ីមែលមិនត្រឹមត្រូវឡើយ',
            'password.required' => 'សូមបញ្ចូលពាក្យសម្ងាត់',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user && $user->status !== 'active') {
            return back()->withErrors([
                'email' => 'គណនីរបស់អ្នកត្រូវបានផ្អាក ឬមិនទាន់ដំណើរការឡើយ',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin' || $user->role === 'staff') {
                return redirect()->intended('/admin/dashboard')->with('success', 'ស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រង');
            }

            if ($request->has('redirect')) {
                return redirect()->to($request->input('redirect'))
                    ->with('success', 'ស្វាគមន៍មកវិញ! អ្នកអាចបន្តការកក់បន្ទប់បានហើយ។');
            }

            return redirect()->intended('/')->with('success', 'ចូលបានជោគជ័យ');
        }

        return back()->withErrors([
            'email' => 'អ៊ីមែល ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ',
        ])->onlyInput('email');
    }

    public function redirectToGoogle(Request $request)
    {
        if ($request->has('redirect')) {
            session(['google_redirect_url' => $request->input('redirect')]);
        }

        return Socialite::driver('google')->redirect();
    }

    // ទទួលព័ត៌មានពី Google ត្រឡប់មកវិញ
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    $user->update(['google_id' => $googleUser->id]);
                } else {
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'password' => null,
                        'role' => 'customer',
                        'status' => 'active',
                    ]);
                }
            }

            if ($user->status !== 'active') {
                return redirect('/login')->with('error', 'គណនីរបស់អ្នកត្រូវបានផ្អាក ឬមិនទាន់ដំណើរការឡើយ');
            }

            Auth::login($user);
            $request->session()->regenerate();

            if ($user->role === 'admin' || $user->role === 'staff') {
                return redirect()->intended('/admin/dashboard')->with('success', 'ស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រង');
            }

            if (session()->has('google_redirect_url')) {
                $redirectUrl = session()->get('google_redirect_url');
                session()->forget('google_redirect_url');

                return redirect()->to($redirectUrl)->with('success', 'ស្វាគមន៍មកវិញ អ្នកអាចបន្តការកក់បន្ទប់បានហើយ។');
            }

            return redirect()->intended('/')->with('success', 'ចូលប្រើជាមួយ Google ជោគជ័យ');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'មានបញ្ហាក្នុងការចូលប្រើជាមួយ Google សូមព្យាយាមម្តងទៀត។');
        }
    }

    // រុញអ្នកប្រើប្រាស់ទៅកាន់ទំព័រ Facebook
    public function redirectToFacebook(Request $request)
    {
        if ($request->has('redirect')) {
            session(['facebook_redirect_url' => $request->input('redirect')]);
        }

        return Socialite::driver('facebook')->redirect();
    }

    // ទទួលព័ត៌មានពី Facebook ត្រឡប់មកវិញ
    public function handleFacebookCallback(Request $request)
    {
        if ($request->has('error') || $request->input('error') === 'access_denied') {
            return redirect('/login')->with('error', 'អ្នកបានបដិសេធការចូលប្រើជាមួយ Facebook');
        }

        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            $user = User::where('facebook_id', $facebookUser->id)->first();

            if (!$user) {
                if (!empty($facebookUser->email)) {
                    $user = User::where('email', $facebookUser->email)->first();
                }

                if ($user) {
                    $user->update(['facebook_id' => $facebookUser->id]);
                } else {
                    $user = User::create([
                        'name' => $facebookUser->name ?? 'Facebook User',
                        'email' => $facebookUser->email ?? ($facebookUser->id . '@facebook.com'),
                        'facebook_id' => $facebookUser->id,
                        'avatar' => $facebookUser->avatar,
                        'password' => null,
                        'role' => 'customer',
                        'status' => 'active',
                    ]);
                }
            }

            if ($user->status !== 'active') {
                return redirect('/login')->with('error', 'គណនីរបស់អ្នកត្រូវបានផ្អាក ឬមិនទាន់ដំណើរការឡើយ');
            }

            Auth::login($user);
            $request->session()->regenerate();

            if ($user->role === 'admin' || $user->role === 'staff') {
                return redirect()->intended('/admin/dashboard')->with('success', 'ស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រង');
            }

            if (session()->has('facebook_redirect_url')) {
                $redirectUrl = session()->get('facebook_redirect_url');
                session()->forget('facebook_redirect_url');

                return redirect()->to($redirectUrl)->with('success', 'ស្វាគមន៍មកវិញ អ្នកអាចបន្តការកក់បន្ទប់បានហើយ។');
            }

            // ៣. បើគ្មានទេ ឲ្យទៅទំព័រដើមធម្មតា
            return redirect()->intended('/')->with('success', 'ចូលប្រើជាមួយ Facebook ជោគជ័យ');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Facebook Login Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'មានបញ្ហាក្នុងការចូលប្រើជាមួយ Facebook សូមព្យាយាមម្តងទៀត។');
        }
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'អ៊ីមែលនេះមិនទាន់បានចុះឈ្មោះក្នុងប្រព័ន្ធឡើយ'
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        Mail::send('auth.emails.password-reset', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('កំណត់លេខសម្ងាត់ឡើងវិញ - ' . config('app.name'));
        });

        return back()->with('success', 'យើងបានផ្ញើតំណភ្ជាប់ទៅកាន់អ៊ីមែលរបស់អ្នករួចរាល់ហើយ សូមពិនិត្យប្រអប់សំបុត្រ។');
    }

    // បង្ហាញទំព័រវាយ Password ថ្មី
    public function showResetPassword($token, Request $request)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // រក្សាទុក Password ថ្មីចូលក្នុង Database
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'សូមបញ្ចូលអាសយដ្ឋានអ៊ីមែល',
            'email.email' => 'ទម្រង់អាសយដ្ឋានអ៊ីមែលមិនត្រឹមត្រូវឡើយ',
            'email.exists' => 'អាសយដ្ឋានអ៊ីមែលនេះមិនទាន់បានចុះឈ្មោះក្នុងប្រព័ន្ធឡើយ',
            'token.required' => 'តំណភ្ជាប់មិនត្រឹមត្រូវឡើយ',
            'password.required' => 'សូមបញ្ចូលពាក្យសម្ងាត់',
            'password.min' => 'ពាក្យសម្ងាត់ត្រូវមានយ៉ាងហោចណាស់ 6 ខ្ទង់',
            'password.confirmed' => 'ពាក្យសម្ងាត់ និងការបញ្ជាក់ពាក្យសម្ងាត់មិនត្រូវគ្នាឡើយ',
        ]);

        // ផ្ទៀងផ្ទាត់ Token ពីតារាង
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'តំណភ្ជាប់ ឬ Token មិនត្រឹមត្រូវឡើយ']);
        }

        // ពិនិត្យមើលថាតើ Token ហួសកំណត់ ១ ម៉ោង (៦០ នាទី) ឬនៅ
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'តំណភ្ជាប់នេះបានហួសសុពលភាពហើយ']);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'លេខសម្ងាត់របស់អ្នកត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យ សូមចូលប្រើ');
    }
}
