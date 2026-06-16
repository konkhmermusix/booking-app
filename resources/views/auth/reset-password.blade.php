@extends('layouts.auth')
@section('title', 'បង្កើតពាក្យសម្ងាត់ថ្មី')
@section('content')
<div class="max-w-4xl w-full bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row border dark:border-gray-700">
    {{-- Banner --}}
    <div class="hidden md:flex md:w-1/2 bg-[#002B5B] dark:bg-black p-12 text-white flex-col justify-center relative overflow-hidden">
        <h2 class="text-4xl font-bold mb-4 italic">សណ្ឋាគារ <span class="text-yellow-400 font-serif">ភីអេនធី</span></h2>
        <p class="text-blue-100 opacity-80">សូមបង្កើតពាក្យសម្ងាត់ដែលមានសុវត្ថិភាពខ្ពស់។</p>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-yellow-400/10 rounded-full blur-3xl"></div>
    </div>

    {{-- Form --}}
    <div class="w-full md:w-1/2 p-8 lg:p-12 relative flex flex-col justify-center">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">បង្កើតពាក្យសម្ងាត់ថ្មី</h3>
        <p class="text-xs text-gray-400 dark:text-gray-400 mb-6">សូមបញ្ចូលពាក្យសម្ងាត់ថ្មីរបស់អ្នកខាងក្រោម ដើម្បីធ្វើបច្ចុប្បន្នភាពគណនី។</p>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
            @csrf
            {{-- Hidden inputs សម្រាប់ផ្ទុក Token និង Email ផ្ទៀងផ្ទាត់ --}}
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            {{-- ប្រអប់ពាក្យសម្ងាត់ថ្មី --}}
            <div x-data="{ show: false }">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">ពាក្យសម្ងាត់ថ្មី</label>
                <div class="relative group">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="យ៉ាងហោចណាស់ ៨ ខ្ទង់"
                        class="w-full pl-11 pr-12 py-3 text-gray-900 bg-gray-50 border @error('password') border-red-500 @else border-gray-200 @enderror rounded-xl outline-none focus:ring-2 ring-blue-500/50 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('password')
                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- ប្រអប់បញ្ជាក់ពាក្យសម្ងាត់ថ្មី --}}
            <div x-data="{ show: false }">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">បញ្ជាក់ពាក្យសម្ងាត់ថ្មី</label>
                <div class="relative group">
                    <i class="fas fa-check-circle absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder="វាយពាក្យសម្ងាត់ខាងលើឡើងវិញ"
                        class="w-full pl-11 pr-12 py-3 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 ring-blue-500/50 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#002B5B] dark:bg-blue-600 text-white py-3.5 rounded-xl font-bold shadow-lg hover:bg-opacity-90 transition-all transform active:scale-95 text-sm">
                រក្សាទុកពាក្យសម្ងាត់ថ្មី
            </button>
        </form>
    </div>
</div>
@endsection