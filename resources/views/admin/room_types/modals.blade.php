<div x-show="showAddModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-[1.5rem] shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <i class="fa-solid fa-layer-group text-xl"></i>
                    </div>
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
                            <select name="hotel_id" required class="w-full h-12 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all appearance-none font-bold">
                                <option value="" disabled selected>ជ្រើសរើស...</option>
                                @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ឈ្មោះប្រភេទបន្ទប់ <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="ឧ. បន្ទប់គ្រែមួយ" class="w-full h-12 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">តម្លៃគោល ($) <span class="text-red-500">*</span></label>
                            <input type="number" name="base_price" step="0.01" required placeholder="0.00" class="w-full h-12 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ភ្ញៀវអតិបរមា <span class="text-red-500">*</span></label>
                            <input type="number" name="max_guests" required placeholder="2" class="w-full h-12 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 font-bold">
                        </div>
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
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-[1.5rem] bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer transition-all border-blue-500/30">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa-solid fa-images text-2xl text-blue-500 mb-2"></i>
                                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest text-center px-4">
                                        ចុចដើម្បីបញ្ចូលរូបភាពច្រើនសន្លឹក <br>
                                        <span class="text-blue-400">(JPG, PNG, WEBP)</span>
                                    </p>
                                </div>
                                <input type="file" name="images[]" multiple class="hidden" accept="image/*" @change="handleFileSelect" />
                            </label>
                        </div>

                        <template x-if="previews.length > 0">
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 mt-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                                <template x-for="(src, index) in previews" :key="index">
                                    <div class="relative aspect-square group/item">
                                        <img :src="src" class="w-full h-full object-cover rounded-xl shadow-sm ring-2 ring-white dark:ring-gray-700">

                                        <button type="button" @click="previews.splice(index, 1)"
                                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow-lg opacity-0 group-hover/item:opacity-100 transition-opacity">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </template>

                                <div class="flex items-center justify-center border-2 border-dotted border-gray-200 dark:border-gray-700 rounded-xl">
                                    <span class="text-[10px] font-black text-gray-400" x-text="'+' + previews.length"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 rounded-b-[1.5rem] border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false; previews = []" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic">បោះបង់</button>
                    <button type="submit" class="px-8 h-10 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-500/20 active:scale-95 transition-all">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-[1.5rem] shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-50 dark:bg-orange-500/10 rounded-2xl flex items-center justify-center text-orange-600">
                        <i class="fa-solid fa-pen-to-square text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែសម្រួលប្រភេទបន្ទប់</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Type, Images & Facilities</p>
                    </div>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>

            <form :action="`{{ url('admin/room_types') }}/${currentRoomType.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">សណ្ឋាគារ</label>
                            <select name="hotel_id" x-model="currentRoomType.hotel_id" required class="w-full h-12 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 font-bold">
                                @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ឈ្មោះប្រភេទបន្ទប់</label>
                            <input type="text" name="name" x-model="currentRoomType.name" required class="w-full h-12 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">តម្លៃគោល ($)</label>
                            <input type="number" name="base_price" step="0.01" x-model="currentRoomType.base_price" required class="w-full h-12 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 font-bold">
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
                                <div class="relative w-20 h-20 group">
                                    <img :src="`/storage/${image.image_path}`" class="w-full h-full object-cover rounded-xl">

                                    <button type="button"
                                        @click="if(confirm('តើអ្នកចង់លុបរូបភាពនេះមែនទេ?')) deleteExistingImage(image.id)"
                                        class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fa-solid fa-times"></i>
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



<div x-show="showDetailModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showDetailModal = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ព័ត៌មានលម្អិត៖ <span x-text="currentType.name" class="text-blue-500"></span></h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <div class="h-48 bg-gray-200 dark:bg-gray-800 relative overflow-hidden">
                <template x-if="currentType.images && currentType.images.length > 0">
                    <img :src="'/storage/' + currentType.images[0].image_path" class="w-full h-full object-cover">
                </template>
                <template x-if="!currentType.images || currentType.images.length == 0">
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <i class="fas fa-image text-4xl"></i>
                    </div>
                </template>
            </div>

            <div class="p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center rounded-2xl text-blue-600 text-xl">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold dark:text-white" x-text="currentType.name"></h4>
                        <p class="text-sm text-gray-400" x-text="currentType.hotel?.name"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border dark:border-gray-800 text-center">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">តម្លៃគោល</p>
                        <p class="text-lg font-bold text-emerald-600" x-text="'$' + parseFloat(currentType.base_price).toFixed(2)"></p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border dark:border-gray-800 text-center">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">ចំនួនភ្ញៀវ</p>
                        <p class="text-lg font-bold dark:text-gray-200" x-text="currentType.max_guests + ' នាក់'"></p>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-2">រូបភាពទាំងអស់</p>
                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                        <template x-for="img in currentType.images" :key="img.id">
                            <img :src="'/storage/' + img.image_path" class="w-16 h-16 rounded-lg object-cover flex-shrink-0 border dark:border-gray-700">
                        </template>
                    </div>
                </div>

                <button @click="showDetailModal = false" class="w-full py-3 bg-gray-100 dark:bg-gray-800 dark:text-gray-300 rounded-2xl font-bold hover:bg-gray-200 transition-all">បិទត្រឡប់ទៅវិញ</button>
            </div>
        </div>
    </div>
</div>

<script>
    async function deleteImage(imageId) {
        const result = await Swal.fire({
            title: 'លុបរូបភាព?',
            text: "តើអ្នកចង់លុបរូបភាពនេះចេញពី Gallery មែនទេ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'យល់ព្រម',
            cancelButtonText: 'បោះបង់'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/admin/room_types/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();

                if (data.success) {
                    const container = document.getElementById(`img-container-${imageId}`);
                    if (container) {
                        container.style.transform = 'scale(0)';
                        container.style.opacity = '0';
                        setTimeout(() => container.remove(), 300);
                    }
                    Swal.fire('ជោគជ័យ', 'រូបភាពត្រូវបានលុប', 'success');
                }
            } catch (error) {
                Swal.fire('បរាជ័យ', 'មានបញ្ហាប្រព័ន្ធ!', 'error');
            }
        }
    }
</script>