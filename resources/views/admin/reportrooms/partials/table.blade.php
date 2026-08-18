@forelse($bookings as $booking)
@php
    $nights = $booking->check_in && $booking->check_out 
        ? \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) 
        : 1;
    $roomNumStr = $booking->room->room_number ?? 'N/A';
    $roomTypeStr = $booking->room->roomType->name ?? 'បន្ទប់ស្នាក់នៅ';
    $guestName = $booking->customer_name ?: ($booking->user->name ?? 'ភ្ញៀវ Walk-in');

    $statusKhmer = match($booking->status) {
        'pending'   => 'រង់ចាំពិនិត្យ',
        'confirmed' => 'បានបញ្ជាក់',
        'completed' => 'បានបញ្ចប់',
        'cancelled' => 'បានបោះបង់',
        default     => $booking->status
    };

    $statusBadgeClass = match($booking->status) {
        'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-300',
        'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 border-blue-300',
        'cancelled' => 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 border-rose-300',
        default     => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border-amber-300'
    };

    $bType = strtolower($booking->booking_type ?? '');
    $isOnlineSource = ($bType === 'online') || ($booking->user_id && !$booking->customer_name);
    
    if ($bType === 'online' || $isOnlineSource) {
        $sourceBadge = 'អនឡាញ';
        $sourceClass = 'bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300 border-purple-200';
    } else {
        $sourceBadge = 'កក់ផ្ទាល់';
        $sourceClass = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-200';
    }
@endphp
<tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/30 transition-colors">
    <td class="p-4 font-mono font-bold text-blue-600 dark:text-blue-400 text-xs">{{ $booking->booking_code }}</td>
    <td class="p-4 font-semibold text-gray-800 dark:text-white">
        {{ $guestName }}
        <span class="block text-[11px] text-gray-400 font-normal font-mono">{{ $booking->customer_phone ?: ($booking->user->phone ?? 'គ្មានលេខទូរស័ព្ទ') }}</span>
    </td>
    <td class="p-4">
        <span class="font-bold text-gray-800 dark:text-gray-200">No. {{ $roomNumStr }}</span>
        <span class="block text-xs text-gray-400">{{ $roomTypeStr }}</span>
    </td>
    <td class="p-4 text-xs font-mono">
        {{ $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : 'N/A' }} <i class="fas fa-arrow-right text-[10px] text-gray-400 mx-1 print-hide"></i> {{ $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : 'N/A' }}
    </td>
    <td class="p-4 text-center font-bold text-xs">{{ $nights }} យប់</td>
    <td class="p-4 text-center">
        <span class="px-2.5 py-1 rounded-md text-[11px] font-bold border {{ $sourceClass }}">
            {{ $sourceBadge }}
        </span>
    </td>
    <td class="p-4 text-center">
        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusBadgeClass }}">
            {{ $statusKhmer }}
        </span>
    </td>
    <td class="p-4 text-right font-black text-emerald-500 text-base">${{ number_format($booking->total_price, 2) }}</td>
    <td class="p-4 text-center print-hide">
        <div class="flex items-center justify-center gap-1">
            <button @click="selectedBooking = {{ json_encode($booking) }}; detailModalOpen = true" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/50 rounded-xl text-xs transition" title="មើលព័ត៌មានលម្អិត">
                <i class="fas fa-eye"></i>
            </button>
            @if($booking->status == 'cancelled')
                <button @click="cancelReason = '{{ addslashes($booking->notes ?? 'អតិថិជនបានបោះបង់ការកក់តាមប្រព័ន្ធ') }}'; cancelModalOpen = true" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-xl text-xs transition" title="មូលហេតុបោះបង់">
                    <i class="fas fa-info-circle"></i>
                </button>
            @endif
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="9" class="text-center p-12 text-gray-400">មិនទាន់មានទិន្នន័យកក់បន្ទប់នៅក្នុងប្រព័ន្ធទេ</td>
</tr>
@endforelse
