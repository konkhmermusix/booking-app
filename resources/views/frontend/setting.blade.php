@extends('layouts.app')
@section('title', 'ការកំណត់គណនី')

@section('content')

<div class="container mx-auto px-4 py-10" 
    x-data="{ 
        tab: '{{ $errors->has('current_password') || $errors->has('password') ? 'password' : ($errors->has('delete_password') ? 'delete_account' : 'personal') }}',
        removeAvatar: false
    }">
    <section class="">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-1.5 bg-blue-600 rounded-full"></div>
                    <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        ការកំណត់គណនី
                    </h4>
                </div>
            </div>

            {{-- PROFILE HEADER CARD & QUICK STATS --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-5 w-full md:w-auto">
                    <div class="relative shrink-0">
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=002B5B&color=ffffff&bold=true&length=1' }}"
                            class="w-16 h-16 rounded-2xl object-cover border-2 border-blue-500 shadow-md">
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white dark:border-gray-900 rounded-full" title="Active"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h3>
                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 capitalize">
                                {{ Auth::user()->role ?? 'Customer' }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <i class="fas fa-envelope mr-1"></i> {{ Auth::user()->email }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-calendar-alt mr-1"></i> សមាជិកតាំងពី {{ Auth::user()->created_at ? Auth::user()->created_at->format('M Y') : '២០២៦' }}
                        </p>
                    </div>
                </div>

                {{-- STATS & QUICK ACTION --}}
                <div class="flex items-center gap-4 w-full md:w-auto justify-end border-t md:border-t-0 pt-4 md:pt-0 border-gray-100 dark:border-gray-800">
                    <div class="text-center px-4 py-2 bg-blue-50/50 dark:bg-gray-800/60 rounded-xl border border-blue-100/50 dark:border-gray-700/50 min-w-[100px]">
                        <span class="block text-xl font-extrabold text-blue-600 dark:text-blue-400">{{ $totalBookings ?? 0 }}</span>
                        <span class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">ការកក់សរុប</span>
                    </div>

                    <a href="/mybookings" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl font-bold text-xs shadow-md shadow-blue-500/20 transition">
                        <i class="fas fa-calendar-check"></i> ប្រវត្តិការកក់
                    </a>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-8">
                {{-- SIDEBAR TABS --}}
                <aside class="w-full md:w-1/4 shrink-0">
                    <nav class="flex flex-col space-y-2 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                        <button type="button" @click="tab = 'personal'"
                            :class="tab === 'personal' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800'"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all cursor-pointer text-left">
                            <i class="fas fa-user-edit"></i> ព័ត៌មានផ្ទាល់ខ្លួន
                        </button>

                        <button type="button" @click="tab = 'password'"
                            :class="tab === 'password' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800'"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all cursor-pointer text-left">
                            <i class="fas fa-key"></i> ប្តូរលេខសម្ងាត់
                        </button>

                        <div class="border-t dark:border-gray-800 my-2"></div>

                        <button type="button" @click="tab = 'delete_account'"
                            :class="tab === 'delete_account' ? 'bg-red-600 text-white' : 'text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30'"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all cursor-pointer text-left">
                            <i class="fas fa-trash-alt"></i> លុបគណនីចោល
                        </button>
                    </nav>
                </aside>

                {{-- TAB CONTENTS --}}
                <div class="flex-1 min-w-0">

                    {{-- TAB 1: PERSONAL INFORMATION --}}
                    <div x-show="tab === 'personal'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 md:p-8">
                            <h2 class="text-xl font-bold mb-6 dark:text-white">ព័ត៌មានផ្ទាល់ខ្លួន</h2>

                            <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf @method('PUT')
                                <input type="hidden" name="remove_avatar" :value="removeAvatar ? '1' : '0'">

                                <div class="flex items-center gap-6 mb-8 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl">
                                    <div class="relative group shrink-0">
                                        <img id="preview" 
                                            src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=002B5B&color=ffffff&bold=true&length=1' }}"
                                            class="w-24 h-24 rounded-2xl object-cover border-2 border-white dark:border-gray-700 shadow-md">
                                        <label for="avatar" class="absolute -bottom-2 -right-2 bg-blue-600 text-white p-2 rounded-lg cursor-pointer hover:bg-blue-700 shadow-lg transition" title="ប្តូររូបថត">
                                            <i class="fas fa-camera text-xs"></i>
                                        </label>
                                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewImage(event)">
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="font-bold dark:text-white text-base">រូបភាព Profile</h4>
                                        
                                        @if(Auth::user()->avatar)
                                        <button type="button" @click="removeAvatar = true; document.getElementById('preview').src = 'https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=002B5B&color=ffffff&bold=true&length=1'" class="text-xs text-red-500 hover:underline pt-1 inline-block font-semibold">
                                            <i class="fas fa-trash-alt mr-1"></i> លុបរូប Profile ចេញ
                                        </button>
                                        @endif
                                        @error('avatar') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">ឈ្មោះពេញ *</label>
                                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                            class="w-full h-[52px] px-4 bg-gray-50 dark:bg-gray-800 border border-transparent rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all @error('name') border-red-500 ring-red-500 @enderror">
                                        @error('name') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">លេខទូរស័ព្ទ</label>
                                        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" placeholder="ឧទាហរណ៍៖ 012345678"
                                            class="w-full h-[52px] px-4 bg-gray-50 dark:bg-gray-800 border border-transparent rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all @error('phone') border-red-500 ring-red-500 @enderror">
                                        @error('phone') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">អ៊ីមែល *</label>
                                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                                            class="w-full h-[52px] px-4 bg-gray-50 dark:bg-gray-800 border border-transparent rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all @error('email') border-red-500 ring-red-500 @enderror">
                                        @error('email') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                {{-- CONNECTED SOCIAL ACCOUNTS --}}
                                <div class="pt-6 border-t dark:border-gray-800">
                                    <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-4">
                                        <i class="fas fa-link text-blue-500 mr-2"></i> គណនីដែលបានភ្ជាប់
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        {{-- Google Connection --}}
                                        <div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-gray-800/60 rounded-xl border border-gray-100 dark:border-gray-700">
                                            <div class="flex items-center gap-3">
                                                <i class="fab fa-google text-red-500 text-lg"></i>
                                                <div>
                                                    <p class="text-xs font-bold dark:text-white">Google Account</p>
                                                    <p class="text-[11px] text-gray-500 dark:text-gray-400">
                                                        {{ Auth::user()->google_id ? 'បានភ្ជាប់រួចរាល់' : 'មិនទាន់បានភ្ជាប់' }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if(Auth::user()->google_id)
                                                <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 rounded-lg">
                                                    <i class="fas fa-check-circle mr-1"></i> ភ្ជាប់រួច
                                                </span>
                                            @else
                                                <a href="{{ route('auth.google') }}" class="text-xs font-bold text-blue-600 hover:underline">
                                                    ភ្ជាប់ឥឡូវនេះ
                                                </a>
                                            @endif
                                        </div>

                                        {{-- Facebook Connection --}}
                                        <div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-gray-800/60 rounded-xl border border-gray-100 dark:border-gray-700">
                                            <div class="flex items-center gap-3">
                                                <i class="fab fa-facebook text-blue-600 text-lg"></i>
                                                <div>
                                                    <p class="text-xs font-bold dark:text-white">Facebook Account</p>
                                                    <p class="text-[11px] text-gray-500 dark:text-gray-400">
                                                        {{ Auth::user()->facebook_id ? 'បានភ្ជាប់រួចរាល់' : 'មិនទាន់បានភ្ជាប់' }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if(Auth::user()->facebook_id)
                                                <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 rounded-lg">
                                                    <i class="fas fa-check-circle mr-1"></i> ភ្ជាប់រួច
                                                </span>
                                            @else
                                                <a href="{{ route('auth.facebook') }}" class="text-xs font-bold text-blue-600 hover:underline">
                                                    ភ្ជាប់ឥឡូវនេះ
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800">
                                    <a href="/" class="px-6 py-3 rounded-xl font-bold border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">បោះបង់</a>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition cursor-pointer">រក្សាទុក</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- TAB 2: CHANGE PASSWORD --}}
                    <div x-show="tab === 'password'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
                        x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 md:p-8">
                            <h2 class="text-xl font-bold mb-2 dark:text-white">ប្តូរលេខសម្ងាត់</h2>
                            <p class="text-sm text-gray-500 mb-8">ដើម្បីសុវត្ថិភាព គួរប្តូរលេខសម្ងាត់ដែលមានអក្សរ លេខ និងនិមិត្តសញ្ញាបញ្ចូលគ្នា។</p>

                            <form action="{{ route('setting.password.update') }}" method="POST" class="space-y-6">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">លេខសម្ងាត់បច្ចុប្បន្ន *</label>
                                        <div class="relative flex items-center bg-gray-50 dark:bg-gray-800 rounded-xl border border-transparent focus-within:ring-2 focus-within:ring-blue-500 transition-all @error('current_password') border-red-500 focus-within:ring-red-500 @enderror">
                                            <input :type="showCurrent ? 'text' : 'password'" name="current_password" required placeholder="••••••••"
                                                class="w-full h-[52px] pl-4 pr-12 bg-transparent border-none outline-none dark:text-white text-sm focus:ring-0">
                                            <button type="button" @click="showCurrent = !showCurrent" class="absolute right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none cursor-pointer">
                                                <i class="fa-solid" :class="showCurrent ? 'fa-eye-slash text-blue-600' : 'fa-eye'"></i>
                                            </button>
                                        </div>
                                        @error('current_password') <span class="text-xs text-red-500 ml-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">លេខសម្ងាត់ថ្មី *</label>
                                        <div class="relative flex items-center bg-gray-50 dark:bg-gray-800 rounded-xl border border-transparent focus-within:ring-2 focus-within:ring-blue-500 transition-all @error('password') border-red-500 focus-within:ring-red-500 @enderror">
                                            <input :type="showNew ? 'text' : 'password'" name="password" required placeholder="យ៉ាងហោចណាស់ ៨ ខ្ទង់"
                                                class="w-full h-[52px] pl-4 pr-12 bg-transparent border-none outline-none dark:text-white text-sm focus:ring-0">
                                            <button type="button" @click="showNew = !showNew" class="absolute right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none cursor-pointer">
                                                <i class="fa-solid" :class="showNew ? 'fa-eye-slash text-blue-600' : 'fa-eye'"></i>
                                            </button>
                                        </div>
                                        @error('password') <span class="text-xs text-red-500 ml-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">បញ្ជាក់លេខសម្ងាត់ថ្មី *</label>
                                        <div class="relative flex items-center bg-gray-50 dark:bg-gray-800 rounded-xl border border-transparent focus-within:ring-2 focus-within:ring-blue-500 transition-all">
                                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required placeholder="បញ្ចូលលេខសម្ងាត់ថ្មីម្តងទៀត"
                                                class="w-full h-[52px] pl-4 pr-12 bg-transparent border-none outline-none dark:text-white text-sm focus:ring-0">
                                            <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none cursor-pointer">
                                                <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash text-blue-600' : 'fa-eye'"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800">
                                    <button type="button" @click="tab = 'personal'" class="px-6 py-3 rounded-xl font-bold border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">បោះបង់</button>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition cursor-pointer">ប្តូរលេខសម្ងាត់</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- TAB 3: DELETE ACCOUNT --}}
                    <div x-show="tab === 'delete_account'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-data="{ showDeletePass: false }">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-red-100 dark:border-red-950/30 p-6 md:p-8">
                            <h2 class="text-xl font-bold text-red-600 mb-2">តើអ្នកពិតជាចង់លុបគណនីនេះមែនទេ?</h2>
                            <p class="text-sm text-gray-500 mb-6">នៅពេលដែលអ្នកលុបគណនី រាល់ទិន្នន័យទាំងអស់ (ព័ត៌មានផ្ទាល់ខ្លួន រូបភាព Profile និងប្រវត្តិការកក់) នឹងត្រូវលុបចោលជាអចិន្ត្រៃយ៍ ហើយមិនអាចទាញយកមកវិញបានឡើយ។</p>

                            <form id="deleteAccountForm" action="{{ route('setting.destroy') }}" method="POST" class="space-y-6">
                                @csrf @method('DELETE')

                                <div class="max-w-md space-y-2">
                                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">សូមវាយលេខសម្ងាត់របស់អ្នកដើម្បីបញ្ជាក់ *</label>
                                    <div class="relative flex items-center bg-gray-50 dark:bg-gray-800 rounded-xl border border-transparent focus-within:ring-2 focus-within:ring-red-500 transition-all @error('delete_password') border-red-500 focus-within:ring-red-500 @enderror">
                                        <input :type="showDeletePass ? 'text' : 'password'" name="delete_password" id="delete_password" required placeholder="បញ្ចូលលេខសម្ងាត់បច្ចុប្បន្ន"
                                            class="w-full h-[52px] pl-4 pr-12 bg-transparent border-none outline-none dark:text-white text-sm focus:ring-0">
                                        <button type="button" @click="showDeletePass = !showDeletePass" class="absolute right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none cursor-pointer">
                                            <i class="fa-solid" :class="showDeletePass ? 'fa-eye-slash text-red-600' : 'fa-eye'"></i>
                                        </button>
                                    </div>
                                    @error('delete_password') <span class="text-xs text-red-500 ml-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex justify-start gap-3 pt-2 border-t dark:border-gray-800">
                                    <button type="button" onclick="confirmDeleteAccount()" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-red-500/30 transition cursor-pointer">យល់ព្រមលុបគណនី</button>
                                    <button type="button" @click="tab = 'personal'" class="px-6 py-3 rounded-xl font-bold border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition cursor-pointer">មិនលុបទេ</button>
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

    function confirmDeleteAccount() {
        const passwordInput = document.getElementById('delete_password');
        if (!passwordInput || !passwordInput.value.trim()) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'សូមបញ្ចូលលេខសម្ងាត់!',
                    text: 'អ្នកត្រូវតែបញ្ចូលលេខសម្ងាត់បច្ចុប្បន្នដើម្បីបញ្ជាក់ការលុបគណនី។',
                    confirmButtonText: 'យល់ព្រម',
                    confirmButtonColor: '#ef4444'
                });
            } else {
                alert('សូមបញ្ចូលលេខសម្ងាត់បច្ចុប្បន្ន!');
            }
            return;
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'តើអ្នកពិតជាចង់លុបគណនីមែនទេ?',
                text: "ទិន្នន័យទាំងអស់របស់អ្នកនឹងត្រូវលុបចោលជាអចិន្ត្រៃយ៍ ហើយមិនអាចទាញយកមកវិញបានឡើយ!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'យល់ព្រមលុប',
                cancelButtonText: 'បោះបង់',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteAccountForm').submit();
                }
            });
        } else {
            document.getElementById('deleteAccountForm').submit();
        }
    }
</script>
@endsection


