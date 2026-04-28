<div x-show="showAddModal" x-data="roomTypeAddHandler()" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">

                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមប្រភេទបន្ទប់ថ្មី</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Type, Images & Facilities</p>
                    </div>
                </div>
                <button @click="showAddModal = false; previews = []" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('room_types.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">សណ្ឋាគារ <span class="text-red-500">*</span></label>
                            <select name="hotel_id" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium relative z-0">
                                <option value="" disabled selected>ជ្រើសរើសសណ្ឋាគារ</option>
                                @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ឈ្មោះប្រភេទបន្ទប់ <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="បន្ទប់គ្រែមួយ" class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">តម្លៃគោល ($) <span class="text-red-500">*</span></label>
                            <input type="number" name="base_price" step="0.01" required placeholder="0.00" class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ភ្ញៀវអតិបរមា <span class="text-red-500">*</span></label>
                            <input type="number" name="max_guests" required placeholder="2" class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>
                    </div>

                    <div class="space-y-2 col-span-full">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ពិពណ៌នា (Description)</label>
                        <textarea name="description" rows="3" placeholder="ព័ត៌មានលម្អិតពីបន្ទប់..."
                            class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:bg-white outline-none transition-all"></textarea>
                    </div>


                    <div class="space-y-4">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">គ្រឿងបរិក្ខារក្នុងបន្ទប់ (Facilities)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @forelse($facilities->where('is_active', 1) as $facility)
                            <label class="relative flex items-center p-3 rounded-2xl bg-gray-50 dark:bg-gray-800 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all group">
                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" class="peer sr-only">

                                <div class="w-5 h-5 rounded-lg border-2 border-gray-300 dark:border-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center transition-all shadow-sm">
                                    <i class="fa-solid fa-check text-[10px] text-white opacity-0 peer-checked:opacity-100"></i>
                                </div>

                                <span class="ml-3 text-[11px] font-black text-gray-500 dark:text-gray-400 peer-checked:text-blue-600 dark:peer-checked:text-blue-400 uppercase tracking-tight">
                                    {{ $facility->name }}
                                </span>
                            </label>
                            @empty
                            <div class="col-span-full">
                                <a href="{{ route('facilities.index') }}"
                                    class="flex items-center justify-center gap-2 p-4 rounded-2xl border-2 border-dashed border-gray-100 dark:border-gray-800 text-gray-400 hover:text-blue-500 hover:border-blue-200 transition-all group">
                                    <i class="fa-solid fa-plus-circle text-lg group-hover:scale-110 transition-transform"></i>
                                    <span class="text-xs font-black uppercase tracking-widest">សូមបញ្ចូលគ្រឿងបរិក្ខារជាមុនសិន</span>
                                </a>
                            </div>
                            @endforelse
                        </div>
                    </div>



                    <div class="space-y-4">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">រូបភាពបន្ទប់ (Gallery)</label>

                        <div class="relative group">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer transition-all border-blue-500/30">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa-solid fa-images text-2xl text-blue-500 mb-2"></i>
                                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest text-center px-4">
                                        ចុចដើម្បីបញ្ចូលរូបភាពច្រើន​សន្លឹក <br>
                                        <span class="text-blue-400">(JPG, PNG, WEBP)</span>
                                    </p>
                                </div>
                                <input type="file" name="images[]" multiple class="hidden" accept="image/*" @change="handleFileSelect" />
                            </label>
                        </div>


                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 mt-4 max-h-60 overflow-y-auto p-1 custom-scrollbar">

                            <template x-for="(src, index) in previews" :key="index">
                                <div class="relative aspect-square group/item">
                                    <img :src="src" class="w-full h-full object-cover rounded-lg border border-blue-500/30 shadow-sm">

                                    <button type="button" @click="removeFile(index)"
                                        class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[8px] shadow-lg opacity-100 sm:opacity-0 group-hover/item:opacity-100 transition-opacity z-10">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </template>

                            <template x-if="previews.length > 0">
                                <div @click="clearAll()" class="aspect-square flex flex-col items-center justify-center border-2 border-dashed border-red-200 hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg cursor-pointer transition-all group">
                                    <i class="fa-solid fa-trash-can text-red-400 group-hover:text-red-600 text-xs"></i>
                                    <span class="text-[8px] font-black text-red-400 group-hover:text-red-600 uppercase mt-1">Clear</span>
                                </div>
                            </template>

                        </div>
                    </div>

                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false; previews = []" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic">បោះបង់</button>
                    <button type="submit" class="px-8 h-10 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-500/20 active:scale-95 transition-all">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showEditModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center border-b dark:border-gray-800">
                <div class="flex items-center gap-4">

                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែសម្រួលប្រភេទបន្ទប់</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Type, Images & Facilities</p>
                    </div>
                </div>
                <button @click="showEditModal = false; previews = []" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/room_types') }}/${currentRoomType.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">សណ្ឋាគារ</label>
                            <select name="hotel_id" x-model="currentRoomType.hotel_id" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium relative z-0">
                                @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ឈ្មោះប្រភេទបន្ទប់</label>
                            <input type="text" name="name" x-model="currentRoomType.name" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">តម្លៃគោល ($)</label>
                            <input type="number" name="base_price" step="0.01" x-model="currentRoomType.base_price" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ភ្ញៀវអតិបរមា <span class="text-red-500">*</span></label>
                            <input type="number" name="max_guests" x-model="currentRoomType.max_guests" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="space-y-2 col-span-full">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ពិពណ៌នា (Description)</label>
                            <textarea name="description" rows="3" x-model="currentRoomType.description" placeholder="ព័ត៌មានលម្អិតពីបន្ទប់..."
                                class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:bg-white outline-none transition-all"></textarea>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">គ្រឿងបរិក្ខារក្នុងបន្ទប់</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($facilities->where('is_active', 1) as $facility)
                            <label class="relative flex items-center p-3 rounded-2xl bg-gray-50 dark:bg-gray-800 cursor-pointer">
                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                    x-model="selectedFacilities"
                                    class="peer sr-only">
                                <div class="w-5 h-5 rounded-lg border-2 border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center transition-all shadow-sm">
                                    <i class="fa-solid fa-check text-[10px] text-white opacity-0 peer-checked:opacity-100"></i>
                                </div>
                                <span class="ml-3 text-[11px] font-black text-gray-500 dark:text-gray-400 peer-checked:text-blue-600 uppercase tracking-tight">{{ $facility->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">រូបភាពបច្ចុប្បន្ន</label>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="image in currentRoomType.images" :key="image.id">

                                <div class="relative w-20 h-20 group" x-data="{ confirming: false }">
                                    <img :src="`/storage/${image.image_path}`" class="w-full h-full object-cover rounded-xl">

                                    <button type="button"
                                        @click.stop="if(!confirming) { confirming = true; setTimeout(() => confirming = false, 3000) } else { deleteExistingImage(image.id) }"
                                        :class="confirming ? 'bg-yellow-500 w-auto px-2 opacity-100' : 'bg-red-500 w-5 opacity-0'"
                                        class="absolute -top-1 -right-1 text-white rounded-full h-5 flex items-center justify-center text-[10px] group-hover:opacity-100 transition-all duration-300">

                                        <span x-show="!confirming"><i class="fa-solid fa-times"></i></span>
                                        <span x-show="confirming" class="font-bold uppercase tracking-tighter">លុប?</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">បន្ថែមរូបភាពថ្មី (Optional)</label>
                        <div class="relative group">
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-800/50 cursor-pointer hover:bg-gray-100 transition-all">
                                <i class="fa-solid fa-plus text-gray-400 mb-1"></i>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest text-center">ជ្រើសរើសរូបភាពបន្ថែម</span>
                                <input type="file" name="images[]" multiple class="hidden" accept="image/*" @change="handleFileSelect" />
                            </label>
                        </div>
                        <div class="grid grid-cols-4 gap-3" x-show="previews.length > 0">
                            <template x-for="(src, index) in previews" :key="index">
                                <img :src="src" class="w-full h-16 object-cover rounded-xl border-2 border-blue-500">
                            </template>
                        </div>
                    </div>

                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false" class="px-8 h-10 font-black text-sm uppercase text-gray-400 hover:text-red-500 transition-all italic">បោះបង់</button>
                    <button type="submit" class="px-8 h-10 bg-orange-500 hover:bg-orange-600 text-white font-black text-sm uppercase tracking-widest rounded-2xl shadow-xl shadow-orange-500/20 active:scale-95 transition-all">ធ្វើបច្ចុប្បន្នភាព</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div x-show="showDetailModal" class="fixed inset-0 z-60 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showDetailModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center border-b dark:border-gray-800">
                <div class="flex items-center gap-4">

                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ព័ត៌មានលម្អិត</h3>
                    </div>
                </div>
                <button @click="showDetailModal = false; previews = []" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <div class="max-h-[80vh] overflow-y-auto custom-scrollbar">
                <div class="h-56 bg-gray-200 dark:bg-gray-800 relative overflow-hidden">
                    <template x-if="currentRoomType.images && currentRoomType.images.length > 0">
                        <img :src="currentRoomType.images[0].url" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!currentRoomType.images || currentRoomType.images.length == 0">
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-4xl"></i>
                        </div>
                    </template>
                </div>

                <div class="p-8 space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center rounded-2xl text-blue-600 text-2xl">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div>
                            <h4 class="text-2xl font-black dark:text-white uppercase" x-text="currentRoomType.name"></h4>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest" x-text="currentRoomType.hotel?.name"></p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-2">ពិពណ៌នា</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed"
                            x-text="currentRoomType.description && currentRoomType.description !== 'null' ? currentRoomType.description : 'មិនមានការពិពណ៌នា'"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-500/5 rounded-2xl border border-emerald-100 dark:border-emerald-500/20">
                            <p class="text-[10px] text-emerald-600/60 uppercase font-black tracking-wider mb-1">តម្លៃគោល</p>
                            <p class="text-xl font-black text-emerald-600" x-text="'$' + parseFloat(currentRoomType.base_price || 0).toFixed(2)"></p>
                        </div>
                        <div class="p-4 bg-blue-50 dark:bg-blue-500/5 rounded-2xl border border-blue-100 dark:border-blue-500/20">
                            <p class="text-[10px] text-blue-600/60 uppercase font-black tracking-wider mb-1">ចំនួនភ្ញៀវ</p>
                            <p class="text-xl font-black dark:text-gray-200" x-text="currentRoomType.max_guests + ' នាក់'"></p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-3">គ្រឿងបរិក្ខារ</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="facility in currentRoomType.facilities" :key="facility.id">
                                <span class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-lg text-[11px] font-bold uppercase" x-text="facility.name"></span>
                            </template>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-3">រូបភាពទាំងអស់ (ចុចដើម្បីពង្រីក)</p>
                        <div class="flex gap-3 overflow-x-auto pb-4 custom-scrollbar">
                            <template x-for="img in currentRoomType.images" :key="img.id">
                                <a :href="img.url || '/storage/' + img.image_path" class="spotlight flex-shrink-0">
                                    <img :src="img.url || '/storage/' + img.image_path"
                                        class="w-20 h-20 rounded-xl object-cover border-2 dark:border-gray-700 hover:border-blue-500 transition-all cursor-zoom-in">
                                </a>
                            </template>
                        </div>
                    </div>

                    <button @click="showDetailModal = false" class="w-full py-4 bg-gray-900 dark:bg-white dark:text-gray-900 text-white rounded-2xl font-black uppercase tracking-widest hover:opacity-90 transition-all text-sm shadow-xl">បិទត្រឡប់ទៅវិញ</button>
                </div>
            </div>
        </div>
    </div>
</div>