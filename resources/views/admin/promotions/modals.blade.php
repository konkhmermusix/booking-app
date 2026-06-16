<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមប្រូម៉ូសិនថ្មី</h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest">Create New Special Offer</p>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('promotions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar"
                    x-data="{ 
                        originalPrice: '', 
                        discountedPrice: '',
                        imageUrl: null,
                        get discountPercentage() {
                            if (!this.originalPrice || !this.discountedPrice) return 0;
                            let orig = parseFloat(this.originalPrice);
                            let disc = parseFloat(this.discountedPrice);
                            if (orig <= 0 || disc >= orig) return 0;
                            return Math.round(((orig - disc) / orig) * 100);
                        },
                        get isInvalid() {
                            if (!this.originalPrice || !this.discountedPrice) return false;
                            return parseFloat(this.discountedPrice) >= parseFloat(this.originalPrice);
                        }
                     }">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើងប្រូម៉ូសិន <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required placeholder="ឧ. បញ្ចុះតម្លៃពិសេសរដូវក្តៅ"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
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
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">តម្លៃដើម ($) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">$</span>
                                <input type="number" step="0.01" min="0" name="original_price" x-model="originalPrice" required placeholder="0.00"
                                    class="w-full h-14 pl-10 pr-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center px-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 tracking-widest">តម្លៃបញ្ចុះ ($) <span class="text-red-500">*</span></label>
                                <span x-show="discountPercentage > 0" x-transition class="bg-green-100 dark:bg-green-950 text-green-600 dark:text-green-400 text-[10px] font-black px-2 py-0.5 rounded-md uppercase tracking-tight">
                                    ចុះអស់ <span x-text="discountPercentage"></span>%
                                </span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-green-600 font-bold text-sm">$</span>
                                <input type="number" step="0.01" min="0" name="discounted_price" x-model="discountedPrice" required placeholder="0.00"
                                    :class="isInvalid ? 'focus:ring-red-500 border border-red-500 dark:border-red-500 bg-red-50/50' : 'focus:ring-green-500 bg-gray-50 dark:bg-gray-800'"
                                    class="w-full h-14 pl-10 pr-6 rounded-2xl border-none dark:text-white outline-none transition-all font-semibold text-green-600">
                            </div>
                        </div>

                        <div x-show="isInvalid" x-transition class="col-span-1 md:col-span-2 flex items-center gap-1.5 text-red-500 text-[11px] font-bold px-2">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>ស្វ័យព្រមាន៖ តម្លៃបញ្ចុះតម្លៃ មិនអាចធំជាង ឬស្មើនឹងតម្លៃដើមបានឡើយ!</span>
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ថ្ងៃផុតកំណត់ <span class="text-red-500">*</span></label>
                            <input type="date" name="expiry_date" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">រូបភាពប្រូម៉ូសិន</label>
                            <div class="group relative flex flex-col items-center justify-center w-full min-h-[140px] rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/40 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-blue-500 transition-all overflow-hidden p-4">
                                <template x-if="!imageUrl">
                                    <div class="flex flex-col items-center justify-center text-center space-y-2 cursor-pointer w-full h-full py-4" @click="$refs.imageInputAdd.click()">
                                        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform"><i class="fa-solid fa-cloud-arrow-up text-lg"></i></div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300">ចុចទីនេះដើម្បីបញ្ចូលរូបភាព</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">គាំទ្រទម្រង់ PNG, JPG ឬ WEBP</p>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="imageUrl">
                                    <div class="relative w-full rounded-xl overflow-hidden border dark:border-gray-700 group/preview bg-gray-100 dark:bg-gray-900 flex items-center justify-center max-h-[180px]">
                                        <img :src="imageUrl" class="w-full h-full object-cover max-h-[180px]">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/preview:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                            <button type="button" @click="$refs.imageInputAdd.click()" class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-md text-white hover:bg-white/40 active:scale-95 transition-all flex items-center justify-center shadow"><i class="fa-solid fa-pen text-xs"></i></button>
                                            <button type="button" @click="imageUrl = null; $refs.imageInputAdd.value = ''" class="w-9 h-9 rounded-xl bg-red-500/80 text-white hover:bg-red-500 active:scale-95 transition-all flex items-center justify-center shadow"><i class="fa-solid fa-trash text-xs"></i></button>
                                        </div>
                                    </div>
                                </template>
                                <input type="file" name="image_path" x-ref="imageInputAdd" accept="image/*" class="hidden" @change="const file = $event.target.files[0]; if (file) { imageUrl = URL.createObjectURL(file) }">
                            </div>
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
                            <span class="text-[11px] font-black uppercase text-gray-400 tracking-widest">បង្ហាញឥឡូវនេះ (Active)</span>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-all">បោះបង់</button>
                    <button type="submit" class="px-8 h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/10 active:scale-95 transition-all">រក្សាទុកទិន្នន័យ</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div x-show="showEditModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែប្រែប្រូម៉ូសិន</h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest">Update Special Offer Details</p>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="'{{ route('promotions.index') }}/' + currentPromo.id" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar"
                    x-data="{ 
                        imageUrlEdit: null,
                        get discountPercentage() {
                            if (!currentPromo.original_price || !currentPromo.discounted_price) return 0;
                            let orig = parseFloat(currentPromo.original_price);
                            let disc = parseFloat(currentPromo.discounted_price);
                            if (orig <= 0 || disc >= orig) return 0;
                            return Math.round(((orig - disc) / orig) * 100);
                        },
                        get isInvalid() {
                            if (!currentPromo.original_price || !currentPromo.discounted_price) return false;
                            return parseFloat(currentPromo.discounted_price) >= parseFloat(currentPromo.original_price);
                        }
                     }">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើងប្រូម៉ូសិន <span class="text-red-500">*</span></label>
                            <input type="text" name="title" x-model="currentPromo.title" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">សម្រាប់ប្រភេទបន្ទប់ <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <i class="fa-solid fa-bed absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>
                                <select name="room_type_id" x-model="currentPromo.room_type_id" required class="w-full h-14 pl-12 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium">
                                    @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ស្លាកសញ្ញា (Tag)</label>
                            <input type="text" name="tag" x-model="currentPromo.tag"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">តម្លៃដើម ($) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">$</span>
                                <input type="number" step="0.01" min="0" name="original_price" x-model="currentPromo.original_price" required
                                    class="w-full h-14 pl-10 pr-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center px-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 tracking-widest">តម្លៃបញ្ចុះ ($) <span class="text-red-500">*</span></label>
                                <span x-show="discountPercentage > 0" x-transition class="bg-green-100 dark:bg-green-950 text-green-600 dark:text-green-400 text-[10px] font-black px-2 py-0.5 rounded-md uppercase tracking-tight">
                                    ចុះអស់ <span x-text="discountPercentage"></span>%
                                </span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-green-600 font-bold text-sm">$</span>
                                <input type="number" step="0.01" min="0" name="discounted_price" x-model="currentPromo.discounted_price" required
                                    :class="isInvalid ? 'focus:ring-red-500 border border-red-500 bg-red-50/50' : 'focus:ring-green-500 bg-gray-50 dark:bg-gray-800'"
                                    class="w-full h-14 pl-10 pr-6 rounded-2xl border-none dark:text-white outline-none transition-all font-semibold text-green-600">
                            </div>
                        </div>

                        <div x-show="isInvalid" x-transition class="col-span-1 md:col-span-2 flex items-center gap-1.5 text-red-500 text-[11px] font-bold px-2">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>ស្វ័យព្រមាន៖ តម្លៃបញ្ចុះតម្លៃ មិនអាចធំជាង ឬស្មើនឹងតម្លៃដើមបានឡើយ!</span>
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ថ្ងៃផុតកំណត់ <span class="text-red-500">*</span></label>
                            <input type="date" name="expiry_date" x-model="currentPromo.expiry_date" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">រូបភាពប្រូម៉ូសិន</label>
                            <div class="group relative flex flex-col items-center justify-center w-full min-h-[140px] rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/40 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-blue-500 transition-all overflow-hidden p-4">

                                <div class="relative w-full rounded-xl overflow-hidden border dark:border-gray-700 group/preview bg-gray-100 dark:bg-gray-900 flex items-center justify-center max-h-[180px]">
                                    <img :src="imageUrlEdit ? imageUrlEdit : (currentPromo.image_path ? '/storage/' + currentPromo.image_path : '/images/default-promo.png')" class="w-full h-full object-cover max-h-[180px]">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/preview:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                        <button type="button" @click="$refs.imageInputEdit.click()" class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-md text-white hover:bg-white/40 active:scale-95 transition-all flex items-center justify-center shadow"><i class="fa-solid fa-pen text-xs"></i></button>
                                    </div>
                                </div>
                                <input type="file" name="image_path" x-ref="imageInputEdit" accept="image/*" class="hidden" @change="const file = $event.target.files[0]; if (file) { imageUrlEdit = URL.createObjectURL(file) }">
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ការរៀបរាប់លម្អិត</label>
                            <textarea name="description" x-model="currentPromo.description" rows="3" placeholder="ព័ត៌មានបន្ថែមអំពីការបញ្ចុះតម្លៃ..."
                                class="w-full p-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium"></textarea>
                        </div>

                        <div class="col-span-1 md:col-span-2 flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" value="1" class="sr-only peer" :checked="currentPromo.status == 1">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                            <span class="text-[11px] font-black uppercase text-gray-400 tracking-widest">បង្ហាញឥឡូវនេះ (Active)</span>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false" class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-all">បោះបង់</button>
                    <button type="submit" class="px-8 h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/10 active:scale-95 transition-all">រក្សាទុកការផ្លាស់ប្តូរ</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div x-show="showDetailModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showDetailModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="relative h-52 w-full bg-gray-100 dark:bg-gray-800 border-b dark:border-gray-800 overflow-hidden">
                <img :src="currentPromo.image_path ? '/storage/' + currentPromo.image_path : '/images/default-promo.png'" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                <button @click="showDetailModal = false" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-black/40 backdrop-blur-md text-white hover:bg-black/60 text-xl flex items-center justify-center transition-transform hover:rotate-90">&times;</button>

                <div class="absolute bottom-5 left-7 right-7 text-white">
                    <span x-show="currentPromo.tag" x-text="currentPromo.tag" class="bg-blue-600 text-[9px] font-black uppercase px-2 py-0.5 rounded tracking-wider mb-1.5 inline-block"></span>
                    <h3 class="font-black text-xl uppercase tracking-tight line-clamp-1" x-text="currentPromo.title"></h3>
                </div>
            </div>

            <div class="p-7 space-y-6 max-h-[50vh] overflow-y-auto custom-scrollbar">

                <div class="grid grid-cols-3 gap-4 p-4 bg-gray-50 dark:bg-gray-800/40 rounded-2xl text-center border dark:border-gray-800">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">តម្លៃដើម</p>
                        <p class="text-base font-bold text-gray-500 line-through" x-text="'$' + parseFloat(currentPromo.original_price).toFixed(2)"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">តម្លៃពិសេស</p>
                        <p class="text-xl font-black text-green-600 dark:text-green-400" x-text="'$' + parseFloat(currentPromo.discounted_price).toFixed(2)"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">សន្សំបាន</p>
                        <p class="text-base font-black text-blue-600 dark:text-blue-400" x-text="Math.round(((currentPromo.original_price - currentPromo.discounted_price) / currentPromo.original_price) * 100) + '%'"></p>
                    </div>
                </div>

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center py-2 border-b dark:border-gray-800">
                        <span class="text-gray-400 font-medium">សម្រាប់ប្រភេទបន្ទប់៖</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200" x-text="currentPromo.room_type?.name || 'គ្រប់ប្រភេទបន្ទប់'"></span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b dark:border-gray-800">
                        <span class="text-gray-400 font-medium">ថ្ងៃផុតកំណត់៖</span>
                        <div class="flex items-center gap-1.5 font-bold text-red-500">
                            <i class="fa-regular fa-clock text-xs"></i>
                            <span x-text="currentPromo.expiry_date"></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b dark:border-gray-800">
                        <span class="text-gray-400 font-medium">ស្ថានភាព៖</span>
                        <template x-if="currentPromo.status == 1">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-green-100 text-green-700 dark:bg-green-950/50 dark:text-green-400">សកម្ម (Active)</span>
                        </template>
                        <template x-if="currentPromo.status != 1">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">មិនសកម្ម (Inactive)</span>
                        </template>
                    </div>

                    <div class="space-y-2 pt-2">
                        <span class="text-gray-400 font-medium block">ការរៀបរាប់លម្អិត៖</span>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed bg-gray-50/50 dark:bg-gray-800/20 p-4 rounded-xl border dark:border-gray-800 font-medium"
                            x-text="currentPromo.description || 'មិនមានការរៀបរាប់លម្អិតឡើយ។'"></p>
                    </div>
                </div>
            </div>

            <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center rounded-b-2xl border-t dark:border-gray-800">
                <button type="button" @click="showDetailModal = false" class="px-8 h-11 bg-gray-800 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition-all">
                    បិទផ្ទាំង
                </button>
            </div>
        </div>
    </div>
</div>