@extends('layouts.auth')
@section('content')
<div class="max-w-4xl w-full bg-white dark:bg-gray-800 rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border dark:border-gray-700">
    {{-- Banner --}}
    <div class="hidden md:flex md:w-1/2 bg-[#002B5B] dark:bg-black p-12 text-white flex-col justify-center relative overflow-hidden">
        <h2 class="text-4xl font-bold mb-4 italic">PNT <span class="text-yellow-400 font-serif">HOTEL</span></h2>
        <p class="text-blue-100 opacity-80">{{ __('auth.welcome') }}</p>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-yellow-400/10 rounded-full blur-3xl"></div>
    </div>

    {{-- Form --}}
    <div class="w-full md:w-1/2 p-8 lg:p-12 relative">
        {{-- Lang Switcher --}}
        <div class="absolute top-4 right-6 flex gap-3">

            <button @click="switchLang('kh')"
                class="px-2 py-1 text-[10px] font-black rounded transition"
                :class=" '{{ app()->getLocale() }}' == 'kh' ? 'bg-white dark:bg-gray-700 text-blue-600 shadow-sm' : 'text-gray-400' ">
                KH
            </button>
            <button @click="switchLang('en')"
                class="px-2 py-1 text-[10px] font-black rounded transition"
                :class=" '{{ app()->getLocale() }}' == 'en' ? 'bg-white dark:bg-gray-700 text-blue-600 shadow-sm' : 'text-gray-400' ">
                EN
            </button>
        </div>

        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-8">{{ __('auth.login') }}</h3>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('auth.email') }}</label>
                <div class="relative group">
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="{{ __('auth.login_text_box_email') }}" class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border dark:border-gray-600 rounded-xl outline-none focus:ring-2 ring-blue-500/50 dark:text-white transition-all">
                </div>
            </div>

            <div x-data="{ show: false }">
                <div class="flex justify-between mb-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase">{{ __('auth.password') }}</label>
                    <a href="#" class="text-[10px] text-blue-500 hover:underline">{{ __('auth.forgot_password') }}</a>
                </div>
                <div class="relative group">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="{{ __('auth.login_text_box_password') }}" class="w-full pl-11 pr-12 py-3 bg-gray-50 dark:bg-gray-700 border dark:border-gray-600 rounded-xl outline-none focus:ring-2 ring-blue-500/50 dark:text-white transition-all">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#002B5B] dark:bg-blue-600 text-white py-3.5 rounded-xl font-bold shadow-lg hover:bg-blue-800 transition-all transform active:scale-95">
                {{ __('auth.login') }}
            </button>

            <p class=" text-center text-sm text-gray-500 mt-6">
                {{ __('auth.no_account') }} <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">{{ __('auth.register') }}</a>
            </p>
        </form>
    </div>
</div>
@endsection