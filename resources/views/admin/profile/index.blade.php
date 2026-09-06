@extends('layouts.admin')

@section('title', 'ការកំណត់គណនី & ប្រវត្តិរូប')

@section('content')
<div class="p-2 sm:p-2" x-data="{ 
    activeTab: 'profile',
    showCurrentPass: false,
    showNewPass: false,
    showConfirmPass: false,
    avatarPreview: '{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=002B5B&color=fff' }}'
}">

    {{-- Top Profile Header Banner --}}
    <div class="bg-gradient-to-r from-[#002B5B] via-indigo-900 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl mb-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 bottom-0 opacity-10 pointer-events-none flex items-center pr-10">
            <i class="fas fa-user-gear text-9xl"></i>
        </div>

        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 z-10 relative">
            <div class="relative group shrink-0">
                <img :src="avatarPreview" alt="{{ $user->name }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover border-4 border-white/20 shadow-2xl transition-all duration-300">
                <span class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 border-2 border-white rounded-full flex items-center justify-center text-[10px]" title="Online Status">
                    <i class="fas fa-check text-white"></i>
                </span>
            </div>

            <div class="text-center sm:text-left flex-1">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5 mb-1.5">
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight">{{ $user->name }}</h2>
                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 backdrop-blur-md text-amber-300 border border-white/10">
                        <i class="fas fa-shield-alt text-[10px] mr-1"></i> {{ ucfirst($user->role) }}
                    </span>
                </div>
                <p class="text-xs text-blue-200 font-medium flex items-center justify-center sm:justify-start gap-3 flex-wrap">
                    <span><i class="fas fa-envelope mr-1 text-blue-300"></i>{{ $user->email }}</span>
                    @if($user->phone)
                    <span><i class="fas fa-phone mr-1 text-blue-300"></i>{{ $user->phone }}</span>
                    @endif
                    <span><i class="fas fa-calendar-alt mr-1 text-blue-300"></i>សមាជិកតាំងពី {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</span>
                </p>
            </div>
        </div>

        {{-- Tab Navigation Buttons --}}
        <div class="flex items-center gap-2 mt-8 pt-4 border-t border-white/10 overflow-x-auto custom-scrollbar">
            <button @click="activeTab = 'profile'"
                :class="activeTab === 'profile' ? 'bg-white text-[#002B5B] font-black shadow-lg' : 'bg-white/10 text-white/80 hover:bg-white/20 font-bold'"
                class="px-5 py-2.5 rounded-xl text-xs flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap">
                <i class="fas fa-user-circle text-sm"></i>
                <span>ព័ត៌មានគណនី (Profile)</span>
            </button>

            <button @click="activeTab = 'password'"
                :class="activeTab === 'password' ? 'bg-white text-[#002B5B] font-black shadow-lg' : 'bg-white/10 text-white/80 hover:bg-white/20 font-bold'"
                class="px-5 py-2.5 rounded-xl text-xs flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap">
                <i class="fas fa-key text-sm"></i>
                <span>ប្តូរលេខសម្ងាត់ (Password)</span>
            </button>

            <button @click="activeTab = 'others'"
                :class="activeTab === 'others' ? 'bg-white text-[#002B5B] font-black shadow-lg' : 'bg-white/10 text-white/80 hover:bg-white/20 font-bold'"
                class="px-5 py-2.5 rounded-xl text-xs flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap">
                <i class="fas fa-sliders-h text-sm"></i>
                <span>ការកំណត់ផ្សេងៗ (Preferences)</span>
            </button>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 p-4 rounded-2xl border border-emerald-200 dark:border-emerald-800 flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-3">
            <i class="fas fa-circle-check text-xl text-emerald-500"></i>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
        <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-lg cursor-pointer">&times;</button>
    </div>
    @endif

    {{-- Error Alert --}}
    @if($errors->any())
    <div class="mb-6 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 p-4 rounded-2xl border border-rose-200 dark:border-rose-800 shadow-xs">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-triangle-exclamation text-xl text-rose-500"></i>
            <span class="text-sm font-bold">សូមពិនិត្យព័ត៌មានខាងក្រោម៖</span>
        </div>
        <ul class="list-disc pl-9 text-xs font-medium space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- TAB 1: PROFILE EDIT FORM --}}
    <div x-show="activeTab === 'profile'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2">
        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-800">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
                <div>
                    <h3 class="text-lg font-black dark:text-white flex items-center gap-2">
                        <i class="fas fa-user-edit text-blue-600 dark:text-blue-400"></i>
                        កែប្រែព័ត៌មានគណនីផ្ទាល់ខ្លួន
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">ធ្វើបច្ចុប្បន្នភាពឈ្មោះ អ៊ីមែល លេខទូរស័ព្ទ និងរូបថតគណនីរបស់អ្នក</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    {{-- Avatar Upload & Preview --}}
                    <div class="p-5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row items-center gap-6">
                        <div class="relative shrink-0">
                            <img :src="avatarPreview" class="w-20 h-20 rounded-2xl object-cover border-2 border-gray-200 dark:border-gray-700 shadow-md">
                        </div>
                        <div class="space-y-2 text-center sm:text-left flex-1">
                            <label class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                រូបថតផ្ទាល់ខ្លួន (Profile Picture)
                            </label>
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                                <label class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md transition-all cursor-pointer flex items-center gap-2 active:scale-95">
                                    <i class="fas fa-upload"></i>
                                    <span>ជ្រើសរើសរូបថតថ្មី</span>
                                    <input type="file" name="avatar" accept="image/*" class="hidden" @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            avatarPreview = URL.createObjectURL(file);
                                        }
                                    ">
                                </label>

                                @if($user->avatar)
                                <label class="px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/40 dark:text-rose-400 rounded-xl font-bold text-xs transition-all cursor-pointer flex items-center gap-1.5">
                                    <input type="checkbox" name="remove_avatar" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                                    <span>លុបរូបថតចាស់</span>
                                </label>
                                @endif
                            </div>
                            <p class="text-[10px] text-gray-400 font-medium">ទំហំអនុញ្ញាតអតិបរមា 20MB (ប្រភេទ៖ JPG, PNG, WEBP)</p>
                        </div>
                    </div>

                    {{-- Form Fields --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase text-gray-700 dark:text-gray-300 tracking-wider">
                                ឈ្មោះពេញ <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full h-12 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-blue-500 text-sm transition-all"
                                placeholder="បញ្ចូលឈ្មោះពេញរបស់អ្នក">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase text-gray-700 dark:text-gray-300 tracking-wider">
                                អ៊ីមែល <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full h-12 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-blue-500 text-sm transition-all"
                                placeholder="example@domain.com">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase text-gray-700 dark:text-gray-300 tracking-wider">
                                លេខទូរស័ព្ទ
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full h-12 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-blue-500 text-sm transition-all"
                                placeholder="012 XXXXXX">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase text-gray-400 tracking-wider">
                                តួនាទីក្នុងប្រព័ន្ធ (Role)
                            </label>
                            <input type="text" value="{{ ucfirst($user->role) }}" disabled
                                class="w-full h-12 px-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-100 dark:bg-gray-800/40 text-gray-500 dark:text-gray-400 font-bold text-sm cursor-not-allowed">
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                        <button type="submit" class="px-8 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-500/20 active:scale-95 transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fas fa-floppy-disk"></i>
                            <span>រក្សាទុកព័ត៌មាន</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TAB 2: CHANGE PASSWORD FORM --}}
    <div x-show="activeTab === 'password'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2">
        <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-800 max-w-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
                <div>
                    <h3 class="text-lg font-black dark:text-white flex items-center gap-2">
                        <i class="fas fa-lock text-amber-500"></i>
                        ប្តូរលេខសម្ងាត់គណនី
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">ដើម្បីសុវត្ថិភាពគណនី សូមប្រើប្រាស់លេខសម្ងាត់ដែលពិបាកទាយ</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update-password') }}" method="POST">
                @csrf
                <div class="space-y-6">

                    {{-- Current Password --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase text-gray-700 dark:text-gray-300 tracking-wider">
                            លេខសម្ងាត់បច្ចុប្បន្ន <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showCurrentPass ? 'text' : 'password'" name="current_password" required
                                class="w-full h-12 pl-4 pr-12 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-amber-500 text-sm transition-all"
                                placeholder="បញ្ចូលលេខសម្ងាត់ចាស់">
                            <button type="button" @click="showCurrentPass = !showCurrentPass" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas" :class="showCurrentPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- New Password --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase text-gray-700 dark:text-gray-300 tracking-wider">
                            លេខសម្ងាត់ថ្មី <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showNewPass ? 'text' : 'password'" name="password" required
                                class="w-full h-12 pl-4 pr-12 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-amber-500 text-sm transition-all"
                                placeholder="បញ្ចូលលេខសម្ងាត់ថ្មី (យ៉ាងតិច ៦ តួ)">
                            <button type="button" @click="showNewPass = !showNewPass" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas" :class="showNewPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Confirm New Password --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase text-gray-700 dark:text-gray-300 tracking-wider">
                            បញ្ជាក់លេខសម្ងាត់ថ្មី <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showConfirmPass ? 'text' : 'password'" name="password_confirmation" required
                                class="w-full h-12 pl-4 pr-12 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-amber-500 text-sm transition-all"
                                placeholder="បញ្ចូលលេខសម្ងាត់ថ្មីម្តងទៀត">
                            <button type="button" @click="showConfirmPass = !showConfirmPass" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas" :class="showConfirmPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Helpful Security Info Box --}}
                    <div class="p-4 bg-amber-50 dark:bg-amber-950/30 rounded-2xl border border-amber-200 dark:border-amber-900/40 text-amber-900 dark:text-amber-200 text-xs space-y-1 font-medium">
                        <p class="font-bold flex items-center gap-1.5 text-amber-800 dark:text-amber-300">
                            <i class="fas fa-shield-halved"></i> ណែនាំអំពីសុវត្ថិភាពលេខសម្ងាត់៖
                        </p>
                        <p class="text-[11px] text-amber-700 dark:text-amber-400 pl-5">
                            • លេខសម្ងាត់ត្រូវមានយ៉ាងតិច ៦ តួអក្សរ។<br>
                            • គួរមានបន្សំរវាងអក្សរតូច អក្សរធំ និងលេខ ដើម្បីបង្កើនសុវត្ថិភាព។
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                        <button type="submit" class="px-8 h-12 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-amber-500/20 active:scale-95 transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fas fa-key"></i>
                            <span>ប្តូរលេខសម្ងាត់</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TAB 3: OTHER PREFERENCES & SETTINGS --}}
    <div x-show="activeTab === 'others'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- System Settings Shortcut Card --}}
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <h3 class="text-lg font-black dark:text-white mb-1">ការកំណត់ប្រព័ន្ធ & អត្រាប្តូរប្រាក់</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">កំណត់តម្លៃអត្រាប្តូរប្រាក់រៀល (USD ➔ KHR), លេខទូរស័ព្ទទំនាក់ទំនងប្រព័ន្ធ, រូបសញ្ញា (Logo) និងបណ្តាញសង្គម</p>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('contacts_sett.index') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md transition-all inline-flex items-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        <span>ទៅកាន់ការកំណត់ប្រព័ន្ធ</span>
                    </a>
                </div>
            </div>

            {{-- Theme & Appearance Preference --}}
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3 class="text-lg font-black dark:text-white mb-1">ពណ៌ប្រព័ន្ធ (Theme Preference)</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">ជ្រើសរើសស្ទីលបង្ហាញប្រព័ន្ធរវាង Light Mode ឬ Dark Mode តាមតម្រូវការ</p>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center gap-4">
                    <button type="button" @click="isDark = false; localStorage.theme = 'light'"
                        :class="!isDark ? 'bg-blue-600 text-white font-black shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 font-bold'"
                        class="flex-1 py-3 px-4 rounded-xl text-xs flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <i class="fas fa-sun text-amber-400 text-sm"></i>
                        <span>Light Mode</span>
                    </button>

                    <button type="button" @click="isDark = true; localStorage.theme = 'dark'"
                        :class="isDark ? 'bg-blue-600 text-white font-black shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 font-bold'"
                        class="flex-1 py-3 px-4 rounded-xl text-xs flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <i class="fas fa-moon text-indigo-400 text-sm"></i>
                        <span>Dark Mode</span>
                    </button>
                </div>
            </div>

            {{-- Notification Settings Card --}}
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col justify-between md:col-span-2">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl mb-4">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3 class="text-lg font-black dark:text-white mb-1">ការជូនដំណឹងប្រព័ន្ធ (Notification Settings)</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">គ្រប់គ្រងការជូនដំណឹងអំពីការកក់បន្ទប់ កក់សាលប្រជុំ និងការផ្ញើសារពីអតិថិជន</p>
                    </div>

                    <a href="{{ route('admin.notifications.index') }}" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md transition-all inline-flex items-center gap-2 shrink-0">
                        <i class="fas fa-bell"></i>
                        <span>មើលការជូនដំណឹងទាំងអស់</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
