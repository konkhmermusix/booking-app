<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បង្កើតការកក់ថ្មី</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>
            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <div class="p-8 grid grid-cols-2 gap-6">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">ជ្រើសរើសសណ្ឋាគារ</label>
                        <select name="hotel_id" required class="w-full px-4 py-3 rounded-2xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                            @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">ជ្រើសរើសអតិថិជន</label>
                        <select name="user_id" class="w-full px-4 py-3 rounded-2xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                            <option value="">ភ្ញៀវក្រៅប្រព័ន្ធ (Walk-in)</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">ថ្ងៃ Check-in</label>
                        <input type="date" name="check_in" required class="w-full px-4 py-3 rounded-2xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">ថ្ងៃ Check-out</label>
                        <input type="date" name="check_out" required class="w-full px-4 py-3 rounded-2xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">តម្លៃសរុប ($)</label>
                        <input type="number" step="0.01" name="total_price" required class="w-full px-4 py-3 rounded-2xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white font-bold text-lg">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">ស្ថានភាព</label>
                        <select name="status" class="w-full px-4 py-3 rounded-2xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                        </select>
                    </div>
                </div>
                <div class="px-8 py-6 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3 border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 text-gray-500 font-bold hover:text-gray-700 transition-colors">បោះបង់</button>
                    <button type="submit" class="px-10 py-3 bg-gray-900 dark:bg-white dark:text-black text-white rounded-2xl font-black shadow-lg hover:scale-105 transition-all">បង្កើតការកក់</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showEditModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-md relative border dark:border-gray-800">
            <div class="px-6 py-4 border-b dark:border-gray-800 flex justify-between items-center">
                <h3 class="font-black dark:text-white italic">កែប្រែការកក់: <span x-text="currentBooking.booking_code" class="text-blue-500"></span></h3>
                <button @click="showEditModal = false" class="text-gray-400 text-2xl">&times;</button>
            </div>
            <form :action="`{{ url('admin/bookings') }}/${currentBooking.id}`" method="POST">
                @csrf @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">បច្ចុប្បន្នភាពស្ថានភាព</label>
                        <select name="status" x-model="currentBooking.status" class="w-full px-4 py-4 rounded-2xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white font-bold">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase">Check-in</label>
                            <input type="date" name="check_in" x-model="currentBooking.check_in" class="w-full px-3 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase">Check-out</label>
                            <input type="date" name="check_out" x-model="currentBooking.check_out" class="w-full px-3 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6 flex justify-end gap-2 border-t dark:border-gray-800">
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black shadow-xl shadow-blue-500/20 transition-all hover:bg-blue-700">ធ្វើបច្ចុប្បន្នភាព</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showDetailModal" class="fixed inset-0 z-[70] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-md" @click="showDetailModal = false"></div>
        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] shadow-2xl w-full max-w-md relative overflow-hidden border dark:border-gray-800">
            <div class="bg-blue-600 p-8 text-white text-center">
                <div class="mb-2 opacity-80 text-xs font-black uppercase tracking-[0.3em]">វិក្កយបត្រការកក់</div>
                <div class="text-3xl font-black tracking-tighter" x-text="'#' + currentBooking.booking_code"></div>
            </div>
            <div class="p-8">
                <div class="flex justify-between mb-6">
                    <div class="text-left">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">អតិថិជន</p>
                        <p class="font-black dark:text-white text-lg" x-text="currentBooking.user?.name || 'ភ្ញៀវក្រៅប្រព័ន្ធ'"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">ស្ថានភាព</p>
                        <p class="font-black text-blue-500 uppercase text-xs" x-text="currentBooking.status"></p>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-3xl p-6 mb-6 border dark:border-gray-700 border-dashed">
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-center flex-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase">Check-in</p>
                            <p class="font-black dark:text-gray-200" x-text="currentBooking.check_in"></p>
                        </div>
                        <div class="px-4 text-gray-300"><i class="fas fa-hotel"></i></div>
                        <div class="text-center flex-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase">Check-out</p>
                            <p class="font-black dark:text-gray-200" x-text="currentBooking.check_out"></p>
                        </div>
                    </div>
                    <div class="border-t dark:border-gray-700 border-dashed pt-4 flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-500 uppercase">តម្លៃសរុប</span>
                        <span class="text-2xl font-black text-emerald-600" x-text="'$' + parseFloat(currentBooking.total_price).toFixed(2)"></span>
                    </div>
                </div>
                <button @click="showDetailModal = false" class="w-full py-4 bg-gray-900 dark:bg-white dark:text-black text-white rounded-2xl font-black transition-all active:scale-95">បិទត្រឡប់ទៅវិញ</button>

                <div class="p-6">
                    <a :href="`{{ url('bookings/invoice') }}/${currentBooking.id}`"
                        class="w-full mb-3 py-3 bg-blue-600 text-white rounded-2xl font-black flex items-center justify-center gap-2 hover:bg-blue-700 transition-all">
                        <i class="fas fa-file-pdf"></i> ទាញយកវិក្កយបត្រ (PDF)
                    </a>

                    @if(isset($booking))
                    <a href="{{ route('bookings.invoice', $booking->id) }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all">
                        <i class="fas fa-file-pdf"></i> ទាញយកវិក្កយបត្រ
                    </a>
                    @endif

                    <button @click="showDetailModal = false" class="w-full py-3 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-2xl font-bold">បិទ</button>
                </div>
            </div>
        </div>
    </div>
</div>