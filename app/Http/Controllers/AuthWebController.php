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
        // ប្រសិនបើមានបញ្ជូន Parameter ?redirect= មកពីទំព័រកន្ត្រកបន្ទប់
        if ($request->has('redirect')) {
            return redirect()->to($request->input('redirect'))
                ->with('success', 'ស្វាគមន៍មកវិញ! អ្នកអាចបន្តការកក់បន្ទប់បានហើយ។');
        }

        // បើគ្មានទេ ឱ្យវាត្រឡប់ទៅទំព័រដើមធម្មតា (Intended URL ឬ /home)
        return redirect()->intended($this->redirectPath());
    }


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
            'password' => 'required|string|min:6|confirmed',
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
                ->with('success', 'ចុះឈ្មោះ និងចូលប្រើជោគជ័យ! អ្នកអាចបន្តការកក់បន្ទប់បានហើយ។');
        }

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

            // ១. បើជា Admin ឱ្យរត់ទៅកាន់ Admin Dashboard ភ្លាម
            if ($user->role === 'admin' || $user->role === 'staff') {
                return redirect()->intended('/admin/dashboard')->with('success', 'ស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រង!');
            }

            // ២. បើមានបញ្ជូន Parameter ?redirect= មកពីទំព័រកន្ត្រកបន្ទប់ (សម្រាប់ Customer)
            if ($request->has('redirect')) {
                return redirect()->to($request->input('redirect'))
                    ->with('success', 'ស្វាគមន៍មកវិញ! អ្នកអាចបន្តការកក់បន្ទប់បានហើយ។');
            }

            // ៣. បើជា Customer ធម្មតាដែលមិនបានមកពីទំព័រ Cart ឱ្យទៅទំព័រដើម
            return redirect()->intended('/')->with('success', 'ចូលបានជោគជ័យ!');
        }

        return back()->withErrors([
            'email' => 'អ៊ីមែល ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ!',
        ])->onlyInput('email');
    }

    public function redirectToGoogle(Request $request)
    {
        // ប្រសិនបើមានជាប់លីង redirect មកពីទំព័រកក់បន្ទប់ ត្រូវចាំវាទុកក្នុង Session
        if ($request->has('redirect')) {
            session(['google_redirect_url' => $request->input('redirect')]);
        }

        return Socialite::driver('google')->redirect();
    }

    // ២. ទទួលព័ត៌មានពី Google ត្រឡប់មកវិញ
    public function handleGoogleCallback(Request $request)
    {
        try {
            // $googleUser = Socialite::driver('google')->user();
            $googleUser = Socialite::driver('google')->stateless()->user();

            // ស្វែងរក User តាមរយៈ google_id
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // បើអត់ឃើញ google_id ទេ ឆែកមើលអ៊ីមែលក្រែងលោគាត់ធ្លាប់ចុះឈ្មោះធម្មតា
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // ធ្វើការ Update ភ្ជាប់ Google ID ចូលគណនីចាស់របស់គាត់
                    $user->update(['google_id' => $googleUser->id]);
                } else {
                    // បើគ្មានគណនីសោះ គឺបង្កើតថ្មីដោយស្វ័យប្រវត្តិ
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'password' => null, // មិនបាច់មាន password ទេ
                        'role' => 'customer', // ឲ្យ Role ជា Customer
                        'status' => 'active',
                    ]);
                }
            }

            // ធ្វើការ Login ចូលប្រព័ន្ធ
            Auth::login($user);
            $request->session()->regenerate();

            // --- ចាប់ផ្តើមចែង Logic បង្វែរទិសដៅ (ដូចកូដចាស់របស់បង) ---

            // ១. បើជា Admin ឬ Staff (ទោះ Login តាម Google ក៏ដេញទៅ Admin Dashboard ដែរ)
            if ($user->role === 'admin' || $user->role === 'staff') {
                return redirect()->intended('/admin/dashboard')->with('success', 'ស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រង!');
            }

            // ២. ពិនិត្យមើលថាតើមានលីងរក្សាទុកក្នុង Session ពីមុន (ទំព័រកក់បន្ទប់) ដែរឬទេ?
            if (session()->has('google_redirect_url')) {
                $redirectUrl = session()->get('google_redirect_url');
                session()->forget('google_redirect_url'); // ប្រើរួចលុបវាចេញពី Session

                return redirect()->to($redirectUrl)->with('success', 'ស្វាគមន៍មកវិញ! អ្នកអាចបន្តការកក់បន្ទប់បានហើយ។');
            }

            // ៣. បើគ្មានទេ ឲ្យទៅទំព័រដើមធម្មតា
            return redirect()->intended('/')->with('success', 'ចូលប្រើប្រព័ន្ធជាមួយ Google ជោគជ័យ!');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'មានបញ្ហាក្នុងការ Login ជាមួយ Google! សូមព្យាយាមម្តងទៀត។');
        }
    }

    // ១. រុញអ្នកប្រើប្រាស់ទៅកាន់ទំព័រ Facebook
    public function redirectToFacebook(Request $request)
    {
        // ប្រសិនបើមានជាប់លីង redirect មកពីទំព័រកក់បន្ទប់ ត្រូវចាំវាទុកក្នុង Session ដូច Google ដែរ
        if ($request->has('redirect')) {
            session(['facebook_redirect_url' => $request->input('redirect')]);
        }

        return Socialite::driver('facebook')->redirect();
    }

    // ២. ទទួលព័ត៌មានពី Facebook ត្រឡប់មកវិញ
    public function handleFacebookCallback(Request $request)
    {
        // កែប្រែ៖ ពិនិត្យមើលជាមុនសិន ប្រសិនបើអ្នកប្រើប្រាស់ចុច Cancel (មិនព្រមឱ្យសិទ្ធិ)
        if ($request->has('error') || $request->input('error') === 'access_denied') {
            return redirect('/login')->with('error', 'អ្នកបានបដិសេធការចូលប្រើប្រព័ន្ធជាមួយ Facebook!');
        }

        try {
            // កែប្រែ៖ ទុកតែជួរដែលមាន ->stateless() តែមួយគត់ (លុបជួរដែលស្ទួនចោល)
            $facebookUser = Socialite::driver('facebook')->stateless()->user();


            // ស្វែងរក User តាមរយៈ facebook_id ក្នុង Database
            $user = User::where('facebook_id', $facebookUser->id)->first();

            if (!$user) {
                // បើអត់ឃើញ facebook_id ទេ ឆែកមើលអ៊ីមែលក្រែងលោគាត់ធ្លាប់ចុះឈ្មោះធម្មតា
                $user = User::where('email', $facebookUser->email)->first();

                if ($user) {
                    // ធ្វើការ Update ភ្ជាប់ Facebook ID ចូលគណនីដែលមានស្រាប់
                    $user->update(['facebook_id' => $facebookUser->id]);
                } else {
                    // បើគ្មានគណនីសោះ គឺបង្កើតថ្មីដោយស្វ័យប្រវត្តិ
                    $user = User::create([
                        'name' => $facebookUser->name,
                        'email' => $facebookUser->email ?? $facebookUser->id . '@facebook.com',
                        'facebook_id' => $facebookUser->id,
                        'avatar' => $facebookUser->avatar,
                        'password' => null,
                        'role' => 'customer',
                        'status' => 'active',
                    ]);
                }
            }

            // ធ្វើការ Login ចូលប្រព័ន្ធ
            Auth::login($user);
            $request->session()->regenerate();

            // ១. បើជា Admin ឬ Staff ឱ្យរត់ទៅកាន់ Admin Dashboard
            if ($user->role === 'admin' || $user->role === 'staff') {
                return redirect()->intended('/admin/dashboard')->with('success', 'ស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រង!');
            }

            // ២. ពិនិត្យមើលថាតើមានលីងរក្សាទុកក្នុង Session ពីមុន (ទំព័រកក់បន្ទប់) ដែរឬទេ?
            if (session()->has('facebook_redirect_url')) {
                $redirectUrl = session()->get('facebook_redirect_url');
                session()->forget('facebook_redirect_url');

                return redirect()->to($redirectUrl)->with('success', 'ស្វាគមន៍មកវិញ! អ្នកអាចបន្តការកក់បន្ទប់បានហើយ។');
            }

            // ៣. បើគ្មានទេ ឲ្យទៅទំព័រដើមធម្មតា
            return redirect()->intended('/')->with('success', 'ចូលប្រើប្រព័ន្ធជាមួយ Facebook ជោគជ័យ!');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'មានបញ្ហាក្នុងការ Login ជាមួយ Facebook! សូមព្យាយាមម្តងទៀត។');
        }
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // ២. ពិនិត្យអ៊ីមែល រួចផ្ញើ Link ទៅកាន់អ៊ីមែលភ្ញៀវ
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'អ៊ីមែលនេះមិនទាន់បានចុះឈ្មោះក្នុងប្រព័ន្ធឡើយ!'
        ]);

        $token = Str::random(64);

        // រក្សាទុក ឬធ្វើបច្ចុប្បន្នភាព Token ក្នុងតារាង password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // ផ្ញើអ៊ីមែលទៅកាន់ User
        Mail::send('auth.emails.password-reset', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('កំណត់លេខសម្ងាត់ឡើងវិញ - ' . config('app.name'));
        });

        return back()->with('success', 'យើងបានផ្ញើតំណភ្ជាប់ទៅកាន់អ៊ីមែលរបស់អ្នករួចរាល់ហើយ! សូមពិនិត្យប្រអប់សំបុត្រ។');
    }

    // ៣. បង្ហាញទំព័រវាយ Password ថ្មី (ពេល User ចុច Link ពីអ៊ីមែល)
    public function showResetPassword($token, Request $request)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // ៤. រក្សាទុក Password ថ្មីចូលក្នុង Database
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        // ផ្ទៀងផ្ទាត់ Token ពីតារាង
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'តំណភ្ជាប់ ឬ Token មិនត្រឹមត្រូវឡើយ!']);
        }

        // ពិនិត្យមើលថាតើ Token ហួសកំណត់ ១ ម៉ោង (៦០ នាទី) ឬនៅ
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'តំណភ្ជាប់នេះបានហួសសុពលភាពហើយ!']);
        }

        // កែប្រែលេខសម្ងាត់ថ្មី
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // លុប Token ចោលក្រោយពេលប្រើរួច ដើម្បីសុវត្ថិភាព
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'លេខសម្ងាត់របស់អ្នកត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យ! សូមឡុកអ៊ីន។');
    }
}
