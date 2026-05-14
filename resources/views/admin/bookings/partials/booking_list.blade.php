<div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-transition>
    @forelse($bookings as $booking)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group border border-transparent dark:border-gray-800 relative overflow-hidden">

        <div class="absolute top-4 right-4 z-10">
            @php
            $statusColors = [
            'pending' => 'bg-amber-100 text-amber-600',
            'confirmed' => 'bg-blue-100 text-blue-600',
            'completed' => 'bg-green-100 text-green-600',
            'cancelled' => 'bg-red-100 text-red-600',
            ];
            @endphp
            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $statusColors[$booking->status] ?? 'bg-gray-100' }}">
                {{ $booking->status }}
            </span>
        </div>

        <div class="h-40 -mx-5 -mt-5 mb-4 overflow-hidden relative group/img">
            @if($booking->room->roomType->images->count() > 0)
            <img src="{{ asset('storage/' . $booking->room->roomType->images->first()->image_path) }}"
                class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500">
            @else
            <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                <i class="fa-solid fa-hotel text-gray-300 text-3xl"></i>
            </div>
            @endif

            <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover/img:opacity-100 transition-opacity">
                <button @click="currentBooking = {{ $booking->toJson() }}; showDetailModal = true" class="w-9 h-9 bg-white text-blue-600 rounded-xl hover:scale-110 transition"><i class="fas fa-eye"></i></button>
                <button @click="currentBooking = {{ $booking->toJson() }}; showEditModal = true" class="w-9 h-9 bg-white text-amber-500 rounded-xl hover:scale-110 transition"><i class="fas fa-edit"></i></button>
            </div>
        </div>

        <div class="space-y-3">
            <div>
                <h3 class="font-black text-gray-800 dark:text-gray-100 text-sm">#{{ $booking->booking_code }}</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">បន្ទប់លេខ {{ $booking->room->room_number }}</p>
            </div>

            <div class="flex items-center gap-2 py-2 border-y dark:border-gray-700">
                <div class="flex-1">
                    <p class="text-[9px] text-gray-400 uppercase font-black">ចូលស្នាក់នៅ</p>
                    <p class="text-xs font-bold dark:text-white">{{ $booking->check_in }}</p>
                </div>
                <i class="fas fa-arrow-right text-[10px] text-gray-300"></i>
                <div class="flex-1 text-right">
                    <p class="text-[9px] text-gray-400 uppercase font-black">ចាក់ចេញ</p>
                    <p class="text-xs font-bold dark:text-white">{{ $booking->check_out }}</p>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 italic">ភ្ញៀវ: {{ $booking->user ? $booking->user->name . ' (អនឡាញ)' : 'ភ្ញៀវមកផ្ទាល់' }}</span>
                <span class="text-lg font-black text-blue-600">${{ number_format($booking->total_price, 0) }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">@include('admin.bookings.partials.empty_state')</div>
    @endforelse
</div>

<div x-show="viewMode === 'list'" class="space-y-3" x-transition>
    @forelse($bookings as $booking)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all border-none">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 font-black text-xs">
                #{{ substr($booking->booking_code, -4) }}
            </div>
            <div>
                <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100 uppercase">បន្ទប់ {{ $booking->room->room_number }} - {{ $booking->user ? $booking->user->name . ' (អនឡាញ)' : 'ភ្ញៀវមកផ្ទាល់' }}</h4>
                <p class="text-[10px] text-gray-400 italic">{{ $booking->check_in }} ដល់ {{ $booking->check_out }} | សរុប: ${{ number_format($booking->total_price, 2) }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase {{ $statusColors[$booking->status] ?? '' }}">
                {{ $booking->status }}
            </span>
            <div class="flex gap-1">
                <button @click="currentBooking = {{ $booking->toJson() }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500"><i class="fas fa-eye text-sm"></i></button>
                <button @click="currentBooking = {{ $booking->toJson() }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-edit text-sm"></i></button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">@include('admin.bookings.partials.empty_state')</div>
    @endforelse
</div>


<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 shadow-sm" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                <tr class="text-[11px] uppercase font-black text-gray-400 tracking-widest">
                    <th class="px-6 py-5">លេខកូដ</th>
                    <th class="px-6 py-5">ភ្ញៀវ & បន្ទប់</th>
                    <th class="px-6 py-5 text-center">រយៈពេលស្នាក់នៅ</th>
                    <th class="px-6 py-5 text-center">តម្លៃ</th>
                    <th class="px-6 py-5 text-center">ស្ថានភាព</th>
                    <th class="px-6 py-5 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-800 text-sm">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4 font-black text-blue-600">#{{ $booking->booking_code }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold dark:text-white">{{ $booking->user ? $booking->user->name . ' (អនឡាញ)' : 'ភ្ញៀវមកផ្ទាល់' }}</div>
                        <div class="text-[11px] text-gray-400">បន្ទប់ {{ $booking->room->room_number }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-lg text-[11px] font-bold dark:text-gray-300">
                            {{ $booking->check_in }} → {{ $booking->check_out }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center font-black text-gray-700 dark:text-gray-200">
                        ${{ number_format($booking->total_price, 2) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase 
                            {{ $booking->status == 'pending' ? 'bg-amber-100 text-amber-600' : '' }}
                            {{ $booking->status == 'confirmed' ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ $booking->status == 'completed' ? 'bg-green-100 text-green-600' : '' }}
                            {{ $booking->status == 'cancelled' ? 'bg-red-100 text-red-600' : '' }}">
                            {{ $booking->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <!-- ប៊ូតុងមើលលម្អិត -->
                            <button @click="viewDetail({{ json_encode($booking) }})"
                                class="p-2 text-gray-400 hover:text-blue-500 transition-colors">
                                <i class="fas fa-eye text-sm"></i>
                            </button>

                            <!-- ប៊ូតុងកែសម្រួល -->
                            <button @click="editBooking({{ json_encode($booking) }})"
                                class="p-2 text-gray-400 hover:text-amber-500 transition-colors">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <td colspan="6" class="text-center">
                    @include('admin.bookings.partials.empty_state')
                </td>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none">
    <div class="dark:text-white">
        {{ $bookings->links() }}
    </div>
</div>