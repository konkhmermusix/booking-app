<div x-show="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm" x-cloak>
    <div class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-[1.5rem] shadow-2xl border border-gray-100 dark:border-gray-800">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
            <h3 class="font-bold dark:text-white uppercase text-sm tracking-wider">បន្ថែមព័ត៌មានថ្មី</h3>
            <button @click="showAddModal = false" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('contacts_sett.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Key (Unique)</label>
                    <input type="text" name="key" placeholder="e.g. telegram_link" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm dark:text-white" required>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Label</label>
                    <input type="text" name="label" placeholder="e.g. តេឡេក្រាម" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm dark:text-white" required>
                </div>
            </div>
            <input type="hidden" name="status" value="1">
            <button type="submit" class="w-full py-3 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition-all">រក្សាទុកទិន្នន័យ</button>
        </form>
    </div>
</div>


<div x-show="showEditModal"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-end="opacity-0 scale-95"
    x-cloak>

    <div class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800" @click.away="showEditModal = false">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
            <h3 class="font-bold dark:text-white">កែប្រែព័ត៌មាន</h3>
            <button @click="showEditModal = false" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>

        <form :action="'{{ url('admin/contacts') }}/' + currentContact.id" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="space-y-1">
                <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">ចំណងជើង</label>
                <input type="text" name="label" x-model="currentContact.label" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500 dark:text-white">
            </div>

            <div class="space-y-1">
                <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">តម្លៃ (Value / Link Map)</label>
                <textarea name="value" x-model="currentContact.value" rows="4" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500 dark:text-white resize-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">Icon (FontAwesome)</label>
                    <input type="text" name="icon" x-model="currentContact.icon" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm dark:text-white">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">ពណ៌</label>
                    <select name="color" x-model="currentContact.color" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm dark:text-white">
                        <option value="blue">Blue</option>
                        <option value="red">Red</option>
                        <option value="green">Green</option>
                        <option value="amber">Amber</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl">
                <label class="text-sm font-bold text-gray-600 dark:text-gray-300">ស្ថានភាពបង្ហាញ</label>
                <select name="status" x-model="currentContact.status" class="bg-white dark:bg-gray-700 border-none rounded-lg text-xs font-bold">
                    <option :value="1">បង្ហាញ</option>
                    <option :value="0">លាក់</option>
                </select>
            </div>

            <div class="pt-4 flex gap-2">
                <button type="button" @click="showEditModal = false" class="flex-1 py-3 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition-all">បោះបង់</button>
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 active:scale-95 transition-all">រក្សាទុក</button>
            </div>
        </form>
    </div>
</div>


<div x-show="showDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm" x-cloak>
    <div class="bg-white dark:bg-gray-900 w-full max-w-sm rounded-[2rem] p-8 text-center shadow-2xl">
        <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="text-lg font-bold dark:text-white mb-2">តើអ្នកប្រាកដទេ?</h3>
        <p class="text-sm text-gray-500 mb-6">ទិន្នន័យនេះនឹងត្រូវលុបចេញពីប្រព័ន្ធរហូត!</p>
        <form :action="'{{ url('admin/contacts') }}/' + currentContact.id" method="POST" class="flex gap-3">
            @csrf
            @method('DELETE')
            <button type="button" @click="showDeleteModal = false" class="flex-1 py-3 text-sm font-bold bg-gray-100 text-gray-500 rounded-xl">បោះបង់</button>
            <button type="submit" class="flex-1 py-3 bg-red-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-red-500/30 hover:bg-red-700">យល់ព្រមលុប</button>
        </form>
    </div>
</div>