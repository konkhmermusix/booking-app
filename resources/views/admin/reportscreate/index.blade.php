@extends('layouts.admin')
@section('title', 'បង្កើតរបាយការណ៍តាមតារាង')

@section('content')
<div class="p-4 sm:p-6 space-y-6">

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <i class="fas fa-file-invoice-dollar text-[180px]"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider mb-2">
                    <i class="fas fa-chart-pie"></i> ប្រព័ន្ធបង្កើតរបាយការណ៍ស្វ័យប្រវត្ត
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">បង្កើតរបាយការណ៍តាមតារាងទិន្នន័យ</h1>
                <p class="text-blue-100 text-sm mt-1">ជ្រើសរើសតារាងទិន្នន័យ កាលបរិច្ឆេទ (ថ្ងៃ, សប្ដាហ៍, ខែ, ឆ្នាំ) និងទាញយកជា Excel/PDF ឬ Print</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('reportscreate.export-excel', request()->all()) }}" target="_blank"
                   class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2 active:scale-95">
                    <i class="fas fa-file-excel text-base"></i> ទាញយក Excel
                </a>
                <a href="{{ route('reportscreate.export-pdf', request()->all()) }}" target="_blank"
                   class="px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-rose-500/30 transition-all flex items-center gap-2 active:scale-95">
                    <i class="fas fa-file-pdf text-base"></i> ទាញយក PDF
                </a>
                <button onclick="window.print()" 
                        class="px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-all flex items-center gap-2">
                    <i class="fas fa-print"></i> បោះពុម្ព
                </button>
            </div>
        </div>
    </div>

    {{-- Filter Form Panel --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-800">
        <form method="GET" action="{{ route('reportscreate.index') }}" class="space-y-4" id="reportFilterForm">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                {{-- 1. Table Data Selector --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                        <i class="fas fa-database text-blue-500 mr-1"></i> ជ្រើសរើសតារាងទិន្នន័យ
                    </label>
                    <select name="table" onchange="this.form.submit()"
                            class="w-full h-11 px-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-sm font-semibold transition-all">
                        <option value="room_bookings" {{ $tableType === 'room_bookings' ? 'selected' : '' }}>🏨 ការកក់បន្ទប់សណ្ឋាគារ (Room Bookings)</option>
                        <option value="meeting_bookings" {{ $tableType === 'meeting_bookings' ? 'selected' : '' }}>🏛️ ការកក់សាលប្រជុំ (Meeting Bookings)</option>
                        <option value="payments" {{ $tableType === 'payments' ? 'selected' : '' }}>💳 ប្រតិបត្តិការបង់ប្រាក់ (Payments)</option>
                        <option value="customers" {{ $tableType === 'customers' ? 'selected' : '' }}>👥 អ្នកប្រើប្រាស់ និងអតិថិជន (Customers)</option>
                        <option value="rooms" {{ $tableType === 'rooms' ? 'selected' : '' }}>🔑 ស្ថានភាពបន្ទប់ (Rooms & Inventory)</option>
                    </select>
                </div>

                {{-- 2. Period / Time Range Selector --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                        <i class="fas fa-calendar-alt text-indigo-500 mr-1"></i> កាលបរិច្ឆេទ / រយៈពេល
                    </label>
                    <select name="period" onchange="toggleCustomDates(this.value); this.form.submit()" id="periodSelect"
                            class="w-full h-11 px-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-sm font-semibold transition-all">
                        <option value="today" {{ $period === 'today' ? 'selected' : '' }}>📅 ថ្ងៃនេះ (Today)</option>
                        <option value="yesterday" {{ $period === 'yesterday' ? 'selected' : '' }}>⏪ ម្សិលមិញ (Yesterday)</option>
                        <option value="this_week" {{ $period === 'this_week' ? 'selected' : '' }}>📊 សប្ដាហ៍នេះ (This Week)</option>
                        <option value="last_7_days" {{ $period === 'last_7_days' ? 'selected' : '' }}>🗓️ ៧ ថ្ងៃចុងក្រោយ (Last 7 Days)</option>
                        <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>📈 ខែនេះ (This Month)</option>
                        <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>📉 ខែមុន (Last Month)</option>
                        <option value="this_year" {{ $period === 'this_year' ? 'selected' : '' }}>📆 ឆ្នាំនេះ (This Year)</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>⚙️ កំណត់ថ្ងៃដោយខ្លួនឯង (Custom Date)</option>
                    </select>
                </div>

                {{-- 3. Status Filter --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                        <i class="fas fa-filter text-purple-500 mr-1"></i> ស្ថានភាព (Status)
                    </label>
                    <select name="status" onchange="this.form.submit()"
                            class="w-full h-11 px-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-sm font-semibold transition-all">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>ទាំងអស់ (All Status)</option>
                        @if($tableType === 'customers')
                            <option value="customer" {{ $status === 'customer' ? 'selected' : '' }}>អតិថិជន (Customer)</option>
                            <option value="admin" {{ $status === 'admin' ? 'selected' : '' }}>អ្នកគ្រប់គ្រង (Admin)</option>
                            <option value="staff" {{ $status === 'staff' ? 'selected' : '' }}>បុគ្គលិក (Staff)</option>
                        @elseif($tableType === 'rooms')
                            <option value="available" {{ $status === 'available' ? 'selected' : '' }}>ទំនេរ (Available)</option>
                            <option value="booked" {{ $status === 'booked' ? 'selected' : '' }}>បានកក់ (Booked)</option>
                            <option value="maintenance" {{ $status === 'maintenance' ? 'selected' : '' }}>ជួសជុល (Maintenance)</option>
                        @elseif($tableType === 'payments')
                            <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>បានបង់ (Paid)</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>រង់ចាំ (Pending)</option>
                            <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>បរាជ័យ (Failed)</option>
                        @else
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>រង់ចាំពិនិត្យ (Pending)</option>
                            <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>បានបញ្ជាក់ (Confirmed)</option>
                            <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>បានបញ្ចប់ (Completed)</option>
                            <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>បានបោះបង់ (Cancelled)</option>
                        @endif
                    </select>
                </div>

                {{-- 4. Search Filter --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                        <i class="fas fa-search text-emerald-500 mr-1"></i> ស្វែងរកពាក្យគន្លឹះ
                    </label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="កូដកក់, ឈ្មោះ, លេខទូរស័ព្ទ..."
                               class="w-full h-11 pl-9 pr-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all font-medium">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>

            </div>

            {{-- Custom Date Inputs (shown only if period == custom) --}}
            <div id="customDateFields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100 dark:border-gray-800 {{ $period === 'custom' ? '' : 'hidden' }}">
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">ចាប់ពីថ្ងៃទី (Start Date)</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                           class="w-full h-10 px-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">ដល់ថ្ងៃទី (End Date)</label>
                    <div class="flex gap-2">
                        <input type="date" name="end_date" value="{{ $endDate }}" 
                               class="w-full h-10 px-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white text-sm font-medium">
                        <button type="submit" class="px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-md">
                            អនុវត្ត
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
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
                <p class="text-xs text-gray-400 uppercase font-semibold">ទឹកប្រាក់សរុប (USD)</p>
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">${{ number_format($summary['total_amount_usd'], 2) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold">ទឹកប្រាក់ជាប្រាក់រៀល (KHR)</p>
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
                <i class="fas fa-table text-blue-500"></i> លទ្ធផលទិន្នន័យតារាង ({{ $records->total() }} ជួរ)
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

</div>

<script>
    function toggleCustomDates(value) {
        const fields = document.getElementById('customDateFields');
        if (value === 'custom') {
            fields.classList.remove('hidden');
        } else {
            fields.classList.add('hidden');
        }
    }
</script>
@endsection
