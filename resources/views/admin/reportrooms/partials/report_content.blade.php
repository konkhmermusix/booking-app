@php
    $pendingCount = $bookingStatus->where('status', 'pending')->first()->total ?? 0;
    $confirmedCount = $bookingStatus->where('status', 'confirmed')->first()->total ?? 0;
    $completedCount = $bookingStatus->where('status', 'completed')->first()->total ?? 0;
    $cancelledCount = $bookingStatus->where('status', 'cancelled')->first()->total ?? 0;
    $totalBookingsCount = $pendingCount + $confirmedCount + $completedCount + $cancelledCount;

    $available = $roomStatus->where('status', 'available')->first()->total ?? 0;
    $booked = $roomStatus->where('status', 'booked')->first()->total ?? 0;
    $maintenance = $roomStatus->where('status', 'maintenance')->first()->total ?? 0;
@endphp

<!-- Status KPI Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ការកក់សរុប</span>
            <h3 class="text-2xl font-black text-gray-800 dark:text-white mt-0.5">{{ number_format($totalBookingsCount) }}</h3>
            <p class="text-[10px] text-gray-400 mt-0.5">គ្រប់ស្ថានភាពកក់</p>
        </div>
        <div class="w-10 h-10 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-calendar-alt"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">បានបញ្ជាក់ & ជោគជ័យ</span>
            <h3 class="text-2xl font-black text-emerald-500 mt-0.5">{{ number_format($confirmedCount + $completedCount) }}</h3>
            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 mt-0.5">បានបញ្ជាក់ + បានបញ្ចប់</p>
        </div>
        <div class="w-10 h-10 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">បានបោះបង់ / No-Show</span>
            <h3 class="text-2xl font-black text-rose-500 mt-0.5">{{ number_format($cancelledCount) }}</h3>
            <p class="text-[10px] text-rose-500/80 mt-0.5">បោះបង់ការកក់</p>
        </div>
        <div class="w-10 h-10 bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-times-circle"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">អត្រាទំនេរ/ជាប់ភ្ញៀវ</span>
            <h3 class="text-2xl font-black text-indigo-500 mt-0.5">{{ $occupancyRate }}%</h3>
            <p class="text-[10px] text-indigo-400 mt-0.5">{{ $bookedRoomsCount }} ពី {{ $totalRoomsCount }} បន្ទប់</p>
        </div>
        <div class="w-10 h-10 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-chart-pie"></i>
        </div>
    </div>
</div>

<!-- Charts & Popular Rooms Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col justify-between">
        <h4 class="text-xs font-bold text-gray-800 dark:text-white mb-2">ក្រាហ្វិកស្ថានភាពបន្ទប់ជាក់ស្តែង</h4>
        <div class="h-[220px] flex items-center justify-center">
            <canvas id="roomStatusChart"></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col justify-between">
        <h4 class="text-xs font-bold text-gray-800 dark:text-white mb-2">ស្ថានភាពនៃការកក់បន្ទប់សរុប</h4>
        <div class="h-[220px] flex items-center justify-center">
            <canvas id="bookingStatusChart"></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <h4 class="text-xs font-bold text-gray-800 dark:text-white mb-3">ប្រភេទបន្ទប់ពេញនិយមបំផុតទាំង ៥</h4>
        <div class="space-y-3">
            @forelse($popularRooms as $index => $roomType)
                <div>
                    <div class="flex justify-between items-center text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                        <span>{{ $index + 1 }}. {{ $roomType->name }}</span>
                        <span class="text-blue-500 font-bold">{{ number_format($roomType->total_booked) }} ដង</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden">
                        @php
                            $maxBooked = $popularRooms->first()->total_booked ?? 1;
                            $percentage = ($roomType->total_booked / $maxBooked) * 100;
                        @endphp
                        <div class="bg-blue-500 h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 text-center py-10">មិនទាន់មានទិន្នន័យកក់បន្ទប់សណ្ឋាគារឡើយ</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Data Table Section -->
<div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
    <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <div>
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                បញ្ជីប្រវត្តិនៃការកក់បន្ទប់សណ្ឋាគារ
            </h3>
            <p class="text-[10px] text-gray-400">សណ្ឋាគារ ភីអេនធី ផាលេស</p>
        </div>
        <span class="text-xs text-gray-400 font-semibold">សរុប {{ $bookings->total() }} កំណត់ត្រា</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-800/50 text-[11px] font-bold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-800 uppercase tracking-wider">
                    <th class="p-3.5">កូដកក់</th>
                    <th class="p-3.5">ឈ្មោះអតិថិជន</th>
                    <th class="p-3.5">បន្ទប់ / ប្រភេទ</th>
                    <th class="p-3.5">ថ្ងៃចូល - ថ្ងៃចេញ</th>
                    <th class="p-3.5 text-center">ចំនួនយប់</th>
                    <th class="p-3.5 text-center">ប្រភពកក់</th>
                    <th class="p-3.5 text-center">ស្ថានភាព</th>
                    <th class="p-3.5 text-right">តម្លៃសរុប</th>
                    <th class="p-3.5 text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="text-xs text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($bookings as $booking)
                @php
                    $nights = $booking->check_in && $booking->check_out 
                        ? \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) 
                        : 1;
                    $roomNumStr = $booking->room->room_number ?? 'N/A';
                    $roomTypeStr = $booking->room->roomType->name ?? 'បន្ទប់ស្នាក់នៅ';
                    $guestName = $booking->customer_name ?: ($booking->user->name ?? 'ភ្ញៀវកក់ផ្ទាល់');

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
                    <td class="p-3.5 font-mono font-bold text-blue-600 dark:text-blue-400 text-[11px]">{{ $booking->booking_code }}</td>
                    <td class="p-3.5 font-semibold text-gray-800 dark:text-white">
                        {{ $guestName }}
                        <span class="block text-[10px] text-gray-400 font-normal font-mono">{{ $booking->customer_phone ?: ($booking->user->phone ?? 'គ្មានលេខទូរស័ព្ទ') }}</span>
                    </td>
                    <td class="p-3.5">
                        <span class="font-bold text-gray-800 dark:text-gray-200">No. {{ $roomNumStr }}</span>
                        <span class="block text-[10px] text-gray-400">{{ $roomTypeStr }}</span>
                    </td>
                    <td class="p-3.5 text-[11px] font-mono">
                        {{ $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : 'N/A' }} <i class="fas fa-arrow-right text-[9px] text-gray-400 mx-1"></i> {{ $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="p-3.5 text-center font-bold text-xs">{{ $nights }} យប់</td>
                    <td class="p-3.5 text-center">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $sourceClass }}">
                            {{ $sourceBadge }}
                        </span>
                    </td>
                    <td class="p-3.5 text-center">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $statusBadgeClass }}">
                            {{ $statusKhmer }}
                        </span>
                    </td>
                    <td class="p-3.5 text-right font-black text-emerald-500 text-sm">${{ number_format($booking->total_price, 2) }}</td>
                    <td class="p-3.5 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <button @click="currentBooking = {{ json_encode($booking) }}; showDetailModal = true" class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/50 rounded-lg transition" title="មើលព័ត៌មានលម្អិត">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($booking->status == 'cancelled')
                                <button @click="cancelReason = '{{ addslashes($booking->notes ?? 'អតិថិជនបានបោះបង់ការកក់តាមប្រព័ន្ធ') }}'; showCancelModal = true" class="p-1.5 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-lg transition" title="មូលហេតុបោះបង់">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center p-12 text-gray-400 text-xs">មិនទាន់មានទិន្នន័យកក់បន្ទប់នៅក្នុងប្រព័ន្ធទេ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-100 dark:border-gray-800 pagination">
        {{ $bookings->links() }}
    </div>
</div>

<script>
function renderReportCharts() {
    const roomEl = document.getElementById('roomStatusChart');
    if (roomEl) {
        new Chart(roomEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['បន្ទប់ទំនេរ', 'មានភ្ញៀវ', 'ជួសជុល'],
                datasets: [{
                    data: [{{ $available }}, {{ $booked }}, {{ $maintenance }}],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    const bookingEl = document.getElementById('bookingStatusChart');
    if (bookingEl) {
        new Chart(bookingEl.getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['រង់ចាំពិនិត្យ', 'បានបញ្ជាក់', 'បានបញ្ចប់', 'បានបោះបង់'],
                datasets: [{
                    data: [{{ $pendingCount }}, {{ $confirmedCount }}, {{ $completedCount }}, {{ $cancelledCount }}],
                    backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
}

// Initial render
renderReportCharts();
</script>
