@extends('layouts.admin')
@section('title', 'លទ្ធផលស្វែងរក')
@section('content')
<div class="p-2 sm:p-4">
    <!-- Header Card -->
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold dark:text-white">លទ្ធផលស្វែងរក</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Global Search Results</p>
        </div>

        <form action="{{ route('admin.global-search') }}" method="GET" class="w-full md:w-96 flex items-center bg-gray-50 dark:bg-gray-800 px-3 py-2 rounded-xl focus-within:ring-2 focus-within:ring-blue-500/50 transition-all border border-transparent">
            <i class="fas fa-search text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="ស្វែងរក..." class="bg-transparent border-none outline-none text-sm ml-2 w-full dark:text-white">
            @if($search)
                <a href="{{ route('admin.global-search') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="fas fa-times-circle"></i>
                </a>
            @endif
        </form>
    </div>

    @if(!$search || (count($hotelBookings) == 0 && count($meetingBookings) == 0 && count($rooms) == 0 && count($users) == 0 && count($posts) == 0))
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-900 p-12 rounded-2xl shadow-sm text-center">
            <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-md font-bold text-gray-700 dark:text-gray-200 mb-1">
                @if(!$search)
                    សូមបញ្ចូលពាក្យគន្លឹះដើម្បីស្វែងរក
                @else
                    មិនមានលទ្ធផលសម្រាប់ការស្វែងរក "{{ $search }}" ទេ
                @endif
            </h3>
            <p class="text-xs text-gray-400">សូមព្យាយាមស្វែងរកលេខកូដកក់, ឈ្មោះអតិថិជន, លេខបន្ទប់ ឬអត្ថបទផ្សេងទៀត</p>
        </div>
    @else
        <!-- Stats Summary -->
        <div class="mb-6 text-sm text-gray-500 dark:text-gray-400">
            រកឃើញទិន្នន័យសរុប <span class="font-bold text-blue-600 dark:text-blue-400">{{ count($hotelBookings) + count($meetingBookings) + count($rooms) + count($users) + count($posts) }}</span> មុខ
        </div>

        <div class="space-y-8">
            <!-- 1. Hotel Bookings Results -->
            @if(count($hotelBookings) > 0)
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b dark:border-gray-800 flex items-center gap-3">
                        <i class="fas fa-bed text-blue-500"></i>
                        <h3 class="font-bold dark:text-white">ការកក់បន្ទប់ស្នាក់នៅ ({{ count($hotelBookings) }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">កូដកក់</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">អតិថិជន</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">បន្ទប់</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">កក់សម្រាប់ថ្ងៃ</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">តម្លៃសរុប</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">ស្ថានភាព</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase text-right">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                @foreach($hotelBookings as $booking)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="px-6 py-4 font-bold text-blue-600 dark:text-blue-400 text-sm">{{ $booking->booking_code }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold dark:text-white">{{ $booking->customer_name ?? ($booking->user->name ?? 'មិនស្គាល់') }}</div>
                                            <div class="text-xs text-gray-400">{{ $booking->customer_phone ?? ($booking->user->phone ?? '-') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm dark:text-gray-300">
                                            {{ $booking->room->room_number ?? 'មិនទាន់កំណត់' }}
                                            <span class="text-xs text-gray-400 block">{{ $booking->room->roomType->name ?? '' }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $booking->check_in }} ដល់ {{ $booking->check_out }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                            ${{ number_format($booking->total_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-xs font-bold uppercase">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold
                                                @if($booking->status === 'approved') text-green-700 bg-green-50 dark:bg-green-950/20
                                                @elseif($booking->status === 'pending') text-yellow-700 bg-yellow-50 dark:bg-yellow-950/20
                                                @else text-red-700 bg-red-50 dark:bg-red-950/20 @endif">
                                                {{ $booking->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end gap-1">
                                                <!-- Link to Bookings index with search query to open/edit booking -->
                                                <a href="{{ route('bookings.index') }}?search={{ urlencode($booking->booking_code) }}" title="គ្រប់គ្រងការកក់" class="p-2 text-gray-400 hover:text-blue-500">
                                                    <i class="fas fa-cog text-sm"></i>
                                                </a>
                                                <!-- Delete Booking form -->
                                                <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete(this.form)"
                                                        class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                                                        title="លុប">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- 2. Meeting Bookings Results -->
            @if(count($meetingBookings) > 0)
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b dark:border-gray-800 flex items-center gap-3">
                        <i class="fas fa-users text-blue-500"></i>
                        <h3 class="font-bold dark:text-white">ការកក់សាលប្រជុំ ({{ count($meetingBookings) }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">កូដកក់</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">អតិថិជន</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">សាលប្រជុំ</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">កាលបរិច្ឆេទ</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">តម្លៃសរុប</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">ស្ថានភាព</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase text-right">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                @foreach($meetingBookings as $booking)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="px-6 py-4 font-bold text-blue-600 dark:text-blue-400 text-sm">{{ $booking->booking_code }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold dark:text-white">{{ $booking->customer_name ?? ($booking->user->name ?? 'មិនស្គាល់') }}</div>
                                            <div class="text-xs text-gray-400">{{ $booking->customer_phone ?? ($booking->user->phone ?? '-') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm dark:text-gray-300">
                                            {{ $booking->room->room_number ?? 'មិនទាន់កំណត់' }}
                                            <span class="text-xs text-gray-400 block">{{ $booking->room->roomType->name ?? '' }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $booking->start_date ? $booking->start_date->format('Y-m-d') : '' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                            ${{ number_format($booking->total_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-xs font-bold uppercase">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold
                                                @if($booking->status === 'approved') text-green-700 bg-green-50 dark:bg-green-950/20
                                                @elseif($booking->status === 'pending') text-yellow-700 bg-yellow-50 dark:bg-yellow-950/20
                                                @else text-red-700 bg-red-50 dark:bg-red-950/20 @endif">
                                                {{ $booking->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end gap-1">
                                                <!-- Link to Meeting Bookings index with search query -->
                                                <a href="{{ route('meeting-bookings.index') }}?search={{ urlencode($booking->booking_code) }}" title="គ្រប់គ្រងការកក់" class="p-2 text-gray-400 hover:text-blue-500">
                                                    <i class="fas fa-cog text-sm"></i>
                                                </a>
                                                <!-- Delete Meeting Booking form -->
                                                <form action="{{ route('meeting-bookings.destroy', $booking->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete(this.form)"
                                                        class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                                                        title="លុប">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- 3. Rooms Results -->
            @if(count($rooms) > 0)
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b dark:border-gray-800 flex items-center gap-3">
                        <i class="fas fa-door-open text-blue-500"></i>
                        <h3 class="font-bold dark:text-white">បន្ទប់ស្នាក់នៅ & សាលប្រជុំ ({{ count($rooms) }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">លេខបន្ទប់</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">សណ្ឋាគារ</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">ប្រភេទ</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">តម្លៃមូលដ្ឋាន</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">ស្ថានភាព</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase text-right">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                @foreach($rooms as $room)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="px-6 py-4 font-bold text-blue-600 dark:text-blue-400 text-sm">{{ $room->room_number }}</td>
                                        <td class="px-6 py-4 text-sm dark:text-gray-300">{{ $room->hotel->name ?? 'មិនស្គាល់' }}</td>
                                        <td class="px-6 py-4 text-sm dark:text-gray-300">{{ $room->roomType->name ?? 'មិនស្គាល់' }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                            ${{ number_format($room->roomType->base_price ?? 0, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-xs font-bold uppercase">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold
                                                @if($room->status === 'available') text-green-700 bg-green-50 dark:bg-green-950/20
                                                @elseif($room->status === 'booked') text-blue-700 bg-blue-50 dark:bg-blue-950/20
                                                @else text-orange-700 bg-orange-50 dark:bg-orange-950/20 @endif">
                                                {{ $room->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('rooms.index') }}?search={{ $room->room_number }}" class="text-blue-500 hover:text-blue-700 font-semibold text-xs">
                                                <i class="fas fa-edit mr-1"></i> គ្រប់គ្រង
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- 4. Users Results -->
            @if(count($users) > 0)
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b dark:border-gray-800 flex items-center gap-3">
                        <i class="fas fa-user-circle text-blue-500"></i>
                        <h3 class="font-bold dark:text-white">គណនីអ្នកប្រើប្រាស់ ({{ count($users) }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">ឈ្មោះ</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">អ៊ីមែល</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">លេខទូរស័ព្ទ</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">តួនាទី</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">ស្ថានភាព</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase text-right">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-xs overflow-hidden">
                                                    @if($user->avatar)
                                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="" class="w-full h-full object-cover">
                                                    @else
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    @endif
                                                </div>
                                                <span class="text-sm font-semibold dark:text-white">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm dark:text-gray-300">{{ $user->email }}</td>
                                        <td class="px-6 py-4 text-sm dark:text-gray-300">{{ $user->phone ?? '-' }}</td>
                                        <td class="px-6 py-4 text-xs font-bold uppercase">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                                @if($user->role === 'admin') text-red-700 bg-red-50 dark:bg-red-950/20
                                                @elseif($user->role === 'staff') text-blue-700 bg-blue-50 dark:bg-blue-950/20
                                                @else text-gray-700 bg-gray-50 dark:bg-gray-800 @endif">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs">
                                            <span class="inline-flex items-center gap-1 font-bold @if($user->status === '1') text-green-500 @else text-red-500 @endif">
                                                <span class="w-1.5 h-1.5 rounded-full @if($user->status === '1') bg-green-500 @else bg-red-500 @endif"></span>
                                                {{ $user->status === '1' ? 'សកម្ម' : 'អសកម្ម' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end gap-1">
                                                <!-- មើលលម្អិត & កែប្រែ: នាំទៅកាន់ទំព័របញ្ជីអ្នកប្រើប្រាស់រួចស្វែងរកឈ្មោះគាត់ ដើម្បីបើក Modal នៅទីនោះ -->
                                                <a href="{{ route('users.index') }}?search={{ urlencode($user->name) }}" title="គ្រប់គ្រងគណនី (កែប្រែ/មើលលម្អិត)" class="p-2 text-gray-400 hover:text-blue-500">
                                                    <i class="fas fa-cog text-sm"></i>
                                                </a>
                                                
                                                <!-- លុបគណនី -->
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete(this.form)"
                                                        class="btn-delete p-2 text-gray-400 hover:text-red-500 transition-colors"
                                                        title="លុប">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- 5. Posts Results -->
            @if(count($posts) > 0)
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b dark:border-gray-800 flex items-center gap-3">
                        <i class="fas fa-newspaper text-blue-500"></i>
                        <h3 class="font-bold dark:text-white">ព័ត៌មាន & អត្ថបទ ({{ count($posts) }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">ចំណងជើង</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">ស្ថានភាព</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">ថ្ងៃបង្កើត</th>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase text-right">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                @foreach($posts as $post)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold dark:text-white">{{ Str::limit($post->title, 60) }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-xs">
                                            <span class="inline-flex items-center gap-1 font-bold @if($post->status === 'published') text-green-500 @else text-gray-500 @endif">
                                                <span class="w-1.5 h-1.5 rounded-full @if($post->status === 'published') bg-green-500 @else bg-gray-500 @endif"></span>
                                                {{ $post->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $post->created_at ? $post->created_at->format('Y-m-d H:i') : '' }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('posts.index') }}?search={{ urlencode($post->title) }}" class="text-blue-500 hover:text-blue-700 font-semibold text-xs">
                                                <i class="fas fa-edit mr-1"></i> គ្រប់គ្រង
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
