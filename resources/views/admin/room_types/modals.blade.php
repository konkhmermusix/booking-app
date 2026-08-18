<style>
    .ck-editor__editable {
        min-height: 250px;
    }

    .dark .ck-editor__editable {
        background-color: #1f2937 !important;
        color: #ffffff !important;
    }

    .dark .ck-toolbar {
        background-color: #111827 !important;
        border-color: #374151 !important;
    }

    .dark .ck-button {
        color: #ffffff !important;
    }

    .dark .ck-button:hover {
        background-color: #374151 !important;
    }

    .ck-content {
        color: #000;
    }

    .ck-editor {
        width: 100%;
    }

    .ck-content h1 {
        font-size: 2em;
        font-weight: bold;
        margin-top: 0.67em;
        margin-bottom: 0.67em;
    }

    .ck-content h2 {
        font-size: 1.5em;
        font-weight: bold;
        margin-top: 0.83em;
        margin-bottom: 0.83em;
    }

    .ck-content h3 {
        font-size: 1.17em;
        font-weight: bold;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    .ck-content h4 {
        font-size: 1em;
        font-weight: bold;
        margin-top: 1.33em;
        margin-bottom: 1.33em;
    }

    .ck-content p {
        margin-top: 0;
        margin-bottom: 1rem;
    }

    .ck-content ul {
        list-style-type: disc;
        padding-left: 40px;
    }

    .ck-content ol {
        list-style-type: decimal;
        padding-left: 40px;
    }
</style>

<div x-show="showAddModal" x-data="{}" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false; previews = []" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមប្រភេទបន្ទប់ថ្មី</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Type, Images & Facilities</p>
                </div>
                <button @click="showAddModal = false; previews = []" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('room_types.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    @if($errors->any())
                    <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 text-xs font-semibold">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>សូមពិនិត្យមើលព័ត៌មានដែលបានបញ្ចូល៖</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">សណ្ឋាគារ <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="hotel_id" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium">
                                    <option value="" disabled selected>ជ្រើសរើសសណ្ឋាគារ</option>
                                    @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ឈ្មោះប្រភេទបន្ទប់ <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="បន្ទប់គ្រែមួយ" class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ប្រភេទសេវាកម្ម <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="category" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-bold">
                                    <option value="stay" selected>បន្ទប់ស្នាក់នៅ</option>
                                    <option value="meeting">សាលប្រជុំ</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
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

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">ពិពណ៌នា (Description)</label>
                        <textarea id="add_editor" name="description" class="w-full"></textarea>
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
                                <a href="{{ route('facilities.index') }}" class="flex items-center justify-center gap-2 p-4 rounded-2xl border-2 border-dashed border-gray-100 dark:border-gray-800 text-gray-400 hover:text-blue-500 hover:border-blue-200 transition-all group">
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
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-2xl bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer transition-all border-blue-500/30">
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
                                    <button type="button" @click="removeFile(index)" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[8px] shadow-lg opacity-100 sm:opacity-0 group-hover/item:opacity-100 transition-opacity z-10">
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

<div x-show="showEditModal"
    x-init="$watch('showEditModal', value => { 
        if(value && window.editEditorInstance) { 
            window.editEditorInstance.setData(currentRoomType.description || ''); 
        } 
     })"
    class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែសម្រួលប្រភេទបន្ទប់</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Type, Images & Facilities</p>
                </div>
                <button @click="showEditModal = false; previews = []" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/room_types') }}/${currentRoomType.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">សណ្ឋាគារ <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="hotel_id" x-model="currentRoomType.hotel_id" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium">
                                    @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ឈ្មោះប្រភេទបន្ទប់ <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="currentRoomType.name" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ប្រភេទសេវាកម្ម <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="category" x-model="currentRoomType.category" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-bold">
                                    <option value="stay">បន្ទប់ស្នាក់នៅ</option>
                                    <option value="meeting">សាលប្រជុំ</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">តម្លៃគោល ($) <span class="text-red-500">*</span></label>
                            <input type="number" name="base_price" step="0.01" x-model="currentRoomType.base_price" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ភ្ញៀវអតិបរមា <span class="text-red-500">*</span></label>
                            <input type="number" name="max_guests" x-model="currentRoomType.max_guests" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>
                    </div>

                    <div class="space-y-2 col-span-full">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ពិពណ៌នា (Description)</label>
                        <textarea id="edit_editor" name="description" class="w-full"></textarea>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">គ្រឿងបរិក្ខារក្នុងបន្ទប់</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($facilities->where('is_active', 1) as $facility)
                            <label class="relative flex items-center p-3 rounded-2xl bg-gray-50 dark:bg-gray-800 cursor-pointer">
                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" x-model="selectedFacilities" class="peer sr-only">
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
                                    <img :src="image.url ? image.url : `/storage/${image.image_path}`" class="w-full h-full object-cover rounded-xl">
                                    <button type="button" @click.stop="if(!confirming) { confirming = true; setTimeout(() => confirming = false, 3000) } else { deleteExistingImage(image.id) }" :class="confirming ? 'bg-yellow-500 w-auto px-2 opacity-100' : 'bg-red-500 w-5 opacity-0'" class="absolute -top-1 -right-1 text-white rounded-full h-5 flex items-center justify-center text-[10px] group-hover:opacity-100 transition-all duration-300">
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


<div x-show="showDetailModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
            @click="showDetailModal = false"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center border-b dark:border-gray-800 bg-white dark:bg-gray-900">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ព័ត៌មានលម្អិតពីប្រភេទបន្ទប់</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Room Type Specifications</p>
                </div>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <div class="max-h-[75vh] overflow-y-auto custom-scrollbar">

                <div class="h-64 bg-gray-100 dark:bg-gray-800 relative overflow-hidden group">
                    <template x-if="currentRoomType.images && currentRoomType.images.length > 0">
                        <img :src="currentRoomType.images[0].url ? currentRoomType.images[0].url : `/storage/${currentRoomType.images[0].image_path}`"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </template>
                    <template x-if="!currentRoomType.images || currentRoomType.images.length == 0">
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-image text-5xl mb-2"></i>
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">មិនទាន់មានរូបភាព</span>
                        </div>
                    </template>
                </div>

                <div class="p-8 space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center rounded-2xl text-blue-600 dark:text-blue-400 text-2xl shadow-sm">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div>
                            <h4 class="text-2xl font-black dark:text-white uppercase tracking-tight" x-text="currentRoomType.name"></h4>
                            <p class="text-xs font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mt-0.5">
                                <i class="fa-solid fa-hotel mr-1 text-[10px]"></i>
                                <span x-text="currentRoomType.hotel?.name ? currentRoomType.hotel.name : 'មិនមានប្រភពសណ្ឋាគារ'"></span>
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-500/5 rounded-2xl border border-emerald-100 dark:border-emerald-500/10">
                            <p class="text-[10px] text-emerald-600/70 dark:text-emerald-400/60 uppercase font-black tracking-wider mb-1"
                                x-text="currentRoomType.category === 'meeting' ? 'តម្លៃជួលសាលប្រជុំ' : 'តម្លៃគោលក្នុងមួយយប់'"></p>
                            <div class="flex flex-col">
                                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400" x-text="'$' + parseFloat(currentRoomType.base_price || 0).toFixed(2)"></p>
                                <p class="text-xs font-bold text-gray-400 font-mono" x-text="'(~ ' + (parseFloat(currentRoomType.base_price || 0) * {{ $khrRate }}).toLocaleString('en-US') + ' ៛)'"></p>
                            </div>
                        </div>

                        <div class="p-4 bg-blue-50 dark:bg-blue-500/5 rounded-2xl border border-blue-100 dark:border-blue-500/10">
                            <p class="text-[10px] text-blue-600/70 dark:text-blue-400/60 uppercase font-black tracking-wider mb-1"
                                x-text="currentRoomType.category === 'meeting' ? 'ចំនួនអ្នកចូលរួមអតិបរមា' : 'ចំនួនភ្ញៀវស្នាក់នៅអតិបរមា'"></p>
                            <p class="text-2xl font-black text-blue-600 dark:text-blue-400" x-text="currentRoomType.max_guests + ' នាក់'"></p>
                        </div>

                        <div class="col-span-2 md:col-span-1 p-4 rounded-2xl border transition-all duration-300"
                            :class="currentRoomType.category === 'meeting' 
            ? 'bg-amber-50 dark:bg-amber-500/5 border-amber-100 dark:border-amber-500/10 text-amber-600 dark:text-amber-400' 
            : 'bg-purple-50 dark:bg-purple-500/5 border-purple-100 dark:border-purple-500/10 text-purple-600 dark:text-purple-400'">

                            <p class="text-[10px] uppercase font-black tracking-wider mb-1 opacity-70">ប្រភេទសេវាកម្ម</p>

                            <div class="flex items-center gap-2 mt-1">
                                <i class="fas text-xl" :class="currentRoomType.category === 'meeting' ? 'fa-users' : 'fa-bed'"></i>
                                <p class="text-xl font-black"
                                    x-text="currentRoomType.category === 'meeting' ? 'សាលប្រជុំ' : 'បន្ទប់ស្នាក់នៅ'"></p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase font-black tracking-wider italic">ពិពណ៌នា (Description)</p>
                        <div class="p-5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-transparent dark:border-gray-800">
                            <div class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed ck-content"
                                x-html="currentRoomType.description && currentRoomType.description !== 'null' && currentRoomType.description !== '' ? currentRoomType.description : '<span class=\'text-gray-400 italic\'>មិនមានការពិពណ៌នាឡើយ</span>'">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase font-black tracking-wider italic">គ្រឿងបរិក្ខាររួមបញ្ចូល (Included Facilities)</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-if="currentRoomType.facilities && currentRoomType.facilities.length > 0">
                                <template x-for="facility in currentRoomType.facilities" :key="facility.id">
                                    <span class="px-4 py-2 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-black uppercase tracking-wide border border-gray-100 dark:border-gray-750 flex items-center gap-2 shadow-sm">
                                        <i class="fa-solid fa-circle-check text-[10px] text-blue-500"></i>
                                        <span x-text="facility.name"></span>
                                    </span>
                                </template>
                            </template>
                            <template x-if="!currentRoomType.facilities || currentRoomType.facilities.length == 0">
                                <span class="text-xs text-gray-400 dark:text-gray-500 italic">មិនមានគ្រឿងបរិក្ខាររួមបញ្ចូលទេ</span>
                            </template>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase font-black tracking-wider italic">រូបភាពទាំងអស់នៅក្នុងអាល់ប៊ុម (Gallery)</p>
                        <div class="flex gap-3 overflow-x-auto pb-3 pt-1 custom-scrollbar">
                            <template x-if="currentRoomType.images && currentRoomType.images.length > 0">
                                <template x-for="img in currentRoomType.images" :key="img.id">
                                    <a :href="img.url ? img.url : `/storage/${img.image_path}`" class="spotlight flex-shrink-0 group/img">
                                        <img :src="img.url ? img.url : `/storage/${img.image_path}`"
                                            class="w-24 h-20 rounded-xl object-cover border-2 border-gray-100 dark:border-gray-800 hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 cursor-zoom-in shadow-sm group-hover/img:scale-95">
                                    </a>
                                </template>
                            </template>
                            <template x-if="!currentRoomType.images || currentRoomType.images.length == 0">
                                <p class="text-xs text-gray-400 dark:text-gray-500 italic">គ្មានរូបភាពផ្សេងទៀតទេ</p>
                            </template>
                        </div>
                    </div>

                </div>
            </div>

            <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/40 flex justify-end items-center border-t dark:border-gray-800">
                <button type="button" @click="showDetailModal = false"
                    class="px-8 h-11 bg-gray-900 dark:bg-white dark:text-gray-900 text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] hover:opacity-90 active:scale-95 transition-all shadow-md">
                    បិទ
                </button>
            </div>

        </div>
    </div>
</div>