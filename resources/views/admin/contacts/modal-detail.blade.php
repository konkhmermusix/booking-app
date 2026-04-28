<div x-show="showDetailModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
    <div @click.away="showDetailModal = true" class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden p-8 border-gray-100 dark:border-gray-800">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-black dark:text-white">ព័ត៌មានលម្អិតនៃសារ</h3>
            <button @click="showDetailModal = false" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-2xl">
                    <label class="text-[10px] uppercase font-bold text-gray-400">ឈ្មោះ</label>
                    <p class="font-bold dark:text-white" x-text="currentMessage.name"></p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-2xl">
                    <label class="text-[10px] uppercase font-bold text-gray-400">ទូរស័ព្ទ</label>
                    <p class="font-bold dark:text-white" x-text="currentMessage.tell"></p>
                </div>
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-2xl">
                <label class="text-[10px] uppercase font-bold text-gray-400">អ៊ីមែល</label>
                <p class="font-bold dark:text-white" x-text="currentMessage.email"></p>
            </div>

            <div class="p-4 bg-blue-50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-900/20">
                <label class="text-[10px] uppercase font-bold text-blue-400">សារបញ្ជូនមក</label>
                <p class="text-sm dark:text-gray-300 leading-relaxed mt-1" x-text="currentMessage.description"></p>
            </div>
        </div>

        <div class="mt-8 flex gap-3">
            <form :action="'/admin/messages/' + currentMessage.id" method="POST" class="flex-1">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button class="w-full py-4 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold shadow-lg shadow-green-500/30 transition-all">
                    សម្គាល់ថាដោះស្រាយរួច
                </button>
            </form>
            <button @click="showDetailModal = false" class="px-6 py-4 bg-gray-100 dark:bg-gray-800 dark:text-white rounded-2xl font-bold">បិទ</button>
        </div>
    </div>
</div>