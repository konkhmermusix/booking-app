@extends('layouts.app')
@section('title', 'គោលការណ៍ឯកជនភាព | សណ្ឋាគារ ភីអេនធី ផាលេស')

@section('content')
<div class="mx-auto">
    {{-- PAGE HEADER (MATCHING OTHER FRONTEND PAGES) --}}
    <div class="pt-20 text-center mb-16 relative z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
            គោលការណ៍ <span class="text-blue-600">ឯកជនភាព</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
            សណ្ឋាគារ ភីអេនធី ផាលេស ប្តេជ្ញាចិត្តយ៉ាងមុតមាំក្នុងការការពារឯកជនភាព និងសុវត្ថិភាពនៃទិន្នន័យផ្ទាល់ខ្លួនរបស់អតិថិជនគ្រប់រូប។
        </p>
        <div class="h-1.5 w-24 bg-blue-600 mx-auto mt-6 rounded-full"></div>
    </div>

    {{-- MAIN CONTENT SECTION --}}
    <section class="py-10 bg-gray-50 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 p-6 md:p-10 space-y-10">

                {{-- ផ្នែកទី១ --}}
                <section class="space-y-4 border-b border-gray-100 dark:border-slate-800 pb-8">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                            ១. ទិន្នន័យផ្ទាល់ខ្លួនរបស់អតិថិជនចុះឈ្មោះប្រើប្រាស់សេវាកម្ម
                        </h2>
                    </div>
                    <div class="pl-5 space-y-4 text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>ដើម្បីដំណើរការកក់បន្ទប់ និងផ្តល់សេវាកម្មប្រកបដោយប្រសិទ្ធភាព យើងប្រមូលព័ត៌មានចាំបាច់មួយចំនួនរួមមាន៖</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-signature text-base"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white text-sm">ឈ្មោះ និងគណនី</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">ឈ្មោះពេញ និងគណនីប្រើប្រាស់</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-phone text-base"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white text-sm">ព័ត៌មានទំនាក់ទំនង</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">លេខទូរស័ព្ទ និងអាសយដ្ឋានអ៊ីមែល</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-receipt text-base"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white text-sm">ប្រវត្តិការកក់</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">ប្រភេទបន្ទប់ និងថ្ងៃខែស្នាក់នៅ</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-credit-card text-base"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white text-sm">ប្រតិបត្តិការទូទាត់</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">វិក្កយបត្រ និងការទូទាត់ប្រាក់</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ផ្នែកទី២ --}}
                <section class="space-y-4 border-b border-gray-100 dark:border-slate-800 pb-8">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                            ២. ការពារសុវត្ថិភាពសារជជែក 
                        </h2>
                    </div>
                    <div class="pl-5 space-y-4 text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>យើងបានបំពាក់បច្ចេកវិទ្យាកូដនីយកម្មសម្ងាត់ <strong>Advanced Encryption Standard (AES-256-CBC Encryption)</strong> លើប្រព័ន្ធសាររបស់យើង៖</p>
                        
                        <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-2">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-shield-halved text-sm"></i>
                                </div>
                                <p class="font-bold text-gray-900 dark:text-white text-sm">
                                    សុវត្ថិភាពទិន្នន័យសម្ងាត់ ១០០%
                                </p>
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 dark:text-gray-300 sm:pl-10 leading-relaxed">
                                រាល់សារសន្ទនារវាងអតិថិជន និងសណ្ឋាគារ ត្រូវបាន Encrypt ទៅជាកូដសម្ងាត់មុនពេលរក្សាទុកក្នុង Database។ ទោះបីជាមានការបើកមើលក្នុង Database ក៏មិនអាចអាន ឬដឹងអំពីខ្លឹមសារនៃការសន្ទនាបានឡើយ។
                            </p>
                        </div>
                    </div>
                </section>

                {{-- ផ្នែកទី៣ --}}
                <section class="space-y-4 border-b border-gray-100 dark:border-slate-800 pb-8">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                            ៣. គោលបំណងនៃការប្រើប្រាស់ទិន្នន័យ
                        </h2>
                    </div>
                    <div class="pl-5 space-y-3 text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>ទិន្នន័យរបស់លោកអ្នកត្រូវបានប្រើប្រាស់ក្នុងគោលបំណងច្បាស់លាស់ដូចតទៅ៖</p>
                        <ul class="space-y-2 list-disc list-inside text-xs md:text-sm pl-2">
                            <li>ផ្ទៀងផ្ទាត់ និងធានាការកក់បន្ទប់ស្នាក់នៅ ឬបន្ទប់ប្រជុំជូនលោកអ្នក។</li>
                            <li>ទំនាក់ទំនងផ្ញើលិខិតបញ្ជាក់ការកក់ ឬការរំលឹកការកក់។</li>
                            <li>ផ្តល់ការគាំទ្រ និងឆ្លើយតបចម្ងល់តាមរយៈឆាតសារសេវាកម្មអតិថិជន។</li>
                            <li>កែលម្អគុណភាពសេវាកម្ម និងបទពិសោធន៍ប្រើប្រាស់លើគេហទំព័រ។</li>
                        </ul>
                    </div>
                </section>

                {{-- ផ្នែកទី៤ --}}
                <section class="space-y-4 border-b border-gray-100 dark:border-slate-800 pb-8">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                            ៤. ការមិនចែករំលែកទិន្នន័យទៅភាគីទីបី
                        </h2>
                    </div>
                    <div class="pl-5 space-y-3 text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>សណ្ឋាគារ ភីអេនធី ផាលេស <strong>ដាច់ខាតមិនលក់ ជួល ឬចែករំលែក</strong> ទិន្នន័យផ្ទាល់ខ្លួនរបស់លោកអ្នកទៅកាន់ក្រុមហ៊ុន ឬភាគីទីបីក្នុងគោលបំណងពាណិជ្ជកម្មឡើយ។ ទិន្នន័យអាចត្រូវបានផ្ទទៀងផ្ទាត់លុះត្រាតែមានការតម្រូវដោយច្បាប់ជាធរមាន។</p>
                    </div>
                </section>

                {{-- ផ្នែកទី៥ --}}
                <section class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                            ៥. សិទ្ធិរបស់ម្ចាស់ទិន្នន័យ
                        </h2>
                    </div>
                    <div class="pl-5 space-y-3 text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>លោកអ្នកមានសិទ្ធិពេញលេញក្នុងការស្នើសុំពិនិត្យ កែប្រែ ឬស្នើសុំលុបទិន្នន័យផ្ទាល់ខ្លួន និងប្រវត្តិនៃការសន្ទនារបស់លោកអ្នកចេញពីប្រព័ន្ធរបស់យើងបានគ្រប់ពេលវេលា ដោយគ្រាន់តែទំនាក់ទំនងមកកាន់ក្រុមការងារយើងខ្ញុំ។</p>
                    </div>
                </section>

            </div>

            {{-- HELP / CONTACT CARD --}}
            <div class="mt-8 rounded-2xl bg-blue-600 p-6 md:p-8 text-white flex flex-col md:flex-row items-center justify-between gap-4 shadow-lg">
                <div>
                    <h3 class="text-lg font-bold">មានសំណួរអំពីការការពារឯកជនភាព?</h3>
                    <p class="text-xs md:text-sm text-blue-100 mt-1">លោកអ្នកអាចទាក់ទងមកកាន់នាយកដ្ឋានការពារទិន្នន័យ និងសេវាកម្មអតិថិជនរបស់យើងបាន។</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('chat.index') }}" class="px-5 py-2.5 rounded-xl bg-white text-blue-600 font-bold text-xs md:text-sm hover:bg-blue-50 transition shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-headset"></i> ទាក់ទងសេវាកម្មអតិថិជន
                    </a>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection