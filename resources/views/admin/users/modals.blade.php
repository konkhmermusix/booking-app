<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមអ្នកប្រើប្រាស់ថ្មី</h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest">Create New User</p>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-2 gap-4">

                        <div class="col-span-2">
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">ឈ្មោះពេញ</label>
                            <input type="text" name="name" placeholder="ឈ្មោះពេញ" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">អ៊ីមែល</label>
                            <input type="email" name="email" placeholder="អ៊ីមែល" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">លេខទូរស័ព្ទ</label>
                            <input type="text" name="phone" placeholder="លេខទូរស័ព្ទ"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">តួនាទី</label>
                            <div class="relative group">
                                <select name="role"
                                    class="w-full h-14 pl-6 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium text-sm relative z-0">
                                    <option value="customer">Customer</option>
                                    <option value="staff">Staff</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">ស្ថានភាព</label>
                            <div class="relative group">
                                <select name="status"
                                    class="w-full h-14 pl-6 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium text-sm relative z-0">
                                    <option value="active" selected>សកម្ម</option>
                                    <option value="pending">រង់ចាំ</option>
                                    <option value="inactive">អសកម្ម</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div class="col-span-2" x-data="{ 
                            avatarPreview: 'https://ui-avatars.com/api/?name=New+User&background=random&size=128',
                            previewImage(event) {
                                const file = event.target.files[0];
                                if (file) { this.avatarPreview = URL.createObjectURL(file); }
                            }
                        }">
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">រូបភាពទម្រង់</label>
                            <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-800/40 p-4 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                                <div class="relative group shrink-0">
                                    <img :src="avatarPreview" class="w-16 h-16 rounded-2xl object-cover shadow-sm bg-gray-100 dark:bg-gray-700 border-2 border-white dark:border-gray-800">
                                </div>
                                <div class="flex-1">
                                    <label class="inline-flex items-center gap-2 px-4 h-10 bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-650 text-gray-700 dark:text-gray-200 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 text-xs font-bold cursor-pointer transition-all active:scale-95">
                                        <i class="fas fa-cloud-upload-alt text-blue-500"></i>
                                        <span>ជ្រើសរើសរូបភាព</span>
                                        <input type="file" name="avatar" accept="image/*" @change="previewImage($event)" class="hidden">
                                    </label>
                                    <p class="text-[10px] text-gray-400 mt-1.5 font-medium">ប្រភេទឯកសារ PNG, JPG </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">ពាក្យសម្ងាត់</label>
                            <input type="password" name="password" placeholder="ពាក្យសម្ងាត់" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">បញ្ជាក់ពាក្យសម្ងាត់</label>
                            <input type="password" name="password_confirmation" placeholder="បញ្ជាក់ពាក្យសម្ងាត់" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false"
                        class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-all">
                        បោះបង់
                    </button>
                    <button type="submit"
                        class="px-8 h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/10 active:scale-95 transition-all">
                        រក្សាទុកទិន្នន័យ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showEditModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showEditModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែប្រែទិន្នន័យ៖ <span x-text="currentUser.name" class="text-blue-500"></span></h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest">Update User Profile</p>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/users') }}/${currentUser.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar"
                    x-data="{ 
                        avatarPreview: '',
                        init() {
                            this.$watch('currentUser', value => {
                                if (value && value.avatar) {
                                    this.avatarPreview = `{{ asset('storage') }}/${value.avatar}`;
                                } else {
                                    this.avatarPreview = `https://ui-avatars.com/api/?name=${encodeURIComponent(value?.name || 'User')}&background=random&size=128`;
                                }
                            });
                        },
                        previewImage(event) {
                            const file = event.target.files[0];
                            if (file) { this.avatarPreview = URL.createObjectURL(file); }
                        }
                     }">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">ឈ្មោះពេញ</label>
                            <input type="text" name="name" x-model="currentUser.name" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">អ៊ីមែល</label>
                            <input type="email" name="email" x-model="currentUser.email" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">លេខទូរស័ព្ទ</label>
                            <input type="text" name="phone" x-model="currentUser.phone"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">តួនាទី</label>
                            <div class="relative group">
                                <select name="role" x-model="currentUser.role"
                                    class="w-full h-14 pl-6 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium text-sm relative z-0">
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                    <option value="customer">Customer</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">ស្ថានភាព</label>
                            <div class="relative group">
                                <select name="status" x-model="currentUser.status"
                                    class="w-full h-14 pl-6 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium text-sm relative z-0">
                                    <option value="active">សកម្ម</option>
                                    <option value="pending">រង់ចាំ</option>
                                    <option value="inactive">អសកម្ម</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">ប្តូររូបភាពទម្រង់ (Avatar)</label>
                            <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-800/40 p-4 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                                <div class="relative group shrink-0">
                                    <img :src="avatarPreview" class="w-16 h-16 rounded-2xl object-cover shadow-sm bg-gray-100 dark:bg-gray-700 border-2 border-white dark:border-gray-800">
                                </div>
                                <div class="flex-1">
                                    <label class="inline-flex items-center gap-2 px-4 h-10 bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-650 text-gray-700 dark:text-gray-200 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 text-xs font-bold cursor-pointer transition-all active:scale-95">
                                        <i class="fas fa-image text-emerald-500"></i>
                                        <span>ជ្រើសរើសរូបភាពថ្មី</span>
                                        <input type="file" name="avatar" accept="image/*" @change="previewImage($event)" class="hidden">
                                    </label>
                                    <p class="text-[10px] text-gray-400 mt-1.5 font-medium">ជ្រើសរើសរូបភាពថ្មីដើម្បីផ្លាស់ប្តូររូបភាពចាស់</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">ពាក្យសម្ងាត់ថ្មី</label>
                            <input type="password" name="password" placeholder="ទុកទំនេរបើមិនចង់ប្តូរ"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">បញ្ជាក់ពាក្យសម្ងាត់ថ្មី</label>
                            <input type="password" name="password_confirmation" placeholder="បញ្ជាក់ពាក្យសម្ងាត់ថ្មី"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-center">
                        <p class="text-xs font-semibold text-blue-600 dark:text-blue-400">ទុកប្រអប់ពាក្យសម្ងាត់នៅទំនេរ ប្រសិនបើមិនចង់ផ្លាស់ប្តូរវា។</p>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 border-t dark:border-gray-800 rounded-b-2xl">
                    <button type="button" @click="showEditModal = false" class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-colors">
                        បោះបង់
                    </button>
                    <button type="submit" class="px-8 h-11 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-emerald-500/10 active:scale-95 transition-all">
                        ធ្វើបច្ចុប្បន្នភាព
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showDetailModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700 relative">
                <div class="absolute inset-0 opacity-25 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

                <button @click="showDetailModal = false" class="absolute top-5 right-5 w-9 h-9 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-all">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>

                <div class="absolute -bottom-10 left-10">
                    <img :src="currentUser.avatar ? '{{ asset('storage') }}/' + currentUser.avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(currentUser.name || 'User') + '&background=random&size=128'"
                        class="w-20 h-20 bg-white dark:bg-gray-900 rounded-2xl shadow-xl object-cover border-4 border-white dark:border-gray-900">
                </div>
            </div>

            <div class="pt-14 p-10 space-y-7">
                <div>
                    <h3 class="text-2xl font-black dark:text-white tracking-tight" x-text="currentUser.name"></h3>
                    <p class="text-sm text-gray-400 font-bold mt-0.5" x-text="currentUser.email"></p>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block">លេខទូរស័ព្ទ</span>
                        <p class="font-bold dark:text-gray-200 text-sm" x-text="currentUser.phone || 'មិនមាន'"></p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block">តួនាទី</span>
                        <p class="font-bold dark:text-gray-200 text-sm uppercase" x-text="currentUser.role"></p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block">Google ID</span>
                        <p class="font-mono text-xs text-gray-600 dark:text-gray-300 truncate" :title="currentUser.google_id" x-text="currentUser.google_id || 'មិនបានភ្ជាប់'"></p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block">Facebook ID</span>
                        <p class="font-mono text-xs text-gray-600 dark:text-gray-300 truncate" :title="currentUser.facebook_id" x-text="currentUser.facebook_id || 'មិនបានភ្ជាប់'"></p>
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-gray-800">

                <div class="flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block">ស្ថានភាពគណនី</span>
                        <div class="mt-1">
                            <template x-if="currentUser.status === 'active'">
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-[10px] font-black uppercase rounded-lg border border-green-200 dark:border-green-500/20">សកម្ម</span>
                            </template>
                            <template x-if="currentUser.status === 'pending'">
                                <span class="px-3 py-1 bg-amber-100 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase rounded-lg border border-amber-200 dark:border-amber-500/20">រង់ចាំ</span>
                            </template>
                            <template x-if="currentUser.status === 'inactive'">
                                <span class="px-3 py-1 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-[10px] font-black uppercase rounded-lg border border-red-200 dark:border-red-500/20">អសកម្ម</span>
                            </template>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block">ថ្ងៃចុះឈ្មោះ</span>
                        <p class="text-xs font-bold text-gray-700 dark:text-gray-300 mt-1"
                            x-text="currentUser.created_at ? new Date(currentUser.created_at).toLocaleDateString('km-KH', {year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric'}) : 'មិនមាន'"></p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block">ពេលចូលប្រើប្រាស់</span>
                        <p class="text-xs font-bold text-gray-700 dark:text-gray-300 mt-1"
                            x-text="currentUser.updated_at ? new Date(currentUser.updated_at).toLocaleDateString('km-KH', {year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric'}) : 'មិនមាន'"></p>
                    </div>
                </div>
            </div>

            <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 dark:border-gray-800 rounded-b-2xl">
                <button @click="showDetailModal = false"
                    class="flex-1 h-12 bg-white dark:bg-gray-800 border dark:border-gray-700 font-black text-[11px] uppercase tracking-widest rounded-xl hover:bg-gray-100 dark:hover:bg-gray-750 transition-all text-gray-600 dark:text-white">
                    បិទ
                </button>
                <button @click="showDetailModal = false; showEditModal = true"
                    class="flex-1 h-12 bg-blue-600 text-white font-black text-[11px] uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 hover:bg-blue-700 active:scale-95 transition-all">
                    កែសម្រួលគណនី
                </button>
            </div>
        </div>
    </div>
</div>