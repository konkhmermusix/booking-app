@extends('layouts.admin')
@section('title', 'បញ្ជីការកក់បន្ទប់')

@section('content')

<div class="space-y-6" x-data="{ showAddModal: false, showEditModal: false, showDetailModal: false, currentBooking: { hotel: {}, user: {} } }" class="p-6 min-h-screen bg-gray-50 dark:bg-black">
    <div class="flex justify-between items-center">

        <div>
            <h2 class="text-2xl font-bold dark:text-white">គ្រប់គ្រងកក់បន្ទប់</h2>
            <p class="text-gray-500 dark:text-gray-400">គ្រប់គ្រងរាល់ការកក់ និងស្ថានភាពស្នាក់នៅរបស់ភ្ញៀវ</p>
        </div>

        <button @click="showAddModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-2xl shadow-xl shadow-blue-500/20 transition-all flex items-center justify-center gap-2 font-bold">
            <i class="fas fa-plus-circle"></i> បង្កើត
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl border dark:border-gray-800 shadow-sm">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">ការកក់សរុប</p>
            <p class="text-2xl font-black dark:text-white">{{ $bookings->total() }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-3xl border dark:border-gray-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 text-[10px] uppercase font-black tracking-widest">
                        <th class="px-6 py-4">លេខកូដកក់</th>
                        <th class="px-6 py-4">ភ្ញៀវ & សណ្ឋាគារ</th>
                        <th class="px-6 py-4">កាលបរិច្ឆេទ</th>
                        <th class="px-6 py-4">តម្លៃសរុប</th>
                        <th class="px-6 py-4">ស្ថានភាព</th>
                        <th class="px-6 py-4 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-800">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors group">
                        <td class="px-6 py-5">
                            <span class="font-black text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-3 py-1.5 rounded-lg text-sm">
                                #{{ $booking->booking_code }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-bold dark:text-white">{{ $booking->user->name ?? 'ភ្ញៀវក្រៅប្រព័ន្ធ' }}</div>
                            <div class="text-xs text-gray-400">{{ $booking->hotel->name }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2 text-xs font-medium dark:text-gray-300">
                                <span class="text-emerald-500 font-bold">{{ $booking->check_in }}</span>
                                <i class="fas fa-arrow-right text-[10px] text-gray-300"></i>
                                <span class="text-rose-500 font-bold">{{ $booking->check_out }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 font-black text-gray-800 dark:text-gray-200">
                            ${{ number_format($booking->total_price, 2) }}
                        </td>
                        <td class="px-6 py-5">
                            @php
                            $statusClasses = [
                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                            'confirmed' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                            'completed' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                            'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                            ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $statusClasses[$booking->status] }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right flex gap-3 justify-end">
                            <div class="flex justify-end gap-2 space-x-3">
                                <button @click="currentBooking = {{ $booking->toJson() }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors"><i class="fas fa-eye"></i></button>
                                <button @click="currentBooking = {{ $booking->toJson() }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-delete p-2 text-gray-400 hover:text-red-500 transition-colors"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
            {{ $bookings->links() }}
        </div>
    </div>

    @include('admin.bookings.modals')

</div>
@endsection