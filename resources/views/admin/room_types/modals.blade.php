<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមប្រភេទបន្ទប់ថ្មី</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('room_types.store') }}" method="POST" enctype="multipart/form-data" x-data="{ previews: [] }">
                @csrf
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <div>
                        <label class="block text-sm font-bold mb-1 dark:text-gray-300 uppercase text-[11px] tracking-wider">សណ្ឋាគារ</label>
                        <select name="hotel_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                            @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-1 dark:text-gray-300 uppercase text-[11px] tracking-wider">ឈ្មោះប្រភេទបន្ទប់</label>
                        <input type="text" name="name" placeholder="បន្ទប់គ្រែពីរ" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-1 dark:text-gray-300 uppercase text-[11px] tracking-wider">ចំនួនភ្ញៀវអតិបរមា</label>
                            <input type="number" name="max_guests" value="2" min="1"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1 dark:text-gray-300 uppercase text-[11px] tracking-wider">តម្លៃគោល ($)</label>
                            <input type="number" step="0.01" name="base_price" required placeholder="0.00"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-1 dark:text-gray-300 uppercase text-[11px] tracking-wider">បរិយាយ</label>
                        <textarea name="description" rows="3" placeholder="ព័ត៌មានបន្ថែមអំពីបន្ទប់..."
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
                    </div>

                    <div class="border-t dark:border-gray-800 pt-4">
                        <label class="block text-sm font-bold mb-2 dark:text-gray-300 uppercase text-[11px] tracking-wider">រូបភាពបន្ទប់</label>
                        <div class="relative group">
                            <input type="file" name="images[]" multiple accept="image/*"
                                @change="previews = Array.from($event.target.files).map(file => URL.createObjectURL(file))"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-800 dark:file:text-blue-400 cursor-pointer">
                        </div>

                        <div class="flex flex-wrap gap-2 mt-3" x-show="previews.length > 0">
                            <template x-for="(url, index) in previews" :key="index">
                                <div class="relative w-20 h-20 rounded-lg overflow-hidden border-2 border-blue-500/30">
                                    <img :src="url" class="w-full h-full object-cover">
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="border-t dark:border-gray-800 pt-4">
                        <label class="block text-sm font-bold mb-3 dark:text-gray-300 uppercase text-[11px] tracking-wider">គ្រឿងបរិក្ខារ (Facilities)</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($facilities as $facility)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all group">
                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500/20 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition-colors">{{ $facility->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="px-8 py-5 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3 rounded-b-3xl border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false"
                        class="px-6 py-2.5 text-gray-500 font-bold hover:text-gray-700 transition-colors">បោះបង់</button>
                    <button type="submit"
                        class="px-8 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 active:scale-95 transition-all">
                        រក្សាទុកទិន្នន័យ
                    </button>
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
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែប្រែ៖ <span x-text="currentType.name" class="text-blue-500"></span></h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/room_types') }}/${currentType.id}`" method="POST" enctype="multipart/form-data" x-data="{ newPreviews: [] }">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">សណ្ឋាគារ</label>
                        <select name="hotel_id" x-model="currentType.hotel_id"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-emerald-500/20">
                            @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">ឈ្មោះប្រភេទបន្ទប់</label>
                        <input type="text" name="name" x-model="currentType.name"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">ចំនួនភ្ញៀវ</label>
                            <input type="number" name="max_guests" x-model="currentType.max_guests"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">តម្លៃគោល ($)</label>
                            <input type="number" step="0.01" name="base_price" x-model="currentType.base_price"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                    </div>

                    <div>
                        <div>
                            <textarea name="description" x-model="currentType.description"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 border-t dark:border-gray-800 pt-4">
                        <label class="block text-sm font-bold dark:text-gray-300 uppercase text-[11px] tracking-wider mb-3">រូបភាពដែលមានស្រាប់</label>
                        <div class="grid grid-cols-3 gap-3">
                            <template x-for="img in currentType.images" :key="img.id">
                                <div class="relative group aspect-video rounded-xl overflow-hidden border dark:border-gray-700 bg-gray-100" :id="'img-container-' + img.id">
                                    <img :src="'/storage/' + img.image_path" class="w-full h-full object-cover">
                                    <button type="button" @click="deleteImage(img.id)"
                                        class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-red-600 shadow-lg">
                                        <i class="fas fa-times text-[10px]"></i>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div class="mt-4 p-4 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 italic">បន្ថែមរូបភាពថ្មី</label>
                            <input type="file" name="images[]" multiple accept="image/*"
                                @change="newPreviews = Array.from($event.target.files).map(file => URL.createObjectURL(file))"
                                class="block w-full text-xs text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-gray-800 dark:file:text-emerald-400 cursor-pointer">

                            <div class="flex flex-wrap gap-2 mt-3" x-show="newPreviews.length > 0">
                                <template x-for="(url, index) in newPreviews" :key="index">
                                    <div class="relative w-16 h-16 rounded-lg overflow-hidden border-2 border-emerald-500/30">
                                        <img :src="url" class="w-full h-full object-cover">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="border-t dark:border-gray-800 pt-4">
                        <label class="block text-sm font-bold mb-3 dark:text-gray-300 uppercase text-[11px] tracking-wider">គ្រឿងបរិក្ខារ (Facilities)</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($facilities as $facility)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all group">
                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                    :checked="currentType.facilities?.some(f => f.id == {{ $facility->id }})"
                                    class="w-5 h-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500/20 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-emerald-600 transition-colors">{{ $facility->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="px-8 py-5 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3 rounded-b-3xl border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false"
                        class="px-6 py-2 text-gray-500 font-bold hover:text-gray-700 transition-colors">បោះបង់</button>
                    <button type="submit"
                        class="px-8 py-2.5 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 active:scale-95 transition-all">
                        <i class="fas fa-save mr-2"></i>ធ្វើបច្ចុប្បន្នភាព
                    </button>
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