@extends('layouts.admin')
@section('title', 'កែសម្រួលការកក់')

@section('content')
<div class="p-2 sm:p-2 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold dark:text-white">កែសម្រួលការកក់បន្ទប់៖ {{ $booking->booking_code }}</h2>
            <p class="text-xs text-gray-400">Modify the reservation details in the system</p>
        </div>
        <a href="{{ route('bookings.index') }}" class="h-10 px-4 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-750 text-gray-600 dark:text-gray-300 rounded-xl text-sm font-bold flex items-center gap-2 transition-all">
            <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Select -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ជ្រើសរើសអតិថិជន <span class="text-rose-500">*</span></label>
                    <select name="customer_id" class="w-full h-11 px-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all @error('customer_id') border-rose-500 focus:ring-rose-500/50 @enderror">
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id', $booking->customer_id) == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} ({{ $customer->phone ?? 'គ្មានលេខទូរស័ព្ទ' }})
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Room Select -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ជ្រើសរើសបន្ទប់ <span class="text-rose-500">*</span></label>
                    <select name="room_id" id="room_select" class="w-full h-11 px-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all @error('room_id') border-rose-500 focus:ring-rose-500/50 @enderror">
                        @foreach($rooms as $room)
                        <option value="{{ $room->id }}" data-price="{{ $room->roomType->price ?? 0 }}" {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}>
                            បន្ទប់ {{ $room->room_number }} ({{ $room->roomType->name ?? 'N/A' }} - ${{ number_format($room->roomType->price ?? 0, 2) }}/យប់)
                        </option>
                        @endforeach
                    </select>
                    @error('room_id')
                    <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Check In Date -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ថ្ងៃចូលស្នាក់នៅ <span class="text-rose-500">*</span></label>
                    <input type="date" name="check_in_date" id="check_in_date" value="{{ old('check_in_date', $booking->check_in_date ? $booking->check_in_date->format('Y-m-d') : '') }}"
                        class="w-full h-11 px-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all @error('check_in_date') border-rose-500 focus:ring-rose-500/50 @enderror">
                    @error('check_in_date')
                    <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Check Out Date -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ថ្ងៃចាកចេញ <span class="text-rose-500">*</span></label>
                    <input type="date" name="check_out_date" id="check_out_date" value="{{ old('check_out_date', $booking->check_out_date ? $booking->check_out_date->format('Y-m-d') : '') }}"
                        class="w-full h-11 px-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all @error('check_out_date') border-rose-500 focus:ring-rose-500/50 @enderror">
                    @error('check_out_date')
                    <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Number Of Guests -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ចំនួនភ្ញៀវ <span class="text-rose-500">*</span></label>
                    <input type="number" name="number_of_guests" value="{{ old('number_of_guests', $booking->number_of_guests) }}" min="1"
                        class="w-full h-11 px-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all @error('number_of_guests') border-rose-500 focus:ring-rose-500/50 @enderror">
                    @error('number_of_guests')
                    <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Price -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">តម្លៃសរុប ($) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="total_price" id="total_price" value="{{ old('total_price', $booking->total_price) }}"
                        class="w-full h-11 px-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all @error('total_price') border-rose-500 focus:ring-rose-500/50 @enderror">
                    @error('total_price')
                    <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Status -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ស្ថានភាពការបង់ប្រាក់ <span class="text-rose-500">*</span></label>
                    <select name="payment_status" class="w-full h-11 px-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all @error('payment_status') border-rose-500 focus:ring-rose-500/50 @enderror">
                        <option value="pending" {{ old('payment_status', $booking->payment_status) === 'pending' ? 'selected' : '' }}>រង់ចាំ (Pending)</option>
                        <option value="paid" {{ old('payment_status', $booking->payment_status) === 'paid' ? 'selected' : '' }}>បានបង់រួច (Paid)</option>
                        <option value="failed" {{ old('payment_status', $booking->payment_status) === 'failed' ? 'selected' : '' }}>បរាជ័យ (Failed)</option>
                    </select>
                    @error('payment_status')
                    <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Booking Status -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ស្ថានភាពការកក់ <span class="text-rose-500">*</span></label>
                    <select name="booking_status" class="w-full h-11 px-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all @error('booking_status') border-rose-500 focus:ring-rose-500/50 @enderror">
                        <option value="pending" {{ old('booking_status', $booking->booking_status) === 'pending' ? 'selected' : '' }}>រង់ចាំពិនិត្យ (Pending)</option>
                        <option value="confirmed" {{ old('booking_status', $booking->booking_status) === 'confirmed' ? 'selected' : '' }}>បានបញ្ជាក់ (Confirmed)</option>
                        <option value="completed" {{ old('booking_status', $booking->booking_status) === 'completed' ? 'selected' : '' }}>រួចរាល់ (Completed)</option>
                        <option value="cancelled" {{ old('booking_status', $booking->booking_status) === 'cancelled' ? 'selected' : '' }}>បានបោះបង់ (Cancelled)</option>
                    </select>
                    @error('booking_status')
                    <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">មតិផ្សេងៗ</label>
                <textarea name="notes" rows="4" placeholder="មតិផ្សេងៗរបស់អតិថិជន..."
                    class="w-full p-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all @error('notes') border-rose-500 focus:ring-rose-500/50 @enderror">{{ old('notes', $booking->notes) }}</textarea>
                @error('notes')
                <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-150 dark:border-gray-800">
                <a href="{{ route('bookings.index') }}" class="h-11 px-6 bg-gray-100 hover:bg-gray-250 dark:bg-gray-800 dark:hover:bg-gray-750 text-gray-600 dark:text-gray-300 rounded-xl text-sm font-bold flex items-center justify-center transition-all">
                    បោះបង់
                </a>
                <button type="submit" class="h-11 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold flex items-center justify-center gap-2 transition-all active:scale-95">
                    <i class="fas fa-save"></i> ធ្វើបច្ចុប្បន្នភាព
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roomSelect = document.getElementById('room_select');
        const checkInInput = document.getElementById('check_in_date');
        const checkOutInput = document.getElementById('check_out_date');
        const totalPriceInput = document.getElementById('total_price');

        function calculateTotalPrice() {
            const selectedOption = roomSelect.options[roomSelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) return;

            const roomPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const checkInVal = checkInInput.value;
            const checkOutVal = checkOutInput.value;

            if (checkInVal && checkOutVal) {
                const date1 = new Date(checkInVal);
                const date2 = new Date(checkOutVal);
                const diffTime = date2 - date1;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays > 0) {
                    totalPriceInput.value = (roomPrice * diffDays).toFixed(2);
                } else {
                    totalPriceInput.value = '';
                }
            }
        }

        roomSelect.addEventListener('change', calculateTotalPrice);
        checkInInput.addEventListener('change', calculateTotalPrice);
        checkOutInput.addEventListener('change', calculateTotalPrice);
    });
</script>
@endsection
