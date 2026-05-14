@extends('layouts.app')
@section('title', 'សេវាកម្ម និងសម្ភារៈ')
@section('content')

<div class="container mx-auto">
    <div class="pt-20 text-center mb-30 relative z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
            សេវាកម្ម <span class="text-blue-600">&</span> សម្ភារៈ
        </h1>
        <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
            យើងផ្តល់ជូននូវបទពិសោធន៍ដ៏ល្អបំផុតជាមួយនឹងសេវាកម្មលំដាប់ខ្ពស់ និងគ្រឿងបរិក្ខារទំនើបៗសម្រាប់រាល់ការស្នាក់នៅរបស់អ្នក។
        </p>
        <div class="h-1.5 w-30 bg-blue-600 mx-auto mt-6 rounded-full"></div>
    </div>

    <section class="py-10 bg-gray-50 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @forelse($facilities as $facility)
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 hover:border-blue-500 transition-all group">
                    <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center text-blue-600 text-3xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <i class="{{ $facility->icon ?? 'fas fa-concierge-bell' }}"></i>
                    </div>

                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase mb-3 tracking-tight">
                        {{ $facility->name }}
                    </h3>

                    <div class="flex items-center gap-2 text-blue-600 font-bold text-xs uppercase tracking-widest">
                        <span>សេវាកម្ម ២៤ ម៉ោង</span>
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-gray-400 italic">មិនទាន់មានទិន្នន័យគ្រឿងបរិក្ខារនៅឡើយទេ</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-10 bg-white dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="bg-blue-600 rounded-2xl p-8 md:p-12 text-white shadow-2xl shadow-blue-500/20">
                <div class="flex flex-col lg:flex-row gap-12 items-center">
                    <div class="lg:w-1/3">
                        <h3 class="text-3xl font-black mb-4">អាហារ និងភេសជ្ជៈ</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-300"></i> ភោជនីយដ្ឋានអាហារពេលថ្ងៃត្រង់</li>
                            <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-300"></i> សេវាផ្ញើដល់បន្ទប់ ២៤/៧</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="p-10 bg-yellow-600 rounded-2xl text-white flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl shadow-blue-500/30">
                <div>
                    <h2 class="text-3xl font-black uppercase mb-2">តើអ្នកមានសំណួរផ្សេងៗ?</h2>
                    <p class="opacity-80">ក្រុមការងារយើងខ្ញុំរង់ចាំបម្រើលោកអ្នករាល់សេវាកម្មដែលត្រូវការ។</p>
                </div>
                <a href="/contact" class="px-8 py-4 bg-white text-blue-600 font-black rounded-2xl uppercase tracking-widest hover:bg-gray-100 transition-all text-sm">
                    ទាក់ទងមកយើងឥឡូវនេះ
                </a>
            </div>
        </div>
    </section>
</div>
@endsection