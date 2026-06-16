@extends('layouts.app')
@section('title', 'ប្រវត្តិរូប')

@section('content')

<div class="container mx-auto" x-data="{ tab: 'personal' }">
    <section class="py-10 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-10">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-1.5 bg-blue-600 rounded-full"></div>
                    <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                        ការកំណត់គណនី
                    </h4>
                </div>
            </div>

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

                        <button @click="tab = 'delete_account'"
                            :class="tab === 'delete_account' ? 'bg-red-600 text-white' : 'text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30'"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all">
                            <i class="fas fa-trash-alt"></i> លុបគណនីចោល
                        </button>
                    </nav>
                </aside>

                <div class="flex-1">

                    <div x-show="tab === 'personal'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-8">
                            <h2 class="text-xl font-bold mb-6 dark:text-white">ព័ត៌មានផ្ទាល់ខ្លួន</h2>

                            <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                                        @error('avatar') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">ឈ្មោះពេញ *</label>
                                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                            class="w-full h-[52px] px-4 bg-gray-50 dark:bg-gray-800 border border-transparent rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all @error('name') border-red-500 ring-red-500 @enderror">
                                        @error('name') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">លេខទូរស័ព្ទ *</label>
                                        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                            class="w-full h-[52px] px-4 bg-gray-50 dark:bg-gray-800 border border-transparent rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all @error('phone') border-red-500 ring-red-500 @enderror">
                                        @error('phone') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">អ៊ីមែល *</label>
                                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                            class="w-full h-[52px] px-4 bg-gray-50 dark:bg-gray-800 border border-transparent rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all @error('email') border-red-500 ring-red-500 @enderror">
                                        @error('email') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-4">
                                    <a href="/" class="px-6 py-3 rounded-xl font-bold border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">បោះបង់</a>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition">រក្សាទុក</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div x-show="tab === 'password'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
                        x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-8">
                            <h2 class="text-xl font-bold mb-2 dark:text-white">ប្តូរលេខសម្ងាត់</h2>
                            <p class="text-sm text-gray-500 mb-8">ដើម្បីសុវត្ថិភាព គួរប្តូរលេខសម្ងាត់ដែលមានអក្សរ លេខ និងនិមិត្តសញ្ញាបញ្ចូលគ្នា។</p>

                            <form action="{{ route('setting.password.update') }}" method="POST" class="space-y-6">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">លេខសម្ងាត់បច្ចុប្បន្ន *</label>
                                        <div class="relative flex items-center bg-gray-50 dark:bg-gray-800 rounded-xl border border-transparent focus-within:ring-2 focus-within:ring-blue-500 transition-all @error('current_password') border-red-500 focus-within:ring-red-500 @enderror">
                                            <input :type="showCurrent ? 'text' : 'password'" name="current_password"
                                                class="w-full h-[52px] pl-4 pr-12 bg-transparent border-none outline-none dark:text-white text-sm focus:ring-0">
                                            <button type="button" @click="showCurrent = !showCurrent" class="absolute right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none">

                                            </button>
                                        </div>
                                        @error('current_password') <span class="text-xs text-red-500 ml-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">លេខសម្ងាត់ថ្មី *</label>
                                        <div class="relative flex items-center bg-gray-50 dark:bg-gray-800 rounded-xl border border-transparent focus-within:ring-2 focus-within:ring-blue-500 transition-all @error('password') border-red-500 focus-within:ring-red-500 @enderror">
                                            <input :type="showNew ? 'text' : 'password'" name="password"
                                                class="w-full h-[52px] pl-4 pr-12 bg-transparent border-none outline-none dark:text-white text-sm focus:ring-0">
                                            <button type="button" @click="showNew = !showNew" class="absolute right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none">

                                            </button>
                                        </div>
                                        @error('password') <span class="text-xs text-red-500 ml-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">បញ្ជាក់លេខសម្ងាត់ថ្មី *</label>
                                        <div class="relative flex items-center bg-gray-50 dark:bg-gray-800 rounded-xl border border-transparent focus-within:ring-2 focus-within:ring-blue-500 transition-all">
                                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                                                class="w-full h-[52px] pl-4 pr-12 bg-transparent border-none outline-none dark:text-white text-sm focus:ring-0">
                                            <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none">
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="button" @click="tab = 'personal'" class="px-6 py-3 rounded-xl font-bold border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">បោះបង់</button>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition">ប្តូរលេខសម្ងាត់</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div x-show="tab === 'delete_account'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-data="{ showDeletePass: false }">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-red-100 dark:border-red-950/30 p-8">
                            <h2 class="text-xl font-bold text-red-600 mb-2">តើអ្នកពិតជាចង់លុបគណនីនេះមែនទេ?</h2>
                            <p class="text-sm text-gray-500 mb-6">នៅពេលដែលអ្នកលុបគណនី រាល់ទិន្នន័យទាំងអស់ (ព័ត៌មានផ្ទាល់ខ្លួន រូបភាព Profile និងប្រវត្តិការកក់) នឹងត្រូវលុបចោលជាអចិន្ត្រៃយ៍ ហើយមិនអាចទាញយកមកវិញបានឡើយ។</p>

                            <form action="{{ route('setting.destroy') }}" method="POST" class="space-y-6">
                                @csrf @method('DELETE')

                                <div class="max-w-md space-y-2">
                                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">សូមវាយលេខសម្ងាត់របស់អ្នកដើម្បីបញ្ជាក់ *</label>
                                    <div class="relative flex items-center bg-gray-50 dark:bg-gray-800 rounded-xl border border-transparent focus-within:ring-2 focus-within:ring-red-500 transition-all @error('delete_password') border-red-500 focus-within:ring-red-500 @enderror">
                                        <input :type="showDeletePass ? 'text' : 'password'" name="delete_password" placeholder="បញ្ចូលលេខសម្ងាត់បច្ចុប្បន្ន"
                                            class="w-full h-[52px] pl-4 pr-12 bg-transparent border-none outline-none dark:text-white text-sm focus:ring-0">
                                        <button type="button" @click="showDeletePass = !showDeletePass" class="absolute right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none">
                                        </button>
                                    </div>
                                    @error('delete_password') <span class="text-xs text-red-500 ml-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex justify-start gap-3 pt-2">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-red-500/30 transition">យល់ព្រមលុបគណនី</button>
                                    <button type="button" @click="tab = 'personal'" class="px-6 py-3 rounded-xl font-bold border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">មិនលុបទេ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('preview').src = reader.result;
        };
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection