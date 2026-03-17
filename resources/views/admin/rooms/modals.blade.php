<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមបន្ទប់ថ្មី</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">សណ្ឋាគារ <span class="text-red-500">*</span></label>
                            <select name="hotel_id" required class="w-full h-[52px] px-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none">
                                <option value="" disabled selected>ជ្រើសរើសសណ្ឋាគារ...</option>
                                @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">ប្រភេទបន្ទប់ <span class="text-red-500">*</span></label>
                            <select name="room_type_id" required class="w-full h-[52px] px-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                <option value="" disabled selected>ជ្រើសរើសប្រភេទបន្ទប់...</option>
                                @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">លេខបន្ទប់ <span class="text-red-500">*</span></label>
                            <input type="text" name="room_number" required placeholder="ឧ. 101"
                                class="w-full h-[52px] px-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">ជាន់</label>
                            <input type="text" name="floor" placeholder="A01"
                                class="w-full h-[52px] px-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">ស្ថានភាពដំបូង</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="relative">
                                    <input type="radio" name="status" value="available" class="peer sr-only" checked>
                                    <div class="flex items-center justify-center h-[52px] rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-500/10 peer-checked:text-green-600 cursor-pointer transition-all text-sm font-bold">
                                        ទំនេរ
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="status" value="booked" class="peer sr-only">
                                    <div class="flex items-center justify-center h-[52px] rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-500/10 peer-checked:text-blue-600 cursor-pointer transition-all text-sm font-bold">
                                        មានភ្ញៀវ
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="status" value="maintenance" class="peer sr-only">
                                    <div class="flex items-center justify-center h-[52px] rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-500/10 peer-checked:text-red-600 cursor-pointer transition-all text-sm font-bold">
                                        ជួសជុល
                                    </div>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="px-8 py-6 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3 rounded-b-[2.5rem] border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false"
                        class="px-6 py-3 font-bold text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white transition-all">
                        បោះបង់
                    </button>
                    <button type="submit"
                        class="px-10 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/30 active:scale-95 transition-all">
                        រក្សាទុក
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
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែប្រែបន្ទប់៖ <span x-text="currentRoom.room_number"></span></h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/rooms') }}/${currentRoom.id}`" method="POST">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">សណ្ឋាគារ</label>
                            <select name="hotel_id" x-model="currentRoom.hotel_id" required
                                class="w-full h-[52px] px-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all">
                                @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">ប្រភេទបន្ទប់</label>
                            <select name="room_type_id" x-model="currentRoom.room_type_id" required
                                class="w-full h-[52px] px-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all">
                                @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-bold mb-2 dark:text-gray-300">លេខបន្ទប់ <span class="text-red-500">*</span></label>
                            <input type="text" name="room_number" x-model="currentRoom.room_number" required
                                class="w-full h-[52px] px-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all">
                        </div>

                        <div>
                            <label class="text-sm font-bold mb-2 dark:text-gray-300">ជាន់</label>
                            <input type="text" name="floor" x-model="currentRoom.floor"
                                class="w-full h-[52px] px-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all">
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">បច្ចុប្បន្នភាពស្ថានភាព</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="relative">
                                    <input type="radio" name="status" value="available" x-model="currentRoom.status" class="peer sr-only">
                                    <div class="flex items-center justify-center h-[52px] rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-500/10 peer-checked:text-green-600 cursor-pointer transition-all text-sm font-bold">
                                        ទំនេរ
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="status" value="booked" x-model="currentRoom.status" class="peer sr-only">
                                    <div class="flex items-center justify-center h-[52px] rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-500/10 peer-checked:text-blue-600 cursor-pointer transition-all text-sm font-bold">
                                        មានភ្ញៀវ
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="status" value="maintenance" x-model="currentRoom.status" class="peer sr-only">
                                    <div class="flex items-center justify-center h-[52px] rounded-2xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 dark:text-gray-400 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-500/10 peer-checked:text-red-600 cursor-pointer transition-all text-sm font-bold">
                                        ជួសជុល
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3 rounded-b-[2.5rem] border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false"
                        class="px-6 py-3 font-bold text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white transition-all">
                        បោះបង់
                    </button>
                    <button type="submit"
                        class="px-10 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl shadow-lg shadow-amber-500/30 active:scale-95 transition-all">
                        ធ្វើបច្ចុប្បន្នភាព
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
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ព័ត៌មានលម្អិត៖ <span x-text="currentRoom.room_number"></span></h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <div class="relative h-56 w-full overflow-hidden">
                <template x-if="currentRoom.room_type.images && currentRoom.room_type.images.length > 0">
                    <img :src="currentRoom.room_type.images.length > 0 ? '/storage/' + currentRoom.room_type.images[0].image_path : '/images/default-room.jpg'"
                        class="w-full h-full object-cover">
                </template>
                <template x-if="!currentRoom.room_type.images || currentRoom.room_type.images.length === 0">
                    <div class="w-full h-full bg-gradient-to-r from-indigo-500 to-blue-600 flex items-center justify-center">
                        <i class="fas fa-image text-white text-5xl opacity-30"></i>
                    </div>
                </template>

                <div class="absolute top-4 right-4">
                    <span :class="{'bg-green-500': currentRoom.status === 'available', 'bg-blue-500': currentRoom.status === 'booked', 'bg-red-500': currentRoom.status === 'maintenance'}" class="px-4 py-1.5 rounded-full text-white text-[10px] font-black uppercase shadow-lg shadow-black/20" x-text="currentRoom.status"></span>
                </div>
            </div>

            <div class="px-6 pb-6 relative">
                <div class="flex justify-center -mt-10 mb-4">
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-xl border-4 border-white dark:border-gray-900 text-3xl">🏨</div>
                </div>

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-black dark:text-white tracking-tight">បន្ទប់លេខ <span x-text="currentRoom.room_number"></span></h3>
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400" x-text="currentRoom.hotel.name"></p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 rounded-2xl bg-gray-50 dark:bg-gray-800/50">
                        <span class="text-[10px] text-gray-400 uppercase font-bold block mb-1">ប្រភេទបន្ទប់</span>
                        <span class="font-bold dark:text-gray-200 text-sm" x-text="currentRoom.room_type.name"></span>
                    </div>
                    <div class="p-3 rounded-2xl bg-gray-50 dark:bg-gray-800/50">
                        <span class="text-[10px] text-gray-400 uppercase font-bold block mb-1">ជាន់ទី</span>
                        <span class="font-bold dark:text-gray-200 text-sm" x-text="currentRoom.floor || 'N/A'"></span>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest border-l-4 border-blue-500 pl-2">បរិក្ខារក្នុងបន្ទប់</h4>

                    <p class="text-xs text-blue-500">ចំនួនបរិក្ខារ៖ <span x-text="currentRoom.room_type?.facilities?.length || 0"></span></p>

                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <template x-for="facility in currentRoom.room_type?.facilities" :key="facility.id">
                            <div class="flex items-center gap-3 p-3 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm">
                                <div class="text-blue-500 text-lg">
                                    <i :class="facility.icon"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold dark:text-gray-300 leading-none" x-text="facility.name"></p>
                                </div>
                            </div>
                        </template>
                        <pre class="text-[10px] text-red-500" x-text="JSON.stringify(currentRoom.room_type.facilities, null, 2)"></pre>
                    </div>

                    <template x-if="!currentRoom.room_type?.facilities || currentRoom.room_type?.facilities.length === 0">
                        <div class="p-4 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-2xl text-center">
                            <p class="text-xs text-gray-400 italic">មិនទាន់មានទិន្នន័យបរិក្ខារ...</p>
                        </div>
                    </template>
                </div>

                <button @click="showDetailModal = false" class="w-full mt-8 py-4 bg-gray-900 dark:bg-white dark:text-gray-900 text-white rounded-2xl font-black text-sm transition-all active:scale-95 shadow-lg shadow-gray-500/20">បិទផ្ទាំងព័ត៌មាន</button>
            </div>
        </div>
    </div>
</div>