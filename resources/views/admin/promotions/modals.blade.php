<div x-show="showAddModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
            @click="showAddModal = true"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-[1.5rem] shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-5 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-50 dark:bg-orange-500/10 rounded-2xl flex items-center justify-center text-orange-600 dark:text-orange-400">
                        <i class="fa-solid fa-tags text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមប្រូម៉ូសិនថ្មី</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Create New Special Offer</p>
                    </div>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('promotions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើងប្រូម៉ូសិន <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required placeholder="ឧ. បញ្ចុះតម្លៃពិសេសរដូវក្តៅ"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">សម្រាប់ប្រភេទបន្ទប់ <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <i class="fa-solid fa-bed absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 z-10"></i>
                                <select name="room_type_id" required class="w-full h-14 pl-12 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium">
                                    <option value="" disabled selected>ជ្រើសរើសប្រភេទបន្ទប់...</option>
                                    @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ស្លាកសញ្ញា (Tag)</label>
                            <input type="text" name="tag" placeholder="ឧ. Summer Sale"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">តម្លៃដើម ($) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="original_price" required placeholder="0.00"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">តម្លៃបញ្ចុះ ($) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="discounted_price" required placeholder="0.00"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-100 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 outline-none transition-all font-bold text-green-600">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ថ្ងៃផុតកំណត់ <span class="text-red-500">*</span></label>
                            <input type="date" name="expiry_date" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">រូបភាពប្រូម៉ូសិន</label>
                            <input type="file" name="image_path" accept="image/*"
                                class="w-full h-14 px-6 py-3 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm">
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ការរៀបរាប់លម្អិត</label>
                            <textarea name="description" rows="3" placeholder="ព័ត៌មានបន្ថែមអំពីការបញ្ចុះតម្លៃ..."
                                class="w-full p-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium"></textarea>
                        </div>

                        <div class="col-span-1 md:col-span-2 flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                            <span class="text-[11px] font-black uppercase text-gray-400 tracking-widest">បើកដំណើរការភ្លាមៗ (Active)</span>
                        </div>

                    </div>
                </div>

                <div class="px-7 py-5 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 rounded-b-[1.5rem] border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false"
                        class="px-8 h-12 font-black text-sm uppercase tracking-widest text-gray-400 hover:text-red-500 transition-all italic">
                        បោះបង់
                    </button>
                    <button type="submit"
                        class="px-10 h-12 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-500/20 active:scale-95 transition-all">
                        រក្សាទុកប្រូម៉ូសិន
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-[1.5rem] shadow-2xl w-full max-w-2xl relative overflow-hidden">
            <div class="px-7 py-5 border-b dark:border-gray-800 flex justify-between items-center">
                <h3 class="font-black text-xl dark:text-white uppercase">កែប្រែប្រូម៉ូសិន</h3>
                <button @click="showEditModal = false" class="text-gray-400 text-3xl">&times;</button>
            </div>

            <form :action="'{{ route('promotions.index') }}/' + currentPromo.id" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2 space-y-2">
                        <label class="text-[11px] font-black uppercase text-gray-400">ចំណងជើង</label>
                        <input type="text" name="title" x-model="currentPromo.title" required class="w-full h-12 px-5 rounded-xl bg-gray-50 dark:bg-gray-800 dark:text-white border-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black uppercase text-gray-400">តម្លៃដើម ($)</label>
                        <input type="number" name="original_price" x-model="currentPromo.original_price" required class="w-full h-12 px-5 rounded-xl bg-gray-50 dark:bg-gray-800 dark:text-white border-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black uppercase text-gray-400">តម្លៃបញ្ចុះ ($)</label>
                        <input type="number" name="discounted_price" x-model="currentPromo.discounted_price" required class="w-full h-12 px-5 rounded-xl bg-gray-100 dark:bg-gray-700 dark:text-green-500 border-none font-bold">
                    </div>

                    <div class="col-span-2 flex justify-end gap-3 mt-4">
                        <button type="button" @click="showEditModal = false" class="px-6 py-2 text-gray-400 font-bold uppercase text-xs italic">បោះបង់</button>
                        <button type="submit" class="px-10 py-3 bg-blue-600 text-white rounded-xl font-black uppercase text-xs shadow-lg shadow-blue-500/30">រក្សាទុកការផ្លាស់ប្តូរ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>