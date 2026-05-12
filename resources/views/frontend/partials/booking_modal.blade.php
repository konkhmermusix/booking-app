<div id="bookingModal" class="fixed inset-0 hidden items-center justify-center bg-black/60 z-100 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl w-full max-w-md shadow-2xl transform transition-all scale-95 opacity-0 duration-300" id="modalContainer">
        <h2 class="text-2xl font-bold mb-6 dark:text-white">បញ្ជាក់ការកក់</h2>

        <form id="bookingForm">
            @csrf
            <input type="hidden" id="room_type_id" name="room_type_id">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">បន្ទប់ដែលរើស</label>
                    <input type="text" id="modal_room_name" readonly
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">ចំនួនភ្ញៀវ</label>
                    <input type="number" name="guests" value="1" min="1"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]"
                        required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 text-blue-500">ថ្ងៃចូល</label>
                    <input type="date" name="check_in" id="check_in" value="{{ $check_in }}"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]"
                        required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 text-red-500">ថ្ងៃចេញ</label>
                    <input type="date" name="check_out" id="check_out" value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]"
                        required>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeBookingModal()" class="flex-1 bg-gray-100 dark:bg-gray-800 dark:text-white py-3 rounded-2xl font-bold hover:bg-gray-200 transition">បោះបង់</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-2xl font-bold hover:bg-blue-700 shadow-lg transition">យល់ព្រមកក់</button>
            </div>
        </form>
    </div>
</div>


<script>
    document.getElementById("duration").addEventListener("change", function() {

        let days = parseInt(this.value);
        if (!days) return;

        // 1. AUTO SET CHECK-IN = TODAY
        let today = new Date();

        let yyyy = today.getFullYear();
        let mm = String(today.getMonth() + 1).padStart(2, '0');
        let dd = String(today.getDate()).padStart(2, '0');

        let checkInDate = `${yyyy}-${mm}-${dd}`;
        document.getElementById("check_in").value = checkInDate;

        // 2. CALCULATE CHECK-OUT
        let checkOut = new Date();
        checkOut.setDate(checkOut.getDate() + days);

        let y2 = checkOut.getFullYear();
        let m2 = String(checkOut.getMonth() + 1).padStart(2, '0');
        let d2 = String(checkOut.getDate()).padStart(2, '0');

        document.getElementById("check_out").value = `${y2}-${m2}-${d2}`;
    });
</script>