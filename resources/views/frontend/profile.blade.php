@extends('layouts.app')
@section('title', 'ប្រវត្ថិរូប')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-8xl" x-data="{ tab: 'personal' }">

    <h1 class="text-3xl font-bold mb-8 dark:text-white">ការកំណត់គណនី</h1>

    <div class="flex flex-col md:flex-row gap-8">
        <aside class="w-full md:w-1/4">
            <nav class="flex flex-col space-y-2 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                <button @click="tab = 'personal'"
                    :class="tab === 'personal' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800'"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all">
                    <i class="fas fa-user-edit"></i> ព័ត៌មានផ្ទាល់ខ្លួន
                </button>

                <button @click="tab = 'password'"
                    :class="tab === 'password' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800'"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all">
                    <i class="fas fa-key"></i> ប្តូរលេខសម្ងាត់
                </button>

                <div class="border-t dark:border-gray-800 my-2"></div>

                <a href="/my-bookings" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <i class="fas fa-calendar-check"></i> ប្រវត្តិការកក់
                </a>
            </nav>
        </aside>

        <div class="flex-1">

            <div x-show="tab === 'personal'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-8">
                    <h2 class="text-xl font-bold mb-6">ព័ត៌មានផ្ទាល់ខ្លួន</h2>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf @method('PUT')

                        <div class="flex items-center gap-6 mb-8 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl">
                            <div class="relative group">
                                <img id="preview" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}"
                                    class="w-24 h-24 rounded-2xl object-cover border-2 border-white shadow-md">
                                <label for="avatar" class="absolute -bottom-2 -right-2 bg-blue-600 text-white p-2 rounded-lg cursor-pointer hover:bg-blue-700 shadow-lg transition">
                                    <i class="fas fa-camera text-xs"></i>
                                </label>
                                <input type="file" id="avatar" name="avatar" class="hidden" onchange="previewImage(event)">
                            </div>
                            <div>
                                <h4 class="font-bold dark:text-white">រូបភាព Profile</h4>
                                <p class="text-xs text-gray-500">JPG, PNG ឬ JPEG (ទំហំអតិបរមា 2MB)</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold">ឈ្មោះពេញ</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full px-4 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold">លេខទូរស័ព្ទ</label>
                                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" class="w-full px-4 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-semibold">អ៊ីមែល</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full px-4 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition">រក្សាទុក</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="tab === 'password'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-8">
                    <h2 class="text-xl font-bold mb-2">ប្តូរលេខសម្ងាត់</h2>
                    <p class="text-sm text-gray-500 mb-8">ដើម្បីសុវត្ថិភាព គួរប្តូរលេខសម្ងាត់ដែលមានអក្សរ លេខ និងនិមិត្តសញ្ញាបញ្ចូលគ្នា។</p>

                    <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-6">
                        @csrf @method('PUT')
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold">លេខសម្ងាត់បច្ចុប្បន្ន</label>
                                <input type="password" name="current_password" class="w-full px-4 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-800 focus:ring-2 focus:ring-red-500 outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold">លេខសម្ងាត់ថ្មី</label>
                                <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold">បញ្ជាក់លេខសម្ងាត់ថ្មី</label>
                                <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-gray-900 dark:bg-blue-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg transition">ប្តូរលេខសម្ងាត់</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('preview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection