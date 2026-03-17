<div x-show="showAddModal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
    <div class="bg-white dark:bg-gray-900 rounded-3xl w-full max-w-lg overflow-hidden border dark:border-gray-800 shadow-2xl">
        <div class="px-8 py-5 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <h3 class="font-black text-lg dark:text-white uppercase">បន្ថែម Slide ថ្មី</h3>
            <button @click="showAddModal = false" class="text-2xl hover:rotate-90 transition-all dark:text-white">&times;</button>
        </div>
        <form action="{{ route('slideshows.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">ចំណងជើង</label>
                <input type="text" name="title" class="w-full px-4 py-2.5 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:border-blue-500 border-gray-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">ចំណងជើងរង</label>
                <input type="text" name="subtitle" class="w-full px-4 py-2.5 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:border-blue-500 border-gray-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">រូបភាព</label>
                <input type="file" name="image" required class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-600 file:text-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">លំដាប់បង្ហាញ</label>
                <input type="number" name="order_column" value="1" class="w-full px-4 py-2.5 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none border-gray-200">
            </div>
            <div class="pt-4 flex justify-end gap-3">
                <button type="button" @click="showAddModal = false" class="px-6 py-2.5 font-bold text-gray-400">បោះបង់</button>
                <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/20">រក្សាទុក</button>
            </div>
        </form>
    </div>
</div>

<div x-show="showEditModal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
    <div class="bg-white dark:bg-gray-900 rounded-3xl w-full max-w-lg overflow-hidden border dark:border-gray-800 shadow-2xl text-left">
        <div class="px-8 py-5 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <h3 class="font-black text-lg dark:text-white uppercase">កែប្រែ Slide</h3>
            <button @click="showEditModal = false" class="text-2xl hover:rotate-90 transition-all dark:text-white">&times;</button>
        </div>
        <form :action="`{{ url('admin/slideshows') }}/${currentSlide.id}`" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">ចំណងជើង</label>
                <input type="text" name="title" x-model="currentSlide.title" class="w-full px-4 py-2.5 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none border-gray-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">ចំណងជើងរង</label>
                <input type="text" name="subtitle" x-model="currentSlide.subtitle" class="w-full px-4 py-2.5 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none border-gray-200">
            </div>
            <div class="flex items-center gap-4">
                <img :src="'/storage/' + currentSlide.image_path" class="w-20 h-12 rounded-lg object-cover">
                <input type="file" name="image" class="text-xs text-gray-400">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">លំដាប់បង្ហាញ</label>
                <input type="number" name="order_column" value="1" x-model="currentSlide.order_column" class="w-full px-4 py-2.5 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none border-gray-200">
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" :checked="currentSlide.is_active" value="1" class="w-5 h-5 rounded-lg border-gray-300">
                <label class="text-sm font-bold dark:text-gray-300">បើកដំណើរការ</label>
            </div>
            <div class="pt-4 flex justify-end gap-3">
                <button type="button" @click="showEditModal = false" class="px-6 py-2.5 font-bold text-gray-400">បោះបង់</button>
                <button type="submit" class="px-8 py-2.5 bg-emerald-600 text-white rounded-2xl font-bold shadow-lg shadow-emerald-500/20">ធ្វើបច្ចុប្បន្នភាព</button>
            </div>
        </form>
    </div>
</div>

<div x-show="showDetailModal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
    <div class="bg-white dark:bg-gray-900 rounded-3xl w-full max-w-md overflow-hidden border dark:border-gray-800 shadow-2xl p-8 text-center">
        <img :src="'/storage/' + currentSlide.image_path" class="w-full h-48 rounded-2xl object-cover mb-6 shadow-lg border dark:border-gray-700">
        <h4 class="text-2xl font-black dark:text-white" x-text="currentSlide.title || 'គ្មានចំណងជើង'"></h4>
        <p class="text-gray-400 text-sm mt-2 mb-8" x-text="currentSlide.subtitle || 'គ្មានការបរិយាយ'"></p>
        <button @click="showDetailModal = false" class="w-full py-3.5 bg-gray-100 dark:bg-gray-800 dark:text-gray-300 rounded-2xl font-bold hover:bg-gray-200 transition-all">បិទត្រឡប់ទៅវិញ</button>
    </div>
</div>