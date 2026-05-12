<div class="flex flex-col items-center justify-center py-20 px-4 bg-white dark:bg-gray-800 rounded-[1.5rem] border-2 border-dashed border-gray-100 dark:border-gray-700 transition-all">

    <div class="relative mb-6">
        <div class="w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center animate-pulse">
            <i class="fa-solid fa-layer-group text-4xl text-blue-500/50"></i>
        </div>
        <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-white dark:bg-gray-800 rounded-full shadow-lg flex items-center justify-center text-amber-500">
            <i class="fa-solid fa-circle-plus text-xl"></i>
        </div>
    </div>

    <div class="text-center max-w-xs">
        <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2 uppercase tracking-tight">
            មិនទាន់មានការកក់បន្ទប់តាមកូដនេះទេ
        </h3>
    </div>

    <button @click="showAddModal = true"
        class="flex items-center gap-3 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-blue-500/30 hover:scale-105 active:scale-95 transition-all">
        <i class="fa-solid fa-plus text-xs"></i>
        បន្ថែមកក់បន្ទប់
    </button>
</div>