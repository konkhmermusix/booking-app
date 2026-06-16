@extends('layouts.admin')
@section('title', 'ផ្ទាំងគ្រប់គ្រង')

@section('content')
<div class="p-2 sm:p-2 transition-colors duration-200">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-9 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-[#EAF7E6] dark:bg-emerald-950/30 p-5 rounded-2xl border border-emerald-50 dark:border-emerald-900/30">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-gray-500 dark:text-emerald-400/80 font-medium">New Bookings</span>
                        <span class="p-1.5 bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg text-xs shadow-sm"><i class="fa-solid fa-calendar"></i></span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">840</h2>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium"><i class="fa-solid fa-arrow-up-long"></i> 8.70% <span class="text-gray-400 dark:text-gray-500 font-normal">from last week</span></span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Check-In</span>
                        <span class="p-1.5 bg-gray-50 dark:bg-gray-700 rounded-lg text-xs"><i class="fa-solid fa-arrow-right-to-bracket text-emerald-500"></i></span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">231</h2>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium"><i class="fa-solid fa-arrow-up-long"></i> 3.56% <span class="text-gray-400 dark:text-gray-500 font-normal">from last week</span></span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Check-Out</span>
                        <span class="p-1.5 bg-gray-50 dark:bg-gray-700 rounded-lg text-xs"><i class="fa-solid fa-arrow-right-from-bracket text-red-400"></i></span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">124</h2>
                    <span class="text-xs text-red-500 dark:text-red-400 font-medium"><i class="fa-solid fa-arrow-down-long"></i> 1.06% <span class="text-gray-400 dark:text-gray-500 font-normal">from last week</span></span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Revenue</span>
                        <span class="p-1.5 bg-gray-50 dark:bg-gray-700 rounded-lg text-xs"><i class="fa-solid fa-dollar-sign text-emerald-600 dark:text-emerald-400"></i></span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">$123,980</h2>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium"><i class="fa-solid fa-arrow-up-long"></i> 5.70% <span class="text-gray-400 dark:text-gray-500 font-normal">from last week</span></span>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 md:col-span-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">Room Availability</h3>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"><i class="fa-solid fa-ellipsis"></i></button>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-8 rounded-xl overflow-hidden flex mb-6">
                        <div class="bg-[#D1FAE5] dark:bg-emerald-500 w-[60%]"></div>
                        <div class="bg-[#FEF08A] dark:bg-yellow-500 w-[25%]"></div>
                        <div class="bg-[#E2E8F0] dark:bg-gray-500 w-[15%]"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border-l-4 border-emerald-400 pl-3">
                            <span class="text-xs text-gray-400 dark:text-gray-500">Occupied</span>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">286</h4>
                        </div>
                        <div class="border-l-4 border-yellow-300 pl-3">
                            <span class="text-xs text-gray-400 dark:text-gray-500">Reserved</span>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">87</h4>
                        </div>
                        <div class="border-l-4 border-emerald-600 dark:border-emerald-400 pl-3">
                            <span class="text-xs text-gray-400 dark:text-gray-500">Available</span>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">32</h4>
                        </div>
                        <div class="border-l-4 border-gray-300 dark:border-gray-600 pl-3">
                            <span class="text-xs text-gray-400 dark:text-gray-500">Not Ready</span>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">13</h4>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 md:col-span-8 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">Revenue</h3>
                        <select class="text-xs bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-100 dark:border-gray-600 rounded-lg px-2 py-1 focus:outline-none">
                            <option>Last 6 Months</option>
                        </select>
                    </div>
                    <div class="h-40 flex items-end justify-between pt-6 relative">
                        <div class="absolute top-2 left-[40%] bg-[#FEF08A] dark:bg-yellow-500/20 px-2 py-1 rounded-lg text-xs font-bold shadow-sm border border-yellow-200 dark:border-yellow-500/30 text-gray-800 dark:text-yellow-400">
                            Total Revenue: <span>$315,060</span>
                        </div>
                        <div class="w-full border-b border-dashed border-gray-100 dark:border-gray-700 absolute bottom-10"></div>
                        <div class="w-full border-b border-dashed border-gray-100 dark:border-gray-700 absolute bottom-20"></div>
                        <div class="w-full border-b border-dashed border-gray-100 dark:border-gray-700 absolute bottom-30"></div>
                        <div class="w-full flex justify-between text-[10px] text-gray-400 dark:text-gray-500 px-2 mt-2 absolute bottom-0">
                            <span>Dec 2027</span><span>Jan 2028</span><span>Feb 2028</span><span>Mar 2028</span><span>Apr 2028</span><span>May 2028</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 md:col-span-7 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 text-base">Reservations</h3>
                            <div class="flex gap-4 mt-1 text-xs">
                                <span class="flex items-center gap-1.5 text-gray-400 dark:text-gray-500">
                                    <span class="w-2.5 h-2.5 bg-[#D1FAE5] dark:bg-emerald-500/50 rounded-sm inline-block"></span> Booked
                                </span>
                                <span class="flex items-center gap-1.5 text-gray-400 dark:text-gray-500">
                                    <span class="w-2.5 h-2.5 bg-[#E6F4A2] dark:bg-yellow-400/50 rounded-sm inline-block"></span> Canceled
                                </span>
                            </div>
                        </div>
                        <select class="text-xs bg-[#EAF7E6] dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-400 font-medium border border-transparent rounded-lg px-3 py-1.5 focus:outline-none">
                            <option>Last 7 Days</option>
                        </select>
                    </div>
                    <div class="h-64">
                        <canvas id="reservationsChart"></canvas>
                    </div>
                </div>

                <div class="col-span-12 md:col-span-5 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 text-base">Booking by Platform</h3>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"><i class="fa-solid fa-ellipsis"></i></button>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="w-40 h-40 flex-shrink-0">
                            <canvas id="platformChart"></canvas>
                        </div>
                        <div class="space-y-2 flex-1 text-xs text-gray-500 dark:text-gray-400 font-medium pl-2 w-full">
                            <div class="flex justify-between items-center">
                                <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#D1FAE5] dark:bg-emerald-400"></span> Direct Booking</span>
                                <span class="font-bold text-gray-700 dark:text-gray-300">61%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#A7F3D0] dark:bg-emerald-500"></span> Booking.com</span>
                                <span class="font-bold text-gray-700 dark:text-gray-300">12%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#CCA43B] dark:bg-amber-500"></span> Agoda</span>
                                <span class="font-bold text-gray-700 dark:text-gray-300">11%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#E6F4A2] dark:bg-lime-400"></span> Airbnb</span>
                                <span class="font-bold text-gray-700 dark:text-gray-300">9%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#FEF9C3] dark:bg-yellow-300"></span> Hotels.com</span>
                                <span class="font-bold text-gray-700 dark:text-gray-300">5%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#F1F5F9] dark:bg-gray-600"></span> Others</span>
                                <span class="font-bold text-gray-700 dark:text-gray-300">2%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200">Booking List</h3>
                    <div class="flex gap-2">
                        <input type="text" placeholder="Search guest, status, etc" class="text-xs bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-100 dark:border-gray-600 rounded-lg px-3 py-1.5 focus:outline-none w-48">
                        <select class="text-xs bg-[#EAF7E6] dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-400 font-medium rounded-lg px-3 py-1.5 focus:outline-none border border-transparent">
                            <option>All Status</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 dark:text-gray-500 text-xs font-medium border-b border-gray-50 dark:border-gray-700">
                                <th class="pb-3">Booking ID</th>
                                <th class="pb-3">Guest Name</th>
                                <th class="pb-3">Room Type</th>
                                <th class="pb-3">Room Number</th>
                                <th class="pb-3">Duration</th>
                                <th class="pb-3">Check-In & Check-Out</th>
                                <th class="pb-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs text-gray-700 dark:text-gray-300 font-medium">
                            <tr class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                <td class="py-4 text-gray-400 dark:text-gray-500">LG-B00108</td>
                                <td class="py-4 text-gray-800 dark:text-gray-200">Angus Copper</td>
                                <td class="py-4"><span class="bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 px-2 py-0.5 rounded-md text-[10px]">Deluxe</span></td>
                                <td class="py-4">Room 101</td>
                                <td class="py-4">3 nights</td>
                                <td class="py-4 text-gray-400 dark:text-gray-500">June 19, 2028 - June 22, 2028</td>
                                <td class="py-4"><span class="bg-[#EAF7E6] dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 px-2 py-1 rounded-lg">Checked-In</span></td>
                            </tr>
                            <tr class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                <td class="py-4 text-gray-400 dark:text-gray-500">LG-B00109</td>
                                <td class="py-4 text-gray-800 dark:text-gray-200">Catherine Lopp</td>
                                <td class="py-4"><span class="bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-md text-[10px]">Standard</span></td>
                                <td class="py-4">Room 202</td>
                                <td class="py-4">2 nights</td>
                                <td class="py-4 text-gray-400 dark:text-gray-500">June 19, 2028 - June 21, 2028</td>
                                <td class="py-4"><span class="bg-[#EAF7E6] dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 px-2 py-1 rounded-lg">Checked-In</span></td>
                            </tr>
                            <tr class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                <td class="py-4 text-gray-400 dark:text-gray-500">LG-B00110</td>
                                <td class="py-4 text-gray-800 dark:text-gray-200">Edgar Irving</td>
                                <td class="py-4"><span class="bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 px-2 py-0.5 rounded-md text-[10px]">Suite</span></td>
                                <td class="py-4">Room 303</td>
                                <td class="py-4">5 nights</td>
                                <td class="py-4 text-gray-400 dark:text-gray-500">June 19, 2028 - June 24, 2028</td>
                                <td class="py-4"><span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-2 py-1 rounded-lg">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-3 space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200 text-sm">Overall Rating</h3>
                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"><i class="fa-solid fa-ellipsis"></i></button>
                </div>
                <div class="flex items-baseline gap-2 mb-4">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">4.6</h2>
                    <span class="text-xs text-gray-400 dark:text-gray-500">/5</span>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded ml-2">Impressive</span>
                </div>
                <div class="space-y-2 text-xs text-gray-500 dark:text-gray-400">
                    <div>
                        <div class="flex justify-between mb-1"><span>Facilities</span><span class="font-semibold text-gray-700 dark:text-gray-300">4.4</span></div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full">
                            <div class="bg-yellow-400 h-1.5 rounded-full" style="width: 88%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1"><span>Cleanliness</span><span class="font-semibold text-gray-700 dark:text-gray-300">4.7</span></div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full">
                            <div class="bg-yellow-400 h-1.5 rounded-full" style="width: 94%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1"><span>Services</span><span class="font-semibold text-gray-700 dark:text-gray-300">4.6</span></div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full">
                            <div class="bg-yellow-400 h-1.5 rounded-full" style="width: 92%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200 text-sm">Tasks</h3>
                    <button class="w-6 h-6 bg-[#EAF7E6] dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-lg text-xs flex items-center justify-center"><i class="fa-solid fa-plus"></i></button>
                </div>
                <div class="space-y-3 text-xs">
                    <div class="p-3 bg-[#EAF7E6] dark:bg-emerald-950/20 rounded-xl border border-emerald-100 dark:border-emerald-900/30">
                        <span class="text-gray-400 dark:text-emerald-500/70 block mb-1">June 19, 2028</span>
                        <p class="font-semibold text-gray-800 dark:text-gray-200">Set Up Conference Room B for 10 AM Meeting</p>
                    </div>
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-950/20 rounded-xl border border-yellow-100 dark:border-yellow-900/30">
                        <span class="text-gray-400 dark:text-yellow-500/70 block mb-1">June 19, 2028</span>
                        <p class="font-semibold text-gray-800 dark:text-gray-200">Restock Housekeeping Supplies on 3rd Floor</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200 text-sm">Recent Activities</h3>
                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"><i class="fa-solid fa-ellipsis"></i></button>
                </div>
                <div class="space-y-4 relative before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-100 dark:before:bg-gray-700">
                    <div class="flex gap-4 relative">
                        <div class="w-6 h-6 bg-yellow-100 dark:bg-yellow-950 rounded-full flex items-center justify-center text-[10px] text-yellow-600 dark:text-yellow-400 z-10"><i class="fa-solid fa-handshake"></i></div>
                        <div>
                            <span class="text-[10px] text-gray-400 dark:text-gray-500">12:00 PM</span>
                            <h4 class="font-semibold text-xs text-gray-800 dark:text-gray-200">Conference Room Setup</h4>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">Events Team set up Conference Room B...</p>
                        </div>
                    </div>
                    <div class="flex gap-4 relative">
                        <div class="w-6 h-6 bg-emerald-100 dark:bg-emerald-950 rounded-full flex items-center justify-center text-[10px] text-emerald-600 dark:text-emerald-400 z-10"><i class="fa-solid fa-door-open"></i></div>
                        <div>
                            <span class="text-[10px] text-gray-400 dark:text-gray-500">11:30 AM</span>
                            <h4 class="font-semibold text-xs text-gray-800 dark:text-gray-200">Guest Check-Out</h4>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">Sarah Johnson completed check-out process...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // មុខងារពិនិត្យថាតើកំពុងប្រើប្រាស់ Dark Mode ឬទេ
    const isDarkMode = () => document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');

    // កំណត់ពណ៌អក្សរ និងបន្ទាត់ Grid ទៅតាម Mode
    const getChartStyles = () => ({
        textColor: isDarkMode() ? '#9CA3AF' : '#4B5563',
        gridColor: isDarkMode() ? '#374151' : '#F3F4F6'
    });

    const styles = getChartStyles();

    // --- 1. Reservations Chart (Stacked Bar) ---
    const ctxBar = document.getElementById('reservationsChart').getContext('2d');
    const reservationsChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['12 Jun', '13 Jun', '14 Jun', '15 Jun', '16 Jun', '17 Jun', '18 Jun'],
            datasets: [{
                    label: 'Booked',
                    data: [60, 65, 62, 70, 75, 65, 52],
                    backgroundColor: isDarkMode() ? '#059669' : '#D1FAE5', // បៃតងចាស់ក្នុង Dark Mode
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Canceled',
                    data: [22, 21, 28, 16, 21, 18, 38],
                    backgroundColor: isDarkMode() ? '#CA8A04' : '#E6F4A2', // លឿងក្រមៅក្នុង Dark Mode
                    borderRadius: 6,
                }
            ]
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
                x: {
                    stacked: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        },
                        color: styles.textColor
                    }
                },
                y: {
                    stacked: true,
                    grid: {
                        borderDash: [4, 4],
                        color: styles.gridColor
                    },
                    min: 0,
                    max: 100,
                    ticks: {
                        stepSize: 25,
                        font: {
                            size: 10
                        },
                        color: styles.textColor
                    }
                }
            }
        }
    });

    // --- 2. Booking by Platform Chart (Donut) ---
    const ctxDonut = document.getElementById('platformChart').getContext('2d');
    const platformChart = new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [61, 12, 11, 9, 5, 2],
                backgroundColor: isDarkMode() ?
                    ['#10B981', '#059669', '#B45309', '#84CC16', '#EAB308', '#4B5563'] :
                    ['#D1FAE5', '#A7F3D0', '#C2D16D', '#E6F4A2', '#FEF9C3', '#F1F5F9'],
                borderWidth: 2,
                borderColor: isDarkMode() ? '#1f2937' : '#ffffff' // ប្តូរពណ៌ព្រំតាម Mode
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // ឃ្លាំមើលការផ្លាស់ប្តូរ Mode (ឧទាហរណ៍៖ ប្រសិនបើអ្នកប្រើប្រាស់ចុចប៊ូតុង Toggle)
    const observer = new MutationObserver(() => {
        const updatedStyles = getChartStyles();

        // ធ្វើបច្ចុប្បន្នភាពពណ៌ Reservations Chart
        reservationsChart.options.scales.x.ticks.color = updatedStyles.textColor;
        reservationsChart.options.scales.y.ticks.color = updatedStyles.textColor;
        reservationsChart.options.scales.y.grid.color = updatedStyles.gridColor;
        reservationsChart.data.datasets[0].backgroundColor = isDarkMode() ? '#059669' : '#D1FAE5';
        reservationsChart.data.datasets[1].backgroundColor = isDarkMode() ? '#CA8A04' : '#E6F4A2';
        reservationsChart.update();

        // ធ្វើបច្ចុប្បន្នភាពពណ៌ Platform Chart
        platformChart.data.datasets[0].backgroundColor = isDarkMode() ?
            ['#10B981', '#059669', '#B45309', '#84CC16', '#EAB308', '#4B5563'] :
            ['#D1FAE5', '#A7F3D0', '#C2D16D', '#E6F4A2', '#FEF9C3', '#F1F5F9'];
        platformChart.data.datasets[0].borderColor = isDarkMode() ? '#1f2937' : '#ffffff';
        platformChart.update();
    });

    // ចាប់ផ្តើមសង្កេតមើល class លើ tag html 
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
</script>
@endsection