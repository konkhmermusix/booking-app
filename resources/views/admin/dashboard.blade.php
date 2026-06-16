@extends('layouts.admin')
@section('title', 'ផ្ទាំងគ្រប់គ្រង')

@section('content')
<div class="p-6 max-w-[1600px] mx-auto">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">ទិដ្ឋភាពទូទៅនៃប្រព័ន្ធ</h1>
        <p class="text-sm text-slate-500">របាយការណ៍ និងស្ថិតិនៃការកក់បន្ទប់ប្រចាំថ្ងៃរបស់អ្នក</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">ការកក់មិនទាន់អនុម័ត</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_pending'] }} ករណី</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">ចំណូលប្រចាំខែនេះ</p>
                <h3 class="text-2xl font-bold text-slate-800">${{ number_format($stats['revenue'], 2) }}</h3>
                <span class="text-xs {{ $stats['revenue_growth'] >= 0 ? 'text-emerald-500' : 'text-rose-500' }} font-semibold mt-1 inline-block">
                    <i class="fa-solid {{ $stats['revenue_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                    {{ abs($stats['revenue_growth']) }}% ធៀបខែមុន
                </span>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">អត្រាប្រើប្រាស់បន្ទប់</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['occupancy_percent'] }}%</h3>
                <span class="text-xs text-slate-500 mt-1 inline-block">
                    ទំនេរ {{ $stats['available_rooms'] }} ពីសរុប {{ $stats['total_rooms'] }} បន្ទប់
                </span>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-door-open"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">ការកក់សរុបក្នុងប្រព័ន្ធ</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['bookings_count'] }} លើក</h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 text-purple-500 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-hotel"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 lg:col-span-3">
            <h3 class="text-lg font-bold text-slate-800 mb-4">ស្ថិតិប្រាក់ចំណូល និងសកម្មភាព</h3>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">ការកក់បន្ទប់សណ្ឋាគារថ្មីៗចុងក្រោយ</h3>
        </div>

        @if(session('success'))
        <div class="m-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="m-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-bold uppercase tracking-wider">
                        <th class="p-4">លេខកូដកក់</th>
                        <th class="p-4">ឈ្មោះភ្ញៀវ</th>
                        <th class="p-4">ថ្ងៃចូល - ថ្ងៃចេញ</th>
                        <th class="p-4">តម្លៃសរុប</th>
                        <th class="p-4">ស្ថានភាព</th>
                        <th class="p-4 text-center">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($stats['recent_bookings'] as $booking)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-4 font-semibold text-blue-600"><code>{{ $booking->booking_code }}</code></td>
                        <td class="p-4 font-medium text-slate-800">{{ $booking->user->name ?? 'ភ្ញៀវក្រៅប្រព័ន្ធ' }}</td>
                        <td class="p-4 text-slate-500">{{ $booking->check_in }} ដល់ {{ $booking->check_out }}</td>
                        <td class="p-4 font-semibold text-slate-800">${{ number_format($booking->total_price, 2) }}</td>
                        <td class="p-4">
                            @if($booking->status === 'pending')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-50 text-amber-600 border border-amber-200">រង់ចាំពិនិត្យ</span>
                            @elseif($booking->status === 'confirmed')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200">បានយល់ព្រម</span>
                            @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-rose-50 text-rose-600 border border-rose-200">បានបដិសេធ</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <button onclick="openApproveModal({{ json_encode($booking) }}, '{{ $booking->user->name ?? 'ភ្ញៀវក្រៅប្រព័ន្ធ' }}')"
                                class="px-4 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-medium transition shadow-sm">
                                <i class="fa-solid fa-eye mr-1"></i> ពិនិត្យមើល
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">មិនទាន់មានទិន្នន័យការកក់នៅក្នុងប្រព័ន្ធនៅឡើយទេ។</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="approveModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 z-50 transition-opacity duration-300">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl p-6 transform scale-95 transition-transform duration-300">

            <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-3">
                <h3 class="text-lg font-bold text-slate-800"><i class="fa-solid fa-file-invoice-dollar text-blue-500 mr-2"></i>ពិនិត្យ និងអនុម័តការកក់</h3>
                <button onclick="closeApproveModal()" class="text-slate-400 hover:text-slate-600 text-xl"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="space-y-4 mb-8">
                <div class="flex justify-between border-b border-dashed border-slate-100 pb-2">
                    <span class="text-slate-400 text-sm">លេខកូដកក់៖</span>
                    <span id="modalCode" class="font-bold text-blue-600"></span>
                </div>
                <div class="flex justify-between border-b border-dashed border-slate-100 pb-2">
                    <span class="text-slate-400 text-sm">ឈ្មោះភ្ញៀវ៖</span>
                    <span id="modalUser" class="font-semibold text-slate-800"></span>
                </div>
                <div class="flex justify-between border-b border-dashed border-slate-100 pb-2">
                    <span class="text-slate-400 text-sm">ថ្ងៃចូល - ថ្ងៃចេញ៖</span>
                    <span id="modalDates" class="text-slate-600"></span>
                </div>
                <div class="flex justify-between border-b border-dashed border-slate-100 pb-2">
                    <span class="text-slate-400 text-sm">ទឹកប្រាក់សរុប៖</span>
                    <span id="modalPrice" class="font-bold text-lg text-slate-800"></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 rounded-xl text-sm font-semibold transition">
                        <i class="fa-solid fa-circle-xmark mr-1"></i> បដិសេធ
                    </button>
                </form>

                <form id="approveForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow-md shadow-blue-200">
                        <i class="fa-solid fa-circle-check mr-1"></i> អនុម័តការកក់
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openApproveModal(booking, userName) {
            // បំពេញទិន្នន័យចូលទៅក្នុង Modal តាម ID
            document.getElementById('modalCode').innerText = booking.booking_code;
            document.getElementById('modalUser').innerText = userName;
            document.getElementById('modalDates').innerText = booking.check_in + ' ដល់ ' + booking.check_out;
            document.getElementById('modalPrice').innerText = '$' + parseFloat(booking.total_price).toFixed(2);

            // កំណត់ទិសដៅ Action របស់ Form ទៅតាម ID របស់ការកក់នីមួយៗ
            document.getElementById('approveForm').action = "/admin/bookings/" + booking.id + "/approve";
            document.getElementById('rejectForm').action = "/admin/bookings/" + booking.id + "/reject";

            // បង្ហាញ Modal មកលើអេក្រង់
            const modal = document.getElementById('approveModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeApproveModal() {
            const modal = document.getElementById('approveModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['មករា', 'កុម្ភៈ', 'មីនា', 'មេសា', 'ឧសភា', 'មិថុនា'],
                datasets: [{
                    label: 'ប្រាក់ចំណូលសរុប ($)',
                    data: [1500, 2300, 1800, 2900, 2200, {
                        {
                            $stats['revenue']
                        }
                    }], // ទាញយកតម្លៃខែចុងក្រោយពី Controller
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection