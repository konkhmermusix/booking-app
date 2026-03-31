<div x-show="showAddModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-[1.5rem] shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមប្រវត្តិថ្មី</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Create New History Entry</p>
                    </div>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('abouts.store') }}" method="POST">
                @csrf
                <div class="p-10 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ឆ្នាំ <span class="text-red-500">*</span></label>
                            <input type="text" name="year" required placeholder="ឧ. ២០២៤"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើង <span class="text-red-500">*</span></label>
                            <input type="text" name="title_kh" required placeholder="បញ្ចូលចំណងជើង..."
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ពិពណ៌នា <span class="text-red-500">*</span></label>
                            <textarea name="description_kh" rows="4" required placeholder="រៀបរាប់លម្អិត..."
                                class="w-full p-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium"></textarea>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 mb-4 ml-2 tracking-widest">ស្ថានភាព</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative group cursor-pointer">
                                    <input type="radio" name="status" value="1" class="peer sr-only" checked>
                                    <div class="flex flex-col items-center justify-center py-2 rounded-[1.0rem] border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-500/10 peer-checked:text-green-600 transition-all">
                                        <span class="text-[11px] font-black uppercase tracking-widest">សកម្ម</span>
                                    </div>
                                </label>
                                <label class="relative group cursor-pointer">
                                    <input type="radio" name="status" value="0" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center py-2 rounded-[1.0rem] border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-500/10 peer-checked:text-red-600 transition-all">
                                        <span class="text-[11px] font-black uppercase tracking-widest">អសកម្ម</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 rounded-b-[1.5rem] border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic">បោះបង់</button>
                    <button type="submit" class="px-8 h-10 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-500/20 active:scale-95 transition-all">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-[1.5rem] shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 dark:bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="fa-solid fa-pen-to-square text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែសម្រួលប្រវត្តិ</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update History Details</p>
                    </div>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/abouts') }}/${currentHistory.id}`" method="POST">
                @csrf @method('PUT')
                <div class="p-10 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ឆ្នាំ</label>
                            <input type="text" name="year" x-model="currentHistory.year" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើង</label>
                            <input type="text" name="title_kh" x-model="currentHistory.title_kh" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ពិពណ៌នា</label>
                            <textarea name="description_kh" x-model="currentHistory.description_kh" rows="4" required
                                class="w-full p-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium"></textarea>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 mb-4 ml-2 tracking-widest">ស្ថានភាព</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative group cursor-pointer">
                                    <input type="radio" name="status" value="1" x-model="currentHistory.status" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center py-2 rounded-[1.0rem] border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-500/10 peer-checked:text-green-600 transition-all">
                                        <span class="text-[11px] font-black uppercase tracking-widest">សកម្ម</span>
                                    </div>
                                </label>
                                <label class="relative group cursor-pointer">
                                    <input type="radio" name="status" value="0" x-model="currentHistory.status" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center py-2 rounded-[1.0rem] border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-500/10 peer-checked:text-red-600 transition-all">
                                        <span class="text-[11px] font-black uppercase tracking-widest">អសកម្ម</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 rounded-b-[1.5rem] border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic">បោះបង់</button>
                    <button type="submit" class="px-8 h-10 bg-amber-500 hover:bg-amber-600 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-amber-500/20 active:scale-95 transition-all">ធ្វើបច្ចុប្បន្នភាព</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showDetailModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-[1.5rem] shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700 relative">
                <div class="absolute inset-0 opacity-30 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <button @click="showDetailModal = false" class="absolute top-5 right-5 w-10 h-10 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="absolute -bottom-10 left-10">
                    <div class="w-20 h-20 bg-white dark:bg-gray-900 rounded-[2rem] shadow-xl flex items-center justify-center text-blue-600 border-4 border-white dark:border-gray-900">
                        <i class="fa-solid fa-clock-rotate-left text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="pt-14 p-10 space-y-8">
                <div>
                    <h3 class="text-2xl font-black dark:text-white uppercase tracking-tight" x-text="'ឆ្នាំ ' + currentHistory.year"></h3>
                    <p class="text-sm text-blue-600 font-bold uppercase tracking-widest mt-1" x-text="currentHistory.title_kh"></p>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">ពិពណ៌នាប្រវត្តិ</span>
                        <p class="text-sm leading-relaxed dark:text-gray-300" x-text="currentHistory.description_kh"></p>
                    </div>

                    <div class="pt-4 border-t dark:border-gray-800 flex justify-between items-center">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">ស្ថានភាព</span>
                        <template x-if="currentHistory.status == 1">
                            <span class="px-3 py-1 bg-green-100 dark:bg-green-500/10 text-green-600 text-[10px] font-black uppercase rounded-lg border border-green-200 dark:border-green-500/20">សកម្ម</span>
                        </template>
                        <template x-if="currentHistory.status == 0">
                            <span class="px-3 py-1 bg-red-100 dark:bg-red-500/10 text-red-600 text-[10px] font-black uppercase rounded-lg border border-red-200 dark:border-red-500/20">អសកម្ម</span>
                        </template>
                    </div>
                </div>
            </div>

            <div class="px-10 py-8 bg-gray-50 dark:bg-gray-800/50 flex gap-3">
                <button @click="showDetailModal = false" class="flex-1 h-12 bg-white dark:bg-gray-800 border dark:border-gray-700 font-black text-[11px] uppercase tracking-widest rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all dark:text-white">បិទវិញ</button>
                <button @click="showDetailModal = false; showEditModal = true" class="flex-1 h-12 bg-blue-600 text-white font-black text-[11px] uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 hover:bg-blue-700 active:scale-95 transition-all">កែសម្រួល</button>
            </div>
        </div>
    </div>
</div>