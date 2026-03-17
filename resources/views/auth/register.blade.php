@extends('layouts.auth')
@section('content')
<div class="max-w-5xl w-full bg-white dark:bg-gray-800 rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border dark:border-gray-700">
    {{-- Left Banner --}}
    <div class="hidden lg:flex lg:w-1/3 bg-[#002B5B] dark:bg-black p-10 text-white flex-col justify-center">
        <h2 class="text-3xl font-bold mb-4 italic">PNT <span class="text-yellow-400 font-serif">HOTEL</span></h2>
        <p class="text-sm opacity-70">{{ __('auth.create_account') }}</p>
    </div>

    {{-- Form --}}
    <div class="w-full lg:w-2/3 p-8 md:p-12 relative">
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

        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">{{ __('auth.register') }}</h3>

        <form action="{{ route('register') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf
            {{-- Name --}}
            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('auth.name') }}</label>
                <div class="relative group">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input type="text" name="name" required placeholder="{{ __('auth.register_text_box_fullname') }}" class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border dark:border-gray-600 rounded-xl outline-none focus:ring-2 ring-blue-500/50 dark:text-white transition-all text-sm">
                </div>
            </div>

            {{-- Phone --}}
            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('auth.phone') }}</label>
                <div class="relative group">
                    <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input type="tel" name="phone" required placeholder="{{ __('auth.register_text_box_phone') }}" class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border dark:border-gray-600 rounded-xl outline-none focus:ring-2 ring-blue-500/50 dark:text-white transition-all text-sm">
                </div>
            </div>

            {{-- Email --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('auth.email') }}</label>
                <div class="relative group">
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input type="email" name="email" required placeholder="{{ __('auth.register_text_box_email') }}" class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border dark:border-gray-600 rounded-xl outline-none focus:ring-2 ring-blue-500/50 dark:text-white transition-all text-sm">
                </div>
            </div>

            {{-- Password --}}
            <div class=" md:col-span-1" x-data="{ show: false }">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('auth.password') }}</label>
                <div class="relative group">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="{{ __('auth.register_text_box_password') }}" class="w-full pl-11 pr-11 py-3 bg-gray-50 dark:bg-gray-700 border dark:border-gray-600 rounded-xl outline-none focus:ring-2 ring-blue-500/50 dark:text-white transition-all text-sm">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i></button>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class=" md:col-span-1" x-data="{ show: false }">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('auth.confirm_password') }}</label>
                <div class="relative group">
                    <i class="fas fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder="{{ __('auth.register_text_box_confirm_password') }}" class="w-full pl-11 pr-11 py-3 bg-gray-50 dark:bg-gray-700 border dark:border-gray-600 rounded-xl outline-none focus:ring-2 ring-blue-500/50 dark:text-white transition-all text-sm">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i></button>
                </div>
            </div>

            <div class="md:col-span-2 pt-4">
                <button type="submit" class="w-full bg-[#002B5B] dark:bg-blue-600 text-white py-3.5 rounded-xl font-bold shadow-lg hover:bg-blue-800 transition-all transform active:scale-95">
                    {{ __('auth.register') }}
                </button>
                <p class=" text-center text-sm text-gray-500 mt-6">
                    {{ __('auth.have_account') }} <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">{{ __('auth.login') }}</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection