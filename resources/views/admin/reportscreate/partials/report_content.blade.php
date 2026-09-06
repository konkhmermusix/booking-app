{{-- Metric Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-400 uppercase font-semibold">ចំនួនទិន្នន័យសរុប</p>
            <h3 class="text-2xl font-black text-gray-800 dark:text-white mt-1">{{ number_format($summary['total_records']) }} <span class="text-xs font-normal text-gray-400">ជួរ</span></h3>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl font-bold">
            <i class="fas fa-list-ol"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-400 uppercase font-semibold">ទឹកប្រាក់សរុបជាដុល្លារ</p>
            <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">${{ number_format($summary['total_amount_usd'], 2) }}</h3>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold">
            <i class="fas fa-dollar-sign"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-400 uppercase font-semibold">ទឹកប្រាក់សរុបជាប្រាក់រៀល</p>
            <h3 class="text-2xl font-black text-purple-600 dark:text-purple-400 mt-1">៛{{ number_format($summary['total_amount_usd'] * $exchangeRate) }}</h3>
            <p class="text-[10px] text-gray-400 mt-0.5">អត្រាប្តូរប្រាក់: $1 = {{ number_format($exchangeRate) }} ៛</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl font-bold">
            <i class="fas fa-coins"></i>
        </div>
    </div>
</div>

{{-- Data Table Result Preview --}}
<div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
    
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/40">
        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2 text-sm">
            លទ្ធផលទិន្នន័យតារាង ({{ $records->total() }} ជួរ)
        </h3>
        <span class="text-xs text-gray-400">ទំព័រ {{ $records->currentPage() }} នៃ {{ $records->lastPage() }}</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs sm:text-sm">
            <thead>
                <tr class="bg-gray-100/70 dark:bg-gray-800/70 text-gray-600 dark:text-gray-400 uppercase tracking-wider font-bold text-[11px] border-b border-gray-200 dark:border-gray-700">
                    @if($tableType === 'room_bookings')
                        <th class="p-4">កូដកក់</th>
                        <th class="p-4">ឈ្មោះអតិថិជន</th>
                        <th class="p-4">លេខបន្ទប់</th>
                        <th class="p-4">ថ្ងៃចូល - ថ្ងៃចេញ</th>
                        <th class="p-4 text-center">ស្ថានភាព</th>
                        <th class="p-4 text-right">តម្លៃសរុប ($)</th>
                        <th class="p-4 text-right">ថ្ងៃបង្កើត</th>
                    @elseif($tableType === 'meeting_bookings')
                        <th class="p-4">កូដកក់</th>
                        <th class="p-4">ឈ្មោះអតិថិជន</th>
                        <th class="p-4">សាលប្រជុំ</th>
                        <th class="p-4">កាលបរិច្ឆេទ & ម៉ោង</th>
                        <th class="p-4 text-center">ស្ថានភាព</th>
                        <th class="p-4 text-right">តម្លៃសរុប ($)</th>
                        <th class="p-4 text-right">ថ្ងៃបង្កើត</th>
                    @elseif($tableType === 'payments')
                        <th class="p-4">កូដប្រតិបត្តិការ</th>
                        <th class="p-4">ប្រភេទកក់</th>
                        <th class="p-4 text-center">វិធីសាស្ត្រ</th>
                        <th class="p-4 text-center">ស្ថានភាព</th>
                        <th class="p-4 text-right">ចំនួនទឹកប្រាក់ ($)</th>
                        <th class="p-4 text-right">ថ្ងៃបង់ប្រាក់</th>
                    @elseif($tableType === 'customers')
                        <th class="p-4">ID</th>
                        <th class="p-4">ឈ្មោះ</th>
                        <th class="p-4">អ៊ីមែល</th>
                        <th class="p-4">លេខទូរស័ព្ទ</th>
                        <th class="p-4 text-center">តួនាទី</th>
                        <th class="p-4 text-center">IP ចូលចុងក្រោយ</th>
                        <th class="p-4 text-right">ថ្ងៃចុះឈ្មោះ</th>
                    @elseif($tableType === 'rooms')
                        <th class="p-4">លេខបន្ទប់</th>
                        <th class="p-4">ប្រភេទបន្ទប់</th>
                        <th class="p-4">សមត្ថភាព</th>
                        <th class="p-4 text-center">ស្ថានភាព</th>
                        <th class="p-4 text-right">តម្លៃ/យប់ ($)</th>
                        <th class="p-4 text-right">ថ្ងៃបង្កើត</th>
                    @elseif($tableType === 'promotions')
                        <th class="p-4">កូដបញ្ចុះតម្លៃ</th>
                        <th class="p-4">ចំណងជើង</th>
                        <th class="p-4 text-center">ភាគរយ (%)</th>
                        <th class="p-4">កាលបរិច្ឆេទ</th>
                        <th class="p-4 text-center">ស្ថានភាព</th>
                        <th class="p-4 text-right">ថ្ងៃបង្កើត</th>
                    @elseif($tableType === 'reviews')
                        <th class="p-4">ID</th>
                        <th class="p-4">ឈ្មោះអតិថិជន</th>
                        <th class="p-4 text-center">ពិន្ទុ</th>
                        <th class="p-4">មតិយោបល់</th>
                        <th class="p-4 text-right">ថ្ងៃបង្កើត</th>
                    @elseif($tableType === 'contacts')
                        <th class="p-4">ID</th>
                        <th class="p-4">ឈ្មោះ</th>
                        <th class="p-4">អ៊ីមែល/ទូរស័ព្ទ</th>
                        <th class="p-4">ប្រធានបទ & សារ</th>
                        <th class="p-4 text-right">ថ្ងៃផ្ញើ</th>
                    @else
                        <th class="p-4">ID</th>
                        <th class="p-4">ឈ្មោះ/ចំណងជើង</th>
                        <th class="p-4 text-right">ថ្ងៃបង្កើត</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300 font-medium">
                @forelse($records as $row)
                    <tr class="hover:bg-blue-50/40 dark:hover:bg-gray-800/40 transition-colors">
                        @if($tableType === 'room_bookings')
                            <td class="p-4 font-mono font-bold text-blue-600 dark:text-blue-400">{{ $row->booking_code }}</td>
                            <td class="p-4 font-semibold">{{ $row->customer_name ?: ($row->user->name ?? 'ភ្ញៀវកក់ផ្ទាល់') }}</td>
                            <td class="p-4"><span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs font-bold">{{ $row->room->room_number ?? 'N/A' }}</span></td>
                            <td class="p-4 text-xs text-gray-500">{{ $row->check_in }} ➔ {{ $row->check_out }}</td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold 
                                    {{ $row->status === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400' : '' }}
                                    {{ $row->status === 'confirmed' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400' : '' }}
                                    {{ $row->status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' : '' }}
                                    {{ $row->status === 'cancelled' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-400' : '' }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($row->total_price, 2) }}</td>
                            <td class="p-4 text-right text-xs text-gray-400">{{ $row->created_at->format('Y-m-d H:i') }}</td>

                        @elseif($tableType === 'meeting_bookings')
                            <td class="p-4 font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ $row->booking_code }}</td>
                            <td class="p-4 font-semibold">{{ $row->customer_name ?: ($row->user->name ?? 'N/A') }}</td>
                            <td class="p-4"><span class="px-2 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-bold">{{ $row->room->room_number ?? 'សាលប្រជុំ' }}</span></td>
                            <td class="p-4 text-xs text-gray-500">{{ $row->start_date }} ({{ $row->start_time }} - {{ $row->end_time }})</td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold 
                                    {{ $row->status === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400' : '' }}
                                    {{ $row->status === 'confirmed' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400' : '' }}
                                    {{ $row->status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' : '' }}
                                    {{ $row->status === 'cancelled' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-400' : '' }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($row->total_price, 2) }}</td>
                            <td class="p-4 text-right text-xs text-gray-400">{{ $row->created_at->format('Y-m-d H:i') }}</td>

                        @elseif($tableType === 'payments')
                            <td class="p-4 font-mono font-bold text-gray-800 dark:text-gray-200">{{ $row->transaction_id ?: 'TRX-'.$row->id }}</td>
                            <td class="p-4 text-xs">
                                @if($row->hotel_booking_id)
                                    <span class="text-blue-600 dark:text-blue-400 font-semibold">កក់បន្ទប់ ({{ $row->hotelBooking->booking_code ?? '' }})</span>
                                @elseif($row->meeting_booking_id)
                                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold">កក់សាលប្រជុំ ({{ $row->meetingBooking->booking_code ?? '' }})</span>
                                @else
                                    <span>ផ្សេងៗ</span>
                                @endif
                            </td>
                            <td class="p-4 text-center font-bold text-xs uppercase">{{ $row->method }}</td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $row->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($row->amount, 2) }}</td>
                            <td class="p-4 text-right text-xs text-gray-400">{{ $row->created_at->format('Y-m-d H:i') }}</td>

                        @elseif($tableType === 'customers')
                            <td class="p-4 font-mono text-xs text-gray-400">#{{ $row->id }}</td>
                            <td class="p-4 font-bold text-gray-900 dark:text-white">{{ $row->name }}</td>
                            <td class="p-4 text-xs text-gray-500">{{ $row->email }}</td>
                            <td class="p-4 text-xs">{{ $row->phone ?: 'N/A' }}</td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold capitalize bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                    {{ $row->role }}
                                </span>
                            </td>
                            <td class="p-4 text-center font-mono text-xs text-blue-600 dark:text-blue-400">{{ $row->last_login_ip ?: 'N/A' }}</td>
                            <td class="p-4 text-right text-xs text-gray-400">{{ $row->created_at->format('Y-m-d H:i') }}</td>

                        @elseif($tableType === 'rooms')
                            <td class="p-4 font-bold text-blue-600 dark:text-blue-400">បន្ទប់ {{ $row->room_number }}</td>
                            <td class="p-4 font-medium">{{ $row->roomType->name ?? 'N/A' }}</td>
                            <td class="p-4 text-xs text-gray-500">{{ $row->capacity }} នាក់</td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold 
                                    {{ $row->status === 'available' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $row->status === 'booked' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $row->status === 'maintenance' ? 'bg-rose-100 text-rose-700' : '' }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($row->price_per_night, 2) }}</td>
                            <td class="p-4 text-right text-xs text-gray-400">{{ $row->created_at->format('Y-m-d H:i') }}</td>

                        @elseif($tableType === 'promotions')
                            <td class="p-4 font-mono font-bold text-rose-600 dark:text-rose-400">{{ $row->code }}</td>
                            <td class="p-4 font-bold">{{ $row->title }}</td>
                            <td class="p-4 text-center font-bold text-emerald-600">{{ $row->discount_percentage }}%</td>
                            <td class="p-4 text-xs text-gray-500">{{ $row->start_date }} ➔ {{ $row->end_date }}</td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $row->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $row->is_active ? 'សកម្ម' : 'អសកម្ម' }}
                                </span>
                            </td>
                            <td class="p-4 text-right text-xs text-gray-400">{{ $row->created_at->format('Y-m-d H:i') }}</td>

                        @elseif($tableType === 'reviews')
                            <td class="p-4 font-mono text-xs text-gray-400">#{{ $row->id }}</td>
                            <td class="p-4 font-bold">{{ $row->user->name ?? 'N/A' }}</td>
                            <td class="p-4 text-center font-bold text-amber-500">⭐ {{ $row->rating }} / 5</td>
                            <td class="p-4 text-xs text-gray-600 dark:text-gray-300 max-w-xs truncate">{{ $row->comment }}</td>
                            <td class="p-4 text-right text-xs text-gray-400">{{ $row->created_at->format('Y-m-d H:i') }}</td>

                        @elseif($tableType === 'contacts')
                            <td class="p-4 font-mono text-xs text-gray-400">#{{ $row->id }}</td>
                            <td class="p-4 font-bold">{{ $row->name }}</td>
                            <td class="p-4 text-xs">{{ $row->email }} <br> <span class="text-gray-400">{{ $row->phone }}</span></td>
                            <td class="p-4 text-xs"><span class="font-semibold">{{ $row->subject }}</span>: <span class="text-gray-500">{{ $row->message }}</span></td>
                            <td class="p-4 text-right text-xs text-gray-400">{{ $row->created_at->format('Y-m-d H:i') }}</td>

                        @else
                            <td class="p-4 font-mono text-xs text-gray-400">#{{ $row->id }}</td>
                            <td class="p-4 font-bold">{{ $row->title ?: ($row->name ?: ($row->room_number ?? 'Item #'.$row->id)) }}</td>
                            <td class="p-4 text-right text-xs text-gray-400">{{ isset($row->created_at) ? $row->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-400 text-sm">
                            <i class="fas fa-folder-open text-4xl mb-2 block"></i>
                            មិនមានទិន្នន័យត្រូវគ្នានឹងការចម្រោះនេះទេ!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Links --}}
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/40">
        {{ $records->links() }}
    </div>
</div>
