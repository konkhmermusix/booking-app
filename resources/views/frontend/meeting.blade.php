@extends('layouts.app')
@section('title', 'សាលប្រជុំ')

@section('content')

<!-- banner -->
<header class="relative h-[35vh] flex items-center justify-center text-white">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1920"
            class="absolute inset-0 w-full h-full object-cover brightness-50">
    </div>
    <div class="relative z-10 text-center px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">សាលប្រជុំ និងកម្មវិធីផ្សេងៗ</h1>
        <p class="text-lg opacity-80">ទីកន្លែងដ៏ល្អឥតខ្ចោះសម្រាប់ភាពជោគជ័យនៃអាជីវកម្មរបស់អ្នក</p>
    </div>
</header>

<section class="py-20 container mx-auto px-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        <div
            class="bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-xl border dark:border-gray-800 flex flex-col">
            <img src="https://images.unsplash.com/photo-1431540015161-0bf868a2d407?auto=format&fit=crop&w=800&q=80"
                class="h-72 w-full object-cover">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Grand Ballroom (100 Pax)</h2>
                    <span class="bg-blue-100 text-blue-600 px-4 py-1 rounded-full text-sm font-bold">សាលធំ</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="flex items-center gap-3 text-sm"><i class="fas fa-users text-blue-500"></i> ចំណុះ:
                        100 នាក់</div>
                    <div class="flex items-center gap-3 text-sm"><i class="fas fa-th text-blue-500"></i> Style:
                        Theater/Classroom</div>
                    <div class="flex items-center gap-3 text-sm"><i class="fas fa-projector text-blue-500"></i>
                        Projector 4K</div>
                    <div class="flex items-center gap-3 text-sm"><i class="fas fa-volume-up text-blue-500"></i>
                        Sound System VIP</div>
                </div>

                <h4 class="font-bold mb-4 border-b pb-2">គ្រឿងបរិក្ខាររួមមាន (Equipment)</h4>
                <ul class="grid grid-cols-2 gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><i class="fas fa-check-circle text-green-500 mr-2"></i> High-speed WiFi</li>
                    <li><i class="fas fa-check-circle text-green-500 mr-2"></i> Wireless Mics</li>
                    <li><i class="fas fa-check-circle text-green-500 mr-2"></i> Whiteboard & Marker</li>
                    <li><i class="fas fa-check-circle text-green-500 mr-2"></i> Flipchart</li>
                </ul>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-xl border dark:border-gray-800 flex flex-col">
            <img src="https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=800&q=80"
                class="h-72 w-full object-cover">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Executive Room (50 Pax)</h2>
                    <span class="bg-blue-100 text-blue-600 px-4 py-1 rounded-full text-sm font-bold">មធ្យម</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="flex items-center gap-3 text-sm"><i class="fas fa-users text-blue-500"></i> ចំណុះ:
                        50 នាក់</div>
                    <div class="flex items-center gap-3 text-sm"><i class="fas fa-th text-blue-500"></i> Style:
                        U-Shape/Boardroom</div>
                    <div class="flex items-center gap-3 text-sm"><i class="fas fa-tv text-blue-500"></i> LED TV 75"
                    </div>
                    <div class="flex items-center gap-3 text-sm"><i class="fas fa-wifi text-blue-500"></i> Dedicated
                        Fiber</div>
                </div>

                <h4 class="font-bold mb-4 border-b pb-2">គ្រឿងបរិក្ខាររួមមាន (Equipment)</h4>
                <ul class="grid grid-cols-2 gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><i class="fas fa-check-circle text-green-500 mr-2"></i> Coffee Break Set</li>
                    <li><i class="fas fa-check-circle text-green-500 mr-2"></i> HDMI Connectors</li>
                    <li><i class="fas fa-check-circle text-green-500 mr-2"></i> Air Conditioning</li>
                    <li><i class="fas fa-check-circle text-green-500 mr-2"></i> Water & Stationery</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-blue-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">កញ្ចប់តម្លៃសេវាកម្ម</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">

            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-lg border-t-8 border-blue-500 text-center">
                <h3 class="text-xl font-bold mb-2">កញ្ចប់កន្លះថ្ងៃ (Half Day)</h3>
                <p class="text-gray-500 mb-6 text-sm">៨:០០ ព្រឹក - ១២:០០ ថ្ងៃត្រង់</p>
                <div class="text-4xl font-bold text-blue-600 mb-6">$150<span
                        class="text-sm text-gray-400 font-normal">/បូករួមអាហារសម្រន់</span></div>
                <ul class="text-sm space-y-3 mb-8 text-gray-600 dark:text-gray-300">
                    <li>១ ដង សម្រាប់ Coffee Break</li>
                    <li>ប្រើប្រាស់សម្ភារៈបច្ចេកទេសទាំងអស់</li>
                    <li>ទឹកបរិសុទ្ធ និងក្រដាសសរសេរ</li>
                </ul>
            </div>

            <div
                class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-lg border-t-8 border-gold-500 text-center relative overflow-hidden">
                <div
                    class="absolute top-4 right-[-35px] bg-yellow-500 text-white text-[10px] font-bold px-10 py-1 rotate-45">
                    POPULAR</div>
                <h3 class="text-xl font-bold mb-2">កញ្ចប់ពេញមួយថ្ងៃ (Full Day)</h3>
                <p class="text-gray-500 mb-6 text-sm">៨:០០ ព្រឹក - ៥:០០ ល្ងាច</p>
                <div class="text-4xl font-bold text-blue-600 mb-6">$280<span
                        class="text-sm text-gray-400 font-normal">/បូករួមអាហារសម្រន់</span></div>
                <ul class="text-sm space-y-3 mb-8 text-gray-600 dark:text-gray-300">
                    <li>២ ដង សម្រាប់ Coffee Break</li>
                    <li>បូករួមអាហារថ្ងៃត្រង់ (Buffet)</li>
                    <li>ជំនួយការបច្ចេកទេសប្រចាំការ</li>
                </ul>
            </div>

        </div>
    </div>
</section>

<section class="py-20 container mx-auto px-4 max-w-3xl">
    <div class="bg-white dark:bg-gray-900 p-10 rounded-[2rem] shadow-2xl border dark:border-gray-800">
        <h2 class="text-2xl font-bold mb-2 text-center">សាកសួរព័ត៌មាន ឬកក់ទីតាំង</h2>
        <p class="text-center text-gray-500 mb-10">បំពេញព័ត៌មានខាងក្រោម ក្រុមការងារយើងនឹងទាក់ទងទៅវិញក្នុងពេលឆាប់ៗ
        </p>

        <form class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold mb-2 ml-1">ឈ្មោះអ្នកទាក់ទង</label>
                    <input type="text" placeholder="បញ្ចូលឈ្មោះ..."
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-4 rounded-2xl focus:ring-2 ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 ml-1">លេខទូរស័ព្ទ</label>
                    <input type="tel" placeholder="012 345 678"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-4 rounded-2xl focus:ring-2 ring-blue-500 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold mb-2 ml-1">កាលបរិច្ឆេទ</label>
                    <input type="date"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-4 rounded-2xl focus:ring-2 ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 ml-1">ចំនួនអ្នកចូលរួម</label>
                    <input type="number" placeholder="ឧទាហរណ៍: 50"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-4 rounded-2xl focus:ring-2 ring-blue-500 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold mb-2 ml-1">ជ្រើសរើសសាលប្រជុំ</label>
                <select
                    class="w-full bg-gray-50 dark:bg-gray-800 border-none p-4 rounded-2xl focus:ring-2 ring-blue-500 outline-none appearance-none">
                    <option>Grand Ballroom (100 Pax)</option>
                    <option>Executive Room (50 Pax)</option>
                </select>
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold text-lg hover:bg-blue-700 shadow-xl transition-all active:scale-95">ផ្ញើសំណើកក់ឥឡូវនេះ</button>
        </form>
    </div>
</section>

@endsection