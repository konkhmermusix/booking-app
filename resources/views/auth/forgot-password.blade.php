@extends('layouts.auth')
@section('title', 'ភ្លេចពាក្យសម្ងាត់')
@section('content')
<div class="max-w-4xl w-full bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
    {{-- Banner --}}
    <div class="hidden md:flex md:w-1/2 bg-[#002B5B] dark:bg-black p-12 text-white flex-col justify-center relative overflow-hidden">
        <h2 class="text-4xl font-bold mb-4 italic">សណ្ឋាគារ <span class="text-yellow-400 font-serif">ភីអេនធី</span></h2>
        <p class="text-blue-100 opacity-80"></p>
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-yellow-400/10 rounded-full blur-3xl"></div>
    </div>

    {{-- Form --}}
    <div class="w-full md:w-1/2 p-8 lg:p-12 relative flex flex-col justify-center">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">ភ្លេចពាក្យសម្ងាត់?</h3>
        <p class="text-xs text-gray-400 dark:text-gray-400 mb-6">សូមបញ្ចូលអ៊ីមែលគណនីរបស់អ្នក។ យើងនឹងផ្ញើតំណភ្ជាប់ដើម្បីកំណត់ពាក្យសម្ងាត់ថ្មីទៅកាន់ប្រអប់សំបុត្ររបស់អ្នក។</p>

        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">អ៊ីមែលគណនី</label>
                <div class="relative group">
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="បញ្ចូលអ៊ីមែល"
                        class="w-full pl-11 pr-4 py-3 text-gray-900 bg-gray-50 border @error('email') border-red-500 @else border-gray-200 @enderror rounded-xl outline-none focus:ring-2 ring-blue-500/50 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                </div>
                @error('email')
                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-[#002B5B] dark:bg-blue-600 text-white py-3.5 rounded-xl font-bold shadow-lg hover:bg-opacity-90 transition-all transform active:scale-95 text-sm">
                ផ្ញើតំណភ្ជាប់កំណត់ឡើងវិញ
            </button>

            <p class="text-center text-sm text-gray-500 mt-6">
                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline inline-flex items-center gap-2">
                    បោះបង់
                </a>
            </p>
        </form>
    </div>
</div>
@endsection