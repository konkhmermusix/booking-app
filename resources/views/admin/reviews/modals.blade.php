<div x-show="showEditModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ធ្វើបច្ចុប្បន្នភាពការវាយតម្លៃ</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Review Status</p>
                    </div>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="'{{ route('reviews.index') }}/' + currentReview.id" method="POST">
                @csrf
                @method('PUT')
                <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6">

                        <div class="flex items-center gap-4 p-4 bg-blue-50/50 dark:bg-blue-900/10 rounded-xl border border-blue-100/50 dark:border-blue-800/30">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold" x-text="currentReview.name.charAt(0)"></div>
                            <div>
                                <p class="text-sm font-bold dark:text-gray-200" x-text="currentReview.name"></p>
                                <p class="text-[11px] text-gray-400" x-text="'ប្រភេទបន្ទប់៖ ' + currentReview.room_type_name"></p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                ស្ថានភាព <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <select name="status"
                                    x-model="currentReview.status"
                                    class="w-full h-14 pl-7 pr-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium relative z-0">
                                    <option value="1">បង្ហាញ (Show)</option>
                                    <option value="0">លាក់ (Hide)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                ខ្លឹមសារមតិយោបល់ <span class="text-red-500">*</span>
                            </label>
                            <textarea name="comment"
                                x-model="currentReview.comment"
                                required
                                class="w-full h-42 p-6 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium leading-relaxed">
                            </textarea>
                        </div>

                        <div class="space-y-3">
                            <div class="bg-amber-50 dark:bg-amber-900/10 p-4 rounded-xl border border-amber-100 dark:border-amber-800/30">
                                <p class="text-[11px] text-amber-700 dark:text-amber-400 leading-relaxed">
                                    <i class="fas fa-info-circle mr-1"></i> ការផ្លាស់ប្តូរស្ថានភាពទៅ "លាក់" នឹងធ្វើឱ្យការវាយតម្លៃនេះលែងបង្ហាញនៅលើគេហទំព័រខាងមុខ។
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 dark:border-gray-800">
                    <button type="button" @click="showEditModal = false"
                        class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic">
                        បោះបង់
                    </button>
                    <button type="submit"
                        class="px-8 h-10 bg-amber-500 hover:bg-amber-600 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-amber-500/20 active:scale-95 transition-all">
                        ធ្វើបច្ចុប្បន្នភាព
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showDetailModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
            @click="showDetailModal = true"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"></div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ព័ត៌មានលម្អិតនៃការវាយតម្លៃ</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Review Detailed Information</p>
                </div>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 gap-y-6">

                    <div class="flex items-center gap-4 p-5 bg-blue-50/50 dark:bg-blue-900/10 rounded-xl border border-blue-100/50 dark:border-blue-800/30">
                        <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white text-xl font-bold" x-text="currentReview.name ? currentReview.name.charAt(0) : '?' "></div>
                        <div class="flex-1">
                            <p class="text-base font-black dark:text-gray-200" x-text="currentReview.name"></p>
                            <p class="text-xs text-gray-400 font-medium" x-text="'អ៊ីមែល៖ ' + currentReview.email"></p>
                            <p class="text-xs text-blue-500 font-bold mt-1" x-text="'ប្រភេទបន្ទប់៖ ' + currentReview.room_type_name"></p>
                            <div class="flex items-center gap-0.5 text-yellow-400 mt-1">
                                <template x-for="i in [1, 2, 3, 4, 5]" :key="i">
                                    <i class="fa-star text-xs" :class="i <= currentReview.rating ? 'fas' : 'far text-gray-300 dark:text-gray-600'"></i>
                                </template>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">ស្ថានភាព</p>
                            <template x-if="currentReview.status == 1">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full text-[10px] font-black uppercase">បង្ហាញ</span>
                            </template>
                            <template x-if="currentReview.status == 0">
                                <span class="px-3 py-1 bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 rounded-full text-[10px] font-black uppercase">លាក់</span>
                            </template>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                            ខ្លឹមសារមតិយោបល់
                        </label>
                        <div class="w-full min-h-[120px] p-6 rounded-xl bg-gray-50 dark:bg-gray-800 dark:text-white font-medium leading-relaxed shadow-inner border border-gray-100 dark:border-gray-700 whitespace-pre-line"
                            x-text="currentReview.comment">
                        </div>
                    </div>

                    <div class="flex justify-between items-center px-4 py-3 bg-gray-50/50 dark:bg-gray-800/30 rounded-xl border border-gray-100 dark:border-gray-700">
                        <span class="text-[11px] font-black uppercase text-gray-400 tracking-widest">កាលបរិច្ឆេទផ្ញើ</span>
                        <span class="text-sm font-bold dark:text-gray-300" x-text="currentReview.created_at_formatted"></span>
                    </div>
                </div>
            </div>

            <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 dark:border-gray-800">
                <button type="button" @click="showDetailModal = false; showEditModal = true"
                    class="px-10 h-10 bg-amber-800 dark:bg-white dark:text-gray-900 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl active:scale-95 transition-all">
                    កែប្រែ
                </button>
                <button type="button" @click="showDetailModal = false"
                    class="px-8 h-10 bg-amber-500 hover:bg-amber-600 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-amber-500/20 active:scale-95 transition-all">
                    យល់ព្រម
                </button>
            </div>
        </div>
    </div>
</div>
