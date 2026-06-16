@extends('layouts.admin')
@section('title', 'ប្រព័ន្ធគ្រប់គ្រងប្រតិទិនរួម')
@section('content')

<div class="p-2 sm:p-2" x-data="{ currentTab: 'stay', search: '', showAddModal: false, selectedRoom: '', selectedDate: '' }">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-lg font-bold dark:text-white">ប្រព័ន្ធប្រតិទិនរួម</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Hotel Stay & Meeting Room Calendar System</p>
        </div>

        <div class="flex items-center gap-2 bg-gray-50 dark:bg-gray-800 p-1 rounded-xl h-10 dark:border-gray-700">
            <button onclick="changeMonth(-1)" class="w-20 h-full text-xs bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg shadow-xs hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                <i class="fas fa-chevron-left"></i> ខែមុន
            </button>
            <span id="current-month-label" class="text-xs font-black px-4 text-gray-700 dark:text-gray-300 min-w-[120px] text-center">
                {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
            </span>
            <button onclick="changeMonth(1)" class="w-20 h-full text-xs bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg shadow-xs hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                ខែបន្ទាប់ <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
            <button @click="currentTab = 'stay'"
                :class="currentTab === 'stay' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400'"
                class="w-30 h-full rounded-lg transition-all flex items-center justify-center text-xs gap-2">
                <i class="fas fa-bed"></i> បន្ទប់ស្នាក់នៅ
            </button>
            <button @click="currentTab = 'meeting'"
                :class="currentTab === 'meeting' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400'"
                class="w-30 h-full rounded-lg transition-all flex items-center justify-center text-xs gap-2">
                <i class="fas fa-users"></i> សាលប្រជុំ
            </button>
        </div>
    </div>

    <div x-show="loading" x-cloak class="mb-10 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-3 rounded-xl text-sm animate-pulse text-center">
        កំពុងដំណើរការ...
    </div>

    <div id="calendar-table-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.calendar.partials.calendar_table')
    </div>

</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f3f4f6;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 999px;
    }

    .custom-scrollbar-thin::-webkit-scrollbar {
        width: 3px;
    }

    .custom-scrollbar-thin::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .ajax-loading {
        opacity: 0.5;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }
</style>

{{-- 🌟 AJAX SCRIPTS --}}
<script>
    // បង្កើត Variable សម្រាប់រក្សាទុកស្ថានភាព ខែ និងឆ្នាំបច្ចុប្បន្ន
    let currentMonth = parseInt('{{ $month }}');
    let currentYear = parseInt('{{ $year }}');

    function changeMonth(direction) {
        currentMonth += direction;

        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        } else if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }

        // បង្ហាញ Effect Loading ពេលកំពុងទាញទិន្នន័យ
        const container = document.getElementById('calendar-table-container');
        container.classList.add('ajax-loading');

        // ហៅទៅកាន់ Route index របស់ Calendar ដោយភ្ជាប់ Query Parameters
        const url = `{{ route('calendar.index') }}?month=${currentMonth}&year=${currentYear}`;

        fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // ប្រាប់ Laravel ថាជា AJAX Request
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(html => {
                // ជំនួស HTML ចូលទៅក្នុង Container
                container.innerHTML = html;
                container.classList.remove('ajax-loading');

                // ធ្វើបច្ចុប្បន្នភាពអក្សរបង្ហាញខែឆ្នាំនៅលើ Header
                // បើចង់បង្ហាញជាទម្រង់ខ្មែររលូន អាចកែសម្រួល Array ខែបន្ថែម ឬទុកបង្ហាញជាទម្រង់ លេខ/ឆ្នាំ ក៏បាន
                document.getElementById('current-month-label').innerText = `${currentMonth} / ${currentYear}`;
            })
            .catch(error => {
                console.error('Error loading calendar:', error);
                container.classList.remove('ajax-loading');
            });
    }
</script>
@endsection