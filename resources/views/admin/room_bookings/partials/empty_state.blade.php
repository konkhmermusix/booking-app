<div class="flex flex-col items-center justify-center py-12 px-4 rounded-2xl bg-gray-50/50 dark:bg-gray-850/20 border border-dashed border-gray-200 dark:border-gray-800 transition-colors">
    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 rounded-2xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
        <i class="fa-solid fa-calendar-xmark text-2xl"></i>
    </div>

    <div class="text-center max-w-sm">
        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200">
            មិនមានទិន្នន័យការកក់បន្ទប់ទេ
        </h3>
        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1 leading-relaxed">
            ពុំមានទិន្នន័យរកឃើញទៅតាមលក្ខខណ្ឌស្វែងរក ឬតម្រងទិន្នន័យដែលអ្នកបានរៀបចំឡើយ។ សូមសាកល្បងម្តងទៀត!
        </p>
    </div>

    <button @click="search = ''; status = ''; fetchBookings();"
        class="mt-4 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300 text-gray-600 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 active:scale-95">
        <i class="fa-solid fa-rotate-left text-sm"></i> សម្អាតតម្រង
    </button>
</div>