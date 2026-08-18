@extends('layouts.app')
@section('title', 'លក្ខខណ្ឌនៃការប្រើប្រាស់ | សណ្ឋាគារ ភីអេនធី ផាលេស')

@section('content')
<div class="mx-auto">
    {{-- PAGE HEADER (MATCHING OTHER FRONTEND PAGES) --}}
    <div class="pt-20 text-center mb-16 relative z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
            លក្ខខណ្ឌនៃ <span class="text-blue-600">ការប្រើប្រាស់</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
            សូមអានលក្ខខណ្ឌនៃការប្រើប្រាស់ខាងក្រោមដោយយកចិត្តទុកដាក់ មុនពេលធ្វើការកក់បន្ទប់ ឬប្រើប្រាស់សេវាកម្មរបស់សណ្ឋាគារ ភីអេនធី ផាលេស។
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
                            ១. គោលការណ៍កក់បន្ទប់ និងការទូទាត់ប្រាក់
                        </h2>
                    </div>
                    <div class="pl-5 space-y-3 text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>រាល់ការកក់បន្ទប់ស្នាក់នៅ ឬបន្ទប់ប្រជុំតាមរយៈគេហទំព័រនេះ តម្រូវឲ្យអតិថិជនផ្តល់ព័ត៌មានដែលពិតប្រាកដ។ សណ្ឋាគាររក្សាសិទ្ធិក្នុងការលុបចោលការកក់ ប្រសិនបើរកឃើញថាព័ត៌មាននោះជាព័ត៌មានក្លែងបន្លំ ឬមិនប្រក្រតី។</p>
                    </div>
                </section>

                {{-- ផ្នែកទី២ --}}
                <section class="space-y-4 border-b border-gray-100 dark:border-slate-800 pb-8">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                            ២. ការចូលស្នាក់នៅ និងចាកចេញ
                        </h2>
                    </div>
                    <div class="pl-5 space-y-4 text-sm md:text-base text-gray-600 dark:text-gray-300">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-1">
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                                    <i class="fa-solid fa-clock"></i> ម៉ោងចូលស្នាក់នៅ
                                </span>
                                <p class="text-base font-bold text-gray-900 dark:text-white">ចាប់ពីម៉ោង ០២:០០ រសៀល</p>
                                <p class="text-xs text-gray-600 dark:text-gray-300">ការចូលមុនម៉ោង អាស្រ័យលើភាពទំនេរនៃបន្ទប់ជាក់ស្តែង។</p>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-1">
                                <span class="text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                                    <i class="fa-solid fa-clock-rotate-left"></i> ម៉ោងចាកចេញ
                                </span>
                                <p class="text-base font-bold text-gray-900 dark:text-white">មុនម៉ោង ១២:០០ ថ្ងៃត្រង់</p>
                                <p class="text-xs text-gray-600 dark:text-gray-300">ការចាកចេញយឺត អាចនឹងមានការគិតថ្លៃសេវាបន្ថែមតាមម៉ោង។</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ផ្នែកទី៣ --}}
                <section class="space-y-4 border-b border-gray-100 dark:border-slate-800 pb-8">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                            ៣. គោលការណ៍លុបចោល និងកែប្រែការកក់
                        </h2>
                    </div>
                    <div class="pl-5 space-y-3 text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>អតិថិជនអាចធ្វើការកែប្រែថ្ងៃខែ ឬលុបចោលការកក់បានដោយសេរី ប្រសិនបើធ្វើឡើងមុនថ្ងៃចូលស្នាក់នៅយ៉ាងហោចណាស់ <strong>៤៨ ម៉ោង</strong>។</p>
                        <p>ករណីលុបចោលយឺតជាងការកំណត់ ឬភ្ញៀវមិនបានមកតាមការណាត់ សណ្ឋាគាររក្សាសិទ្ធិលុបចោលការកក់ដោយស្វ័យប្រវត្តិដោយមិនបានបញ្ជាក់។</p>
                    </div>
                </section>

                {{-- ផ្នែកទី៤ --}}
                <section class="space-y-4 border-b border-gray-100 dark:border-slate-800 pb-8">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                            ៤. ទំនួលខុសត្រូវ និងការថែរក្សាទ្រព្យសម្បត្តិ
                        </h2>
                    </div>
                    <div class="pl-5 space-y-3 text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>ភ្ញៀវស្នាក់នៅត្រូវទទួលខុសត្រូវលើការខូចខាត ឬការបាត់បង់សម្ភារក្នុងបន្ទប់ដែលបង្កឡើងដោយចេតនា ឬប្រហែស។ សណ្ឋាគារមានសិទ្ធិគិតថ្លៃសងការខូចខាតតាមតម្លៃសម្ភារជាក់ស្តែង។</p>
                        <p>ហាមឃាត់ដាច់ខាតនូវការនាំចូលនូវសារធាតុញៀន, អាវុធជាតិផ្ទុះ, ឬសត្វចិញ្ចឹម (លើកលែងតែមានការអនុញ្ញាតពិសេស) ចូលក្នុងបរិវេណសណ្ឋាគារ។</p>
                    </div>
                </section>

                {{-- ផ្នែកទី៥ --}}
                <section class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                            ៥. ការប្រើប្រាស់សេវាកម្មសារ និងទំនាក់ទំនង 
                        </h2>
                    </div>
                    <div class="pl-5 space-y-3 text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                        <p>សេវាកម្មសារ ត្រូវបានផ្តល់ជូនសម្រាប់សម្រួលដល់ការសាកសួរព័ត៌មាន និងការកក់បន្ទប់។ រាល់សារសន្ទនាទាំងអស់ត្រូវបានការពារដោយប្រព័ន្ធសុវត្ថិភាព និងការកូដនីយកម្មសម្ងាត់។</p>
                        <p>សណ្ឋាគាររក្សាសិទ្ធិក្នុងការផ្អាក ឬលុបគណនីណាដែលប្រើប្រាស់ពាក្យអសុរោះ សាររំខាន (Spam) ឬផ្ញើសារបោកប្រាស់។</p>
                    </div>
                </section>

            </div>

            {{-- HELP / CONTACT CARD --}}
            <div class="mt-8 rounded-2xl bg-blue-600 p-6 md:p-8 text-white flex flex-col md:flex-row items-center justify-between gap-4 shadow-lg">
                <div>
                    <h3 class="text-lg font-bold">មានចម្ងល់បន្ថែមអំពីលក្ខខណ្ឌប្រើប្រាស់?</h3>
                    <p class="text-xs md:text-sm text-blue-100 mt-1">ក្រុមការងារផ្នែកសេវាកម្មអតិថិជនរបស់យើងរង់ចាំជួយសម្រួលលោកអ្នក ២៤/៧។</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('chat.index') }}" class="px-5 py-2.5 rounded-xl bg-white text-blue-600 font-bold text-xs md:text-sm hover:bg-blue-50 transition shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-comments"></i> ឆាតសាកសួរឥឡូវនេះ
                    </a>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection