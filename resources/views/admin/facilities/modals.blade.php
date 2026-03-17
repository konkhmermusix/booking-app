<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-lg relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមគ្រឿងបរិក្ខារថ្មី</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('facilities.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ឈ្មោះគ្រឿងបរិក្ខារ</label>
                        <input type="text" name="name" placeholder="ឧទាហរណ៍៖ ម៉ាស៊ីនត្រជាក់" required
                            class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:border-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">រូបតំណាង (FontAwesome)</label>
                            <input type="text" name="icon" placeholder="fas fa-snowflake"
                                class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">ប្រភេទ</label>
                            <select name="type" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                                <option value="room">សម្រាប់បន្ទប់ (Room)</option>
                                <option value="hotel">សម្រាប់សណ្ឋាគារ (Hotel)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-medium dark:text-gray-300">បើកដំណើរការ</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2 border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 text-gray-500 font-medium">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 font-bold">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showEditModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-lg relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែប្រែ៖ <span x-text="currentFacility.name" class="text-blue-500"></span></h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/facilities') }}/${currentFacility.id}`" method="POST">
                @csrf @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">ឈ្មោះគ្រឿងបរិក្ខារ</label>
                        <input type="text" name="name" x-model="currentFacility.name" required
                            class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:border-emerald-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Icon</label>
                            <input type="text" name="icon" x-model="currentFacility.icon"
                                class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">ប្រភេទ</label>
                            <select name="type" x-model="currentFacility.type" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                                <option value="room">Room</option>
                                <option value="hotel">Hotel</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" x-model="currentFacility.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ms-3 text-sm font-medium dark:text-gray-300">បើកដំណើរការ</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2 border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 text-gray-500">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/20 font-bold">ធ្វើបច្ចុប្បន្នភាព</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showDetailModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showDetailModal = false"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-sm relative border dark:border-gray-800 overflow-hidden">
            <div class="p-8 text-center">
                <div class="w-24 h-24 bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center rounded-3xl text-blue-600 dark:text-blue-400 text-4xl mx-auto mb-6 shadow-inner border dark:border-blue-800/50">
                    <i :class="currentFacility.icon || 'fas fa-box'"></i>
                </div>

                <h4 class="text-2xl font-black dark:text-white mb-1" x-text="currentFacility.name"></h4>
                <p class="text-sm text-gray-400 uppercase tracking-widest font-bold mb-6" x-text="currentFacility.type == 'room' ? 'Room Facility' : 'Hotel Facility'"></p>

                <div class="grid grid-cols-1 gap-3 mb-8 text-left">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border dark:border-gray-800 flex justify-between items-center">
                        <span class="text-xs text-gray-400 font-bold uppercase">ស្ថានភាព</span>
                        <span :class="currentFacility.is_active ? 'text-emerald-500' : 'text-red-500'" class="font-bold flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full" :class="currentFacility.is_active ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'"></span>
                            <span x-text="currentFacility.is_active ? 'Active' : 'Inactive'"></span>
                        </span>
                    </div>
                </div>

                <button @click="showDetailModal = false" class="w-full py-4 bg-gray-100 dark:bg-gray-800 dark:text-gray-300 rounded-2xl font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition-all border dark:border-gray-700">បិទត្រឡប់ទៅវិញ</button>
            </div>
        </div>
    </div>
</div>