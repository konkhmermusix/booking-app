<div class="flex flex-col items-center justify-center py-20 px-4 bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm transition-colors border-none">
    <div class="relative mb-6">
        <div class="absolute inset-0 bg-blue-100 dark:bg-blue-900/20 rounded-full blur-2xl opacity-50 scale-150"></div>
        <div class="relative w-24 h-24 bg-blue-50 dark:bg-gray-900/50 rounded-3xl flex items-center justify-center border border-blue-100 dark:border-gray-700">
            <i class="fa-solid fa-bed-pulse text-4xl text-blue-500 dark:text-blue-400 opacity-80"></i>
            <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-none border-2 border-white dark:border-gray-800">
                <i class="fa-solid fa-plus text-white text-[10px]"></i>
            </div>
        </div>
    </div>

    <div class="text-center max-w-sm">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">មិនទាន់មានទិន្នន័យបន្ទប់</h3>
        <p class="text-sm text-gray-400 dark:text-gray-500 leading-relaxed">
            ប្រព័ន្ធមិនទាន់មានព័ត៌មានបន្ទប់សម្រាប់បង្ហាញនៅឡើយទេ។ សូមចាប់ផ្តើមដោយការបន្ថែមបន្ទប់ថ្មីរបស់អ្នក។
        </p>
    </div>

    <button @click="showAddModal = true"
        class="mt-8 px-6 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-200 dark:shadow-none flex items-center gap-3 transition-all active:scale-95 group">
        <i class="fa-solid fa-plus-circle transition-transform group-hover:rotate-90"></i>
        បន្ថែមបន្ទប់
    </button>
</div>