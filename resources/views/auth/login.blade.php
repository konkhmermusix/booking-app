@extends('layouts.auth')
@section('title', 'ចូលប្រើ')
@section('content')
<div class="max-w-4xl w-full bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row border dark:border-gray-700">
    {{-- Banner --}}
    <div class="hidden md:flex md:w-1/2 bg-[#002B5B] dark:bg-black p-12 text-white flex-col justify-center relative overflow-hidden">
        <h2 class="text-4xl font-bold mb-4 italic">សណ្ឋាគារ <span class="text-yellow-400 font-serif">ភីអេនធី</span></h2>
        <p class="text-blue-100 opacity-80">សូមស្វាគមន៍</p>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-yellow-400/10 rounded-full blur-3xl"></div>
    </div>

    {{-- Form --}}
    <div class="w-full md:w-1/2 p-8 lg:p-12 relative">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-8">ចូលប្រើ</h3>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                @if(request()->has('redirect'))
                <input type="hidden" name="redirect" value="{{ request()->get('redirect') }}">
                @endif

                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">អ៊ីមែល</label>
                <div class="relative group">
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="អ៊ីម៉ែល"
                        class="w-full pl-11 pr-4 py-3 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 ring-blue-500/50 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <p class="mt-1 invisible peer-placeholder-shown:visible text-[10px] text-red-500"></p>
            </div>

            <div x-data="{ show: false }">
                <div class="flex justify-between mb-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase">ពាក្យសម្ងាត់</label>
                    <a href="{{ route('password.request') }}" class="text-[10px] text-blue-500 hover:underline">ភ្លេចពាក្យសម្ងាត់?</a>
                </div>
                <div class="relative group">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="ពាក្យសម្ងាត់"
                        class="w-full pl-11 pr-4 py-3 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 ring-blue-500/50 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#002B5B] dark:bg-blue-600 text-white py-3.5 rounded-xl font-bold shadow-lg hover:bg-blue-800 transition-all transform active:scale-95">
                ចូលប្រើ
            </button>

            {{-- របារខណ្ឌកណ្តាល "ឬ" --}}
            <div class="relative flex py-2 items-center">
                <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                <span class="flex-shrink mx-4 text-gray-400 text-xs">ឬបន្តជាមួយ</span>
                <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                {{-- ប៊ូតុង Google --}}
                <a href="{{ route('auth.google', ['redirect' => request()->input('redirect')]) }}"
                    class="flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-700 py-3 rounded-xl font-bold shadow-sm hover:bg-gray-50 transition-all transform active:scale-95 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 text-sm">
                    <i class="fab fa-google text-red-500 text-base"></i>
                    <span>Google</span>
                </a>

                {{-- ប៊ូតុង Facebook --}}
                <a href="{{ route('auth.facebook', ['redirect' => request()->input('redirect')]) }}"
                    class="flex items-center justify-center gap-2 bg-[#1877F2] text-white py-3 rounded-xl font-bold shadow-md hover:bg-[#166FE5] transition-all transform active:scale-95 text-sm">
                    <i class="fab fa-facebook text-white text-lg"></i>
                    <span>Facebook</span>
                </a>
            </div>

            <p class="text-center text-sm text-gray-500 mt-6">
                មិនទាន់មានគណនី? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">ចុះឈ្មោះ</a>
            </p>
        </form>
    </div>
</div>
@endsection