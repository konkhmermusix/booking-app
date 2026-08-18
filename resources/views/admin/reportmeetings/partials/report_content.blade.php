<!-- KPI Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ការកក់សាលសរុប</span>
            <h3 class="text-2xl font-black text-gray-800 dark:text-white mt-0.5">{{ number_format($meetingStats->total_meetings ?? 0) }}</h3>
            <p class="text-[10px] text-gray-400 mt-0.5">ការកក់សាលប្រជុំទាំងអស់</p>
        </div>
        <div class="w-10 h-10 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-building"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ចំណូលសរុបពីសាល</span>
            <h3 class="text-2xl font-black text-emerald-500 mt-0.5">${{ number_format($meetingStats->total_revenue ?? 0, 2) }}</h3>
            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 mt-0.5">ចំណូលបានទទួលពីសាលប្រជុំ</p>
        </div>
        <div class="w-10 h-10 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-dollar-sign"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">អ្នកចូលរួមសរុប</span>
            <h3 class="text-2xl font-black text-blue-500 mt-0.5">{{ number_format($meetingStats->total_attendees ?? 0) }} នាក់</h3>
            <p class="text-[10px] text-blue-400 mt-0.5">ប៉ាន់ស្មានអ្នកចូលរួម</p>
        </div>
        <div class="w-10 h-10 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-users-cog"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ថ្ងៃមមាញឹកបំផុត (Peak)</span>
            <h3 class="text-lg font-black text-amber-500 mt-0.5">{{ $peakDayName }}</h3>
            <p class="text-[10px] text-amber-400 mt-0.5">អត្រាកក់ខ្ពស់បំផុត</p>
        </div>
        <div class="w-10 h-10 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-fire"></i>
        </div>
    </div>
</div>

<!-- Table View Section -->
<div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
    <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <div>
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-list-alt text-indigo-500"></i> បញ្ជីប្រវត្តិនៃការកក់សាលប្រជុំ
            </h3>
            <p class="text-[10px] text-gray-400">សណ្ឋាគារ ភីអេនធី ផាលេស (PNT Palace Hotel)</p>
        </div>
        <span class="text-xs text-gray-400 font-semibold">សរុប {{ $meetingBookings->total() }} កំណត់ត្រា</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-800/50 text-[11px] font-bold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-800 uppercase tracking-wider">
                    <th class="p-3.5">កូដកក់</th>
                    <th class="p-3.5">អ្នកកក់ / ស្ថាប័ន</th>
                    <th class="p-3.5">ឈ្មោះសាលប្រជុំ</th>
                    <th class="p-3.5">កាលបរិច្ឆេទ & ម៉ោង</th>
                    <th class="p-3.5 text-center">សេវាកម្មបន្ថែម</th>
                    <th class="p-3.5 text-center">អ្នកចូលរួម</th>
                    <th class="p-3.5 text-center">ប្រភពកក់</th>
                    <th class="p-3.5 text-center">ស្ថានភាព</th>
                    <th class="p-3.5 text-right">តម្លៃសរុប</th>
                    <th class="p-3.5 text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="text-xs text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($meetingBookings as $booking)
                @php
                    $clientName = $booking->customer_name ?: ($booking->user->name ?? 'ភ្ញៀវកក់ផ្ទាល់');
                    $roomName = $booking->room->room_number ?? 'សាលប្រជុំទូទៅ';

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
                    <td class="p-3.5 font-mono font-bold text-indigo-600 dark:text-indigo-400 text-[11px]">{{ $booking->booking_code }}</td>
                    <td class="p-3.5 font-semibold text-gray-800 dark:text-white">
                        {{ $clientName }}
                        <span class="block text-[10px] text-gray-400 font-normal font-mono">{{ $booking->customer_phone ?: ($booking->user->phone ?? 'គ្មានលេខទូរស័ព្ទ') }}</span>
                    </td>
                    <td class="p-3.5 font-bold text-gray-800 dark:text-gray-200">{{ $roomName }}</td>
                    <td class="p-3.5 text-[11px] font-mono">
                        {{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') : 'N/A' }}
                        <span class="text-gray-400 block text-[10px]">({{ $booking->start_time }} - {{ $booking->end_time }})</span>
                    </td>
                    <td class="p-3.5 text-center">
                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded text-[10px] font-medium">
                            {{ $booking->setup_style ?: 'Standard' }}
                        </span>
                    </td>
                    <td class="p-3.5 text-center font-bold text-xs">{{ $booking->attendees_count ?: 0 }} នាក់</td>
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
                        <button @click="selectedMeeting = {{ json_encode($booking) }}; detailModalOpen = true" class="p-1.5 text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 rounded-lg transition" title="មើលព័ត៌មានលម្អិត">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center p-12 text-gray-400 text-xs">មិនទាន់មានប្រវត្តិកក់សាលប្រជុំទេ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-100 dark:border-gray-800 pagination">
        {{ $meetingBookings->links() }}
    </div>
</div>

