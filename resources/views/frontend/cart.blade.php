@extends('layouts.app')
@section('title', 'បញ្ជីកន្រ្ដក')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 dark:text-gray-200">
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2 text-gray-900 dark:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        កន្ត្រកទំនិញរបស់អ្នក
    </h1>

    @if(session('success'))
    <div class="mb-4 p-4 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-xl text-sm">
        {{ session('success') }}
    </div>
    @endif

    @if(count($cart) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-4">
            @foreach($cart as $key => $item)
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

                <div class="space-y-1">
                    @if(($item['type'] ?? '') == 'hotel')
                    <span class="bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 text-xs px-2.5 py-1 rounded-md font-bold uppercase">
                        បន្ទប់ស្នាក់នៅ
                    </span>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                        {{ $item['name'] ?? 'មិនមានឈ្មោះបន្ទប់' }}
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        ថ្ងៃស្នាក់នៅ:
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $item['check_in'] ?? 'N/A' }}</span>
                        ដល់
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $item['check_out'] ?? 'N/A' }}</span>
                    </p>

                    <p class="text-xs text-gray-400">
                        ចំនួន: {{ $item['total_nights'] ?? 0 }} យប់ |
                        តម្លៃ: ${{ number_format($item['price'] ?? 0, 2) }}/យប់
                    </p>

                    @elseif(($item['type'] ?? '') == 'meeting')
                    <span class="bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 text-xs px-2.5 py-1 rounded-md font-bold uppercase">
                        សាលប្រជុំ
                    </span>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                        {{ $item['name'] ?? 'មិនមានឈ្មោះសាលប្រជុំ' }}
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        កាលបរិច្ឆេទ:
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $item['start_date'] ?? 'N/A' }}</span>
                        ដល់
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $item['end_date'] ?? 'N/A' }}</span>
                    </p>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        ចន្លោះម៉ោង:
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $item['start_time'] ?? 'N/A' }}</span>
                        -
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $item['end_time'] ?? 'N/A' }}</span>
                    </p>

                    <p class="text-xs text-gray-400">
                        សរុប: {{ $item['total_days'] ?? 0 }} ថ្ងៃ ({{ $item['total_hours'] ?? 0 }} ម៉ោង) |
                        តម្លៃ: ${{ number_format($item['price'] ?? 0, 2) }}/ម៉ោង
                    </p>

                    @else
                    <span class="bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 text-xs px-2.5 py-1 rounded-md font-bold uppercase">
                        ទិន្នន័យមិនច្បាស់លាស់
                    </span>
                    <h3 class="text-lg font-bold text-gray-400 mt-1">ទិន្នន័យកក់មានបញ្ហា (សូមលុបចេញហើយកក់ម្តងទៀត)</h3>
                    @endif
                </div>

                <div class="flex sm:flex-col justify-between sm:items-end w-full sm:w-auto pt-4 sm:pt-0 border-t sm:border-t-0 border-gray-100 dark:border-gray-700">

                    <span class="text-xl font-black text-gray-900 dark:text-white">
                        ${{ number_format($item['total_price'] ?? 0, 2) }}
                    </span>

                    <form action="{{ route('cart.remove', $key) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            លុបចេញ
                        </button>
                    </form>
                </div>

            </div>
            @endforeach
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 sticky top-6 space-y-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">សង្ខេបការបញ្ជាទិញ</h3>

                <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                    <span>ចំនួនកក់សរុប:</span>
                    <span class="font-semibold text-gray-700 dark:text-gray-200">{{ count($cart) }} មុខ</span>
                </div>

                <hr class="border-gray-100 dark:border-gray-700">

                <div class="flex justify-between items-center">
                    <span class="text-base font-medium text-gray-900 dark:text-white">តម្លៃសរុបសាច់ប្រាក់:</span>
                    <span class="text-2xl font-black text-blue-600">${{ number_format($subtotal, 2) }}</span>
                </div>

                @auth
                <a href="{{ route('checkout.index') }}"
                    class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-blue-500/20 text-sm active:scale-95">
                    បន្តទៅកាន់ការទូទាត់ប្រាក់
                </a>
                @else
                <button type="button"
                    onclick="redirectToLogin()"
                    class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-blue-500/20 text-sm active:scale-95">
                    1111
                </button>

                <script>
                    function redirectToLogin() {
                        Swal.fire({
                            title: 'សូមចូលប្រើគណនី!',
                            text: 'អ្នកត្រូវតែចូលប្រើប្រាស់គណនីជាមុនសិន ទើបអាចបន្តទៅកាន់ការទូទាត់ប្រាក់ និងកក់បន្ទប់បាន។',
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ទៅកាន់ទំព័រ Login',
                            cancelButtonText: 'បោះបង់'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // រុញភ្ញៀវទៅកាន់ទំព័រ Login របស់ប្រព័ន្ធ
                                window.location.href = "{{ route('login') }}";
                            }
                        });
                    }
                </script>
                @endauth

                <a href="/" class="block w-full text-center text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-sm font-medium pt-2">
                    ← បន្តមើលបន្ទប់ផ្សេងទៀត
                </a>
            </div>
        </div>

    </div>
    @else
    <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <p class="text-gray-500 dark:text-gray-400 mb-4 font-medium">មិនទាន់មានបន្ទប់ ឬសាលប្រជុំនៅក្នុងកន្ត្រកនៅឡើយទេ។</p>
        <a href="/" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl text-sm transition-all shadow-md">
            ទៅកាន់ទំព័រដើម
        </a>
    </div>
    @endif
</div>


@endsection