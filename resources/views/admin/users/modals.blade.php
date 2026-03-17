<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមអ្នកប្រើប្រាស់ថ្មី</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">ឈ្មោះពេញ</label>
                            <input type="text" name="name" required class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500/20 outline-none border-gray-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">អ៊ីមែល</label>
                            <input type="email" name="email" required class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">លេខទូរស័ព្ទ</label>
                            <input type="text" name="phone" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">តួនាទី (Role)</label>
                            <select name="role" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                                <option value="customer">Customer</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">ស្ថានភាព (Status)</label>
                            <select name="status" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                                <option value="active">សកម្ម (Active)</option>
                                <option value="pending" selected>រង់ចាំ (Pending)</option>
                                <option value="inactive">អសកម្ម (Inactive)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">ពាក្យសម្ងាត់</label>
                            <input type="password" name="password" required class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">បញ្ជាក់ពាក្យសម្ងាត់</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 text-gray-500">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div x-show="showEditModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showEditModal = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែប្រែទិន្នន័យ៖ <span x-text="currentUser.name" class="text-blue-500"></span></h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/users') }}/${currentUser.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">ឈ្មោះពេញ</label>
                            <input type="text" name="name" x-model="currentUser.name" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">អ៊ីមែល</label>
                            <input type="email" name="email" x-model="currentUser.email" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">លេខទូរស័ព្ទ</label>
                            <input type="text" name="phone" x-model="currentUser.phone" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">តួនាទី (Role)</label>
                            <select name="role" x-model="currentUser.role" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">ស្ថានភាព (Status)</label>
                            <select name="status" x-model="currentUser.status" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-500/5 rounded-xl border border-blue-100 dark:border-blue-500/10">
                        <p class="text-xs text-blue-600 dark:text-blue-400">ទុកពាក្យសម្ងាត់នៅទំនេរ ប្រសិនបើមិនចង់ផ្លាស់ប្តូរវា។</p>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 text-gray-500">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/20">ធ្វើបច្ចុប្បន្នភាព</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showDetailModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showDetailModal = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ព័ត៌មានលម្អិតអំពី៖ <span x-text="currentUser.name" class="text-blue-500"></span></h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <div class="bg-white dark:bg-gray-900 shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden transform transition-all">
                <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700"></div>

                <div class="px-8 pb-8">
                    <div class="relative -mt-12 mb-6 flex justify-between items-end">
                        <img :src="currentUser.avatar ? `{{ asset('storage') }}/${currentUser.avatar}` : `https://ui-avatars.com/api/?name=${currentUser.name}&background=random&size=128`"
                            class="w-24 h-24 rounded-2xl border-4 border-white dark:border-gray-900 shadow-lg object-cover bg-white">

                        <span :class="{
                        'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400': currentUser.status === 'active',
                        'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400': currentUser.status === 'pending',
                        'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400': currentUser.status === 'inactive'
                    }" class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-2">
                            <span x-text="currentUser.status"></span>
                        </span>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-bold dark:text-white" x-text="currentUser.name"></h2>
                        <p class="text-gray-500 dark:text-gray-400" x-text="currentUser.email"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">លេខទូរស័ព្ទ</span>
                            <p class="font-medium dark:text-gray-200" x-text="currentUser.phone || 'មិនមាន'"></p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">តួនាទី</span>
                            <p class="font-medium dark:text-gray-200 uppercase" x-text="currentUser.role"></p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">ថ្ងៃចុះឈ្មោះ</span>
                            <p class="font-medium dark:text-gray-200" x-text="new Date(currentUser.created_at).toLocaleDateString('km-KH')"></p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">កែប្រែចុងក្រោយ</span>
                            <p class="font-medium dark:text-gray-200" x-text="new Date(currentUser.updated_at).toLocaleDateString('km-KH')"></p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t dark:border-gray-800 flex justify-end">
                        <button @click="showDetailModal = false"
                            class="px-6 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all font-medium">
                            បិទត្រឡប់ទៅវិញ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>