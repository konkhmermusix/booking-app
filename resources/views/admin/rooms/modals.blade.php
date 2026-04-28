<div x-show="showAddModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <i class="fa-solid fa-hotel text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមបន្ទប់ថ្មី</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Create New Room Entry</p>
                    </div>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="p-10 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                សណ្ឋាគារ <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <i class="fa-solid fa-location-dot absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors z-10"></i>
                                <select name="hotel_id" required class="w-full h-14 pl-12 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium relative z-0">
                                    <option value="" disabled selected>ជ្រើសរើស...</option>
                                    @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                ប្រភេទបន្ទប់ <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <i class="fa-solid fa-door-open absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors z-10"></i>
                                <select name="room_type_id" required class="w-full h-14 pl-12 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium relative z-0">
                                    <option value="" disabled selected>ជ្រើសរើស...</option>
                                    @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                លេខបន្ទប់ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="room_number" required placeholder="ឧ. A01-01"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                ជាន់ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="floor" placeholder="A01"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 mb-4 ml-2 tracking-widest">ស្ថានភាពដំបូង <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-3 gap-4">
                                <label class="relative group">
                                    <input type="radio" name="status" value="available" class="peer sr-only" checked>
                                    <div class="flex flex-col items-center justify-center py-2 rounded-[1.0rem] border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-500/10 peer-checked:text-green-600 cursor-pointer transition-all">
                                        <i class="fa-solid fa-circle-check mb-1 text-sm"></i>
                                        <span class="text-[11px] font-black uppercase tracking-widest">ទំនេរ</span>
                                    </div>
                                </label>
                                <label class="relative group">
                                    <input type="radio" name="status" value="booked" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center py-2 rounded-[1.0rem] border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-500/10 peer-checked:text-blue-600 cursor-pointer transition-all">
                                        <i class="fa-solid fa-user-tag mb-1 text-sm"></i>
                                        <span class="text-[11px] font-black uppercase tracking-widest">មានភ្ញៀវ</span>
                                    </div>
                                </label>
                                <label class="relative group">
                                    <input type="radio" name="status" value="maintenance" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center py-2 rounded-[1.0rem] border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-orange-500 peer-checked:bg-orange-50 dark:peer-checked:bg-orange-500/10 peer-checked:text-orange-600 cursor-pointer transition-all">
                                        <i class="fa-solid fa-tools mb-1 text-sm"></i>
                                        <span class="text-[11px] font-black uppercase tracking-widest">ជួសជុល</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 rounded-b-[1.5rem] border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false"
                        class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic">
                        បោះបង់
                    </button>
                    <button type="submit"
                        class="px-8 h-10 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-500/20 active:scale-95 transition-all">
                        រក្សាទុក
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 dark:bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="fa-solid fa-pen-to-square text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែសម្រួលបន្ទប់</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Room Details</p>
                    </div>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/rooms') }}/${currentRoom.id}`" method="POST">
                @csrf
                @method('PUT')
                <div class="p-10 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                សណ្ឋាគារ <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <i class="fa-solid fa-location-dot absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors z-10"></i>
                                <select name="hotel_id" x-model="currentRoom.hotel_id" required
                                    class="w-full h-14 pl-12 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium relative z-0">
                                    @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                ប្រភេទបន្ទប់ <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <i class="fa-solid fa-door-open absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors z-10"></i>
                                <select name="room_type_id" x-model="currentRoom.room_type_id" required
                                    class="w-full h-14 pl-12 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium relative z-0">
                                    @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                លេខបន្ទប់ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="room_number" x-model="currentRoom.room_number" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                ជាន់ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="floor" x-model="currentRoom.floor" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 mb-4 ml-2 tracking-widest">ស្ថានភាពបច្ចុប្បន្ន <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-3 gap-4">
                                <label class="relative group">
                                    <input type="radio" name="status" value="available" x-model="currentRoom.status" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center py-2 rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-500/10 peer-checked:text-green-600 cursor-pointer transition-all">
                                        <i class="fa-solid fa-circle-check mb-1 text-sm"></i>
                                        <span class="text-[11px] font-black uppercase tracking-widest">ទំនេរ</span>
                                    </div>
                                </label>
                                <label class="relative group">
                                    <input type="radio" name="status" value="booked" x-model="currentRoom.status" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center py-2 rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-500/10 peer-checked:text-blue-600 cursor-pointer transition-all">
                                        <i class="fa-solid fa-user-tag mb-1 text-sm"></i>
                                        <span class="text-[11px] font-black uppercase tracking-widest">មានភ្ញៀវ</span>
                                    </div>
                                </label>
                                <label class="relative group">
                                    <input type="radio" name="status" value="maintenance" x-model="currentRoom.status" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center py-2 rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-orange-500 peer-checked:bg-orange-50 dark:peer-checked:bg-orange-500/10 peer-checked:text-orange-600 cursor-pointer transition-all">
                                        <i class="fa-solid fa-tools mb-1 text-sm"></i>
                                        <span class="text-[11px] font-black uppercase tracking-widest">ជួសជុល</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 dark:border-gray-800">
                    <button type="button" @click="showEditModal = false"
                        class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic">
                        បោះបង់
                    </button>
                    <button type="submit"
                        class="px-8 h-10 bg-amber-500 hover:bg-amber-600 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-amber-500/20 active:scale-95 transition-all">
                        ធ្វើបច្ចុប្បន្នភាព
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showDetailModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700 relative">
                <div class="absolute inset-0 opacity-30 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <button @click="showDetailModal = false" class="absolute top-5 right-5 w-10 h-10 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="absolute -bottom-10 left-10">
                    <div class="w-20 h-20 bg-white dark:bg-gray-900 rounded-2xl shadow-xl flex items-center justify-center text-blue-600 border-4 border-white dark:border-gray-900">
                        <i class="fa-solid fa-door-open text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="pt-14 p-10 space-y-8">
                <div>
                    <h3 class="text-2xl font-black dark:text-white uppercase tracking-tight" x-text="'បន្ទប់លេខ ' + currentRoom.room_number"></h3>
                    <p class="text-sm text-gray-400 font-bold uppercase tracking-widest mt-1" x-text="currentRoom.hotel_name || 'ឈ្មោះសណ្ឋាគារ'"></p>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">ប្រភេទបន្ទប់</span>
                        <p class="font-bold dark:text-gray-200" x-text="currentRoom.room_type_name || 'N/A'"></p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">ជាន់ / ទីតាំង</span>
                        <p class="font-bold dark:text-gray-200" x-text="currentRoom.floor || 'N/A'"></p>
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-gray-800">

                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">ស្ថានភាពបច្ចុប្បន្ន</span>
                        <div class="flex items-center gap-2 mt-1">
                            <template x-if="currentRoom.status === 'available'">
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-500/10 text-green-600 text-[10px] font-black uppercase rounded-lg border border-green-200 dark:border-green-500/20">ទំនេរ</span>
                            </template>
                            <template x-if="currentRoom.status === 'booked'">
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-500/10 text-blue-600 text-[10px] font-black uppercase rounded-lg border border-blue-200 dark:border-blue-500/20">មានភ្ញៀវ</span>
                            </template>
                            <template x-if="currentRoom.status === 'maintenance'">
                                <span class="px-3 py-1 bg-orange-100 dark:bg-orange-500/10 text-orange-600 text-[10px] font-black uppercase rounded-lg border border-orange-200 dark:border-orange-500/20">ជួសជុល</span>
                            </template>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">តម្លៃគោល</span>
                        <p class="text-2xl font-black text-blue-600" x-text="'$' + (currentRoom.price || '0.00')"></p>
                    </div>
                </div>
            </div>

            <div class="px-10 py-8 bg-gray-50 dark:bg-gray-800/50 flex gap-3">
                <button @click="showDetailModal = false"
                    class="flex-1 h-12 bg-white dark:bg-gray-800 border dark:border-gray-700 font-black text-[11px] uppercase tracking-widest rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all dark:text-white">
                    បិទវិញ
                </button>
                <button @click="showDetailModal = false; showEditModal = true"
                    class="flex-1 h-12 bg-blue-600 text-white font-black text-[11px] uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 hover:bg-blue-700 active:scale-95 transition-all">
                    កែសម្រួលព័ត៌មាន
                </button>
            </div>
        </div>
    </div>
</div>