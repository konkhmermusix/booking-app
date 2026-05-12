<div x-show="showAddAbout" class="fixed inset-0 z-60 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddAbout = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative dark:border-gray-800 overflow-hidden">
            <div class="px-4 py-3 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមមាតិកាថ្មី</h3>
                <button @click="showAddAbout = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('about.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">Key (សម្គាល់)</label>
                            <input type="text" name="key" placeholder="ឧទាហរណ៍៖ vision" required
                                class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">ស្ថានភាព</label>
                            <select name="status" class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                <option value="1">បង្ហាញ</option>
                                <option value="0">មិនបង្ហាញ</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ចំណងជើង (KH)</label>
                        <input type="text" name="title_kh" required
                            class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ខ្លឹមសារ (KH)</label>
                        <textarea name="content_kh" rows="4" required
                            class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">រូបភាព</label>
                        <input type="file" name="image"
                            class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2">
                    <button type="button" @click="showAddAbout = false" class="px-4 py-2 text-gray-500 font-medium">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold shadow-lg shadow-blue-500/20">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showEditAbout" class="fixed inset-0 z-60 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showEditAbout = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative dark:border-gray-800 overflow-hidden">
            <div class="px-4 py-3 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight text-amber-500">កែប្រែមាតិកា</h3>
                <button @click="showEditAbout = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/about/update') }}/${currentAbout.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">Key</label>
                            <input type="text" name="key" x-model="currentAbout.key" required
                                class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">ស្ថានភាព</label>
                            <select name="status" x-model="currentAbout.status" class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all">
                                <option value="1">បង្ហាញ</option>
                                <option value="0">មិនបង្ហាញ</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ចំណងជើង (KH)</label>
                        <input type="text" name="title_kh" x-model="currentAbout.title_kh" required
                            class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ខ្លឹមសារ (KH)</label>
                        <textarea name="content_kh" x-model="currentAbout.content_kh" rows="4" required
                            class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ប្តូររូបភាព (បើមាន)</label>
                        <input type="file" name="image" class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2">
                    <button type="button" @click="showEditAbout = false" class="px-4 py-2 text-gray-500">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-amber-500 text-white rounded-xl font-bold shadow-lg shadow-amber-500/20">ធ្វើបច្ចុប្បន្នភាព</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div x-show="showAddHistory" class="fixed inset-0 z-60 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddHistory = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative dark:border-gray-800 overflow-hidden">
            <div class="px-4 py-3 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមប្រវត្តិថ្មី</h3>
                <button @click="showAddHistory = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('history.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">ឆ្នាំ</label>
                            <input type="text" name="year" placeholder="ឧទាហរណ៍៖ ២០២៤" required
                                class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">អាទិភាព (លំដាប់)</label>
                            <input type="number" name="order_priority" value="1" required
                                class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ចំណងជើង (KH)</label>
                        <input type="text" name="title_kh" required
                            class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ការពិពណ៌នា (KH)</label>
                        <textarea name="description_kh" rows="4" required
                            class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                    </div>
                    <input type="hidden" name="status" value="1">
                </div>

                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2">
                    <button type="button" @click="showAddHistory = false" class="px-4 py-2 text-gray-500 font-medium">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold shadow-lg shadow-indigo-500/20">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showEditHistory" class="fixed inset-0 z-60 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showEditHistory = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative dark:border-gray-800 overflow-hidden">
            <div class="px-4 py-3 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight text-indigo-500">កែប្រែប្រវត្តិ</h3>
                <button @click="showEditHistory = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/history/update') }}/${currentHistory.id}`" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">ឆ្នាំ</label>
                            <input type="text" name="year" x-model="currentHistory.year" required
                                class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">អាទិភាព</label>
                            <input type="number" name="order_priority" x-model="currentHistory.order_priority" required
                                class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ចំណងជើង (KH)</label>
                        <input type="text" name="title_kh" x-model="currentHistory.title_kh" required
                            class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ការពិពណ៌នា (KH)</label>
                        <textarea name="description_kh" x-model="currentHistory.description_kh" rows="4" required
                            class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2">
                    <button type="button" @click="showEditHistory = false" class="px-4 py-2 text-gray-500 font-medium">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold">ធ្វើបច្ចុប្បន្នភាព</button>
                </div>
            </form>
        </div>
    </div>
</div>