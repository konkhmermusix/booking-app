@extends('layouts.auth')
@section('title', 'ចុះឈ្មោះ')
@section('content')
<div class="max-w-5xl w-full bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
    {{-- Left Banner --}}
    <div class="hidden lg:flex lg:w-1/3 bg-[#002B5B] dark:bg-black p-10 text-white flex-col justify-center">
        <h2 class="text-3xl font-bold mb-4 italic">សណ្ឋាគារ <span class="text-yellow-400 font-serif">ភីអេនធី</span></h2>
        <p class="text-sm opacity-70">បង្កើតគណនីថ្មី</p>
    </div>

    {{-- Form --}}
    <div class="w-full lg:w-2/3 p-8 md:p-12 relative">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">ចុះឈ្មោះ</h3>

        <form action="{{ route('register') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf
            @if(request()->has('redirect'))
            <input type="hidden" name="redirect" value="{{ request()->get('redirect') }}">
            @endif

            {{-- Name --}}
            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                    ឈ្មោះពេញ <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="ឈ្មោះពេញ"
                        class="w-full pl-11 pr-4 py-3 text-gray-900 bg-gray-50 border @error('name') border-red-500 @else border-gray-200 @enderror rounded-xl outline-none focus:ring-2 ring-blue-500/50 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Phone --}}
            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                    លេខទូរស័ព្ទ <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="លេខទូរស័ព្ទ"
                        class="w-full pl-11 pr-4 py-3 text-gray-900 bg-gray-50 border @error('phone') border-red-500 @else border-gray-200 @enderror rounded-xl outline-none focus:ring-2 ring-blue-500/50 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                    អ៊ីមែល <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="អ៊ីមែល"
                        class="w-full pl-11 pr-4 py-3 text-gray-900 bg-gray-50 border @error('email') border-red-500 @else border-gray-200 @enderror rounded-xl outline-none focus:ring-2 ring-blue-500/50 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div class="md:col-span-1" x-data="{ show: false }">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                    ពាក្យសម្ងាត់<span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="ពាក្យសម្ងាត់"
                        class="w-full pl-11 pr-12 py-3 text-gray-900 bg-gray-50 border @error('password') border-red-500 @else border-gray-200 @enderror rounded-xl outline-none focus:ring-2 ring-blue-500/50 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="md:col-span-1" x-data="{ show: false }">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                    បញ្ជាក់ពាក្យសម្ងាត់ <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <i class="fas fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder="បញ្ជាក់ពាក្យសម្ងាត់"
                        class="w-full pl-11 pr-12 py-3 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 ring-blue-500/50 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="md:col-span-2 pt-4">
                <button type="submit" class="w-full bg-[#002B5B] dark:bg-blue-600 text-white py-3.5 rounded-xl font-bold shadow-lg hover:bg-opacity-90 transition-all transform active:scale-95">
                    ចុះឈ្មោះ
                </button>
                <p class="text-center text-sm text-gray-500 mt-6">
                    មានគណនីរួចហើយ? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">ចូលប្រើ</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection