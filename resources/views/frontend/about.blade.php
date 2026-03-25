@extends('layouts.app')
@section('title', 'អំពីយើង')
@section('content')

<header class="group relative h-[55vh] w-full overflow-hidden flex items-center justify-center rounded-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 transition-all duration-500 ease-in-out cursor-default">

    <!-- Animated Grid Background -->
    <div class="absolute inset-0 z-0 animate-grid-move opacity-60 dark:opacity-20 will-change-transform"
        style="background-image: linear-gradient(to right, #cfdae1 1px, transparent 1px), linear-gradient(to bottom, #fbd5e1 1px, transparent 1px); background-size: 80px 80px;">
    </div>

    <!-- Soft Gradient Overlay -->
    <div class="absolute inset-0 z-[1] backdrop-blur-[2px] bg-[radial-gradient(circle_at_center,transparent_30%,rgba(255,255,255,0.9)_100%)] dark:bg-[radial-gradient(circle_at_center,transparent_30%,rgba(2,6,23,0.95)_100%)]"></div>

    <!-- Content -->
    <div class="relative z-10 text-center px-4">
        <h4 class="text-4xl md:text-4xl font-black mb-4 text-pnt-blue dark:text-white tracking-tight transition-all duration-500 ease-in-out group-hover:scale-105 group-hover:text-[#9e8efc] group-hover:drop-shadow-[0_0_20px_rgba(107,218,225,0.5)]">
            អំពីយើង
        </h4>

        <p class="text-lg font-bold text-slate-600 dark:text-slate-400 transition-all duration-500 ease-in-out delay-75 **:group-hover:text-[#9e8efc] group-hover:translate-y-1">

        </p>
    </div>
</header>


<div class="container mx-auto px-4 mt-[-50px] relative z-20 mb-20">
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
        <div class="space-y-6">
            <span class="text-blue-600 font-bold tracking-widest uppercase text-sm">ស្វាគមន៍មកកាន់ P&T Palace</span>
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white">បទពិសោធន៍សម្រាកលំហែកាយ កម្រិតខ្ពស់បំផុត</h2>
            <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                P&T Palace Hotel គឺជាសណ្ឋាគារលំដាប់ផ្កាយដែលស្ថិតនៅចំកណ្តាលក្រុងសៀមរាប។ យើងផ្ដល់ជូននូវសេវាកម្មដ៏ល្អឥតខ្ចោះ បន្ទប់គេងទំនើប និងបរិយាកាសដ៏កក់ក្ដៅបំផុតសម្រាប់ភ្ញៀវទេសចរជាតិ និងអន្តរជាតិ។
            </p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070" class="rounded-3xl shadow-lg mt-8" alt="Lobby">
            <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=2070" class="rounded-3xl shadow-lg" alt="Pool">
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-20">
        <div class="bg-blue-600 p-10 rounded-[2.5rem] text-white shadow-xl transform transition-hover hover:-translate-y-2">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl mb-6">
                <i class="fas fa-eye"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">ចក្ខុវិស័យ (Vision)</h3>
            <p class="text-blue-50 leading-relaxed italic text-lg">
                "ក្លាយជាជម្រើសទីមួយសម្រាប់ការសម្រាកលំហែកាយនៅកម្ពុជា តាមរយៈការផ្ដល់ជូននូវបដិសណ្ឋារកិច្ចបែបខ្មែរពិតៗ និងស្តង់ដារអន្តរជាតិ។"
            </p>
        </div>
        <div class="bg-slate-900 p-10 rounded-[2.5rem] text-white shadow-xl transform transition-hover hover:-translate-y-2">
            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-3xl mb-6">
                <i class="fas fa-rocket"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">បេសកកម្ម (Mission)</h3>
            <p class="text-slate-300 leading-relaxed">
                យើងប្ដេជ្ញាផ្ដល់ជូននូវសេវាកម្មដែលលើសពីការរំពឹងទុករបស់ភ្ញៀវ បង្កើតការងារជូនប្រជាពលរដ្ឋក្នុងតំបន់ និងចូលរួមចំណែកលើកស្ទួយវិស័យទេសចរណ៍ជាតិ។
            </p>
        </div>
    </section>

    <section class="bg-gray-50 dark:bg-slate-900 rounded-[3rem] p-10 md:p-16 border border-gray-100 dark:border-slate-800 relative overflow-hidden mb-20">
        <div class="absolute top-0 right-0 p-10 opacity-5 text-8xl font-black italic">EST. 2023</div>
        <div class="max-w-3xl">
            <h2 class="text-3xl font-black mb-8 dark:text-white italic">ប្រវត្តិរូបសង្ខេប (History)</h2>
            <div class="space-y-8 border-l-4 border-blue-500 pl-8">
                <div>
                    <h4 class="font-bold text-blue-600 text-xl">2023 - ការចាប់ផ្ដើម</h4>
                    <p class="text-slate-600 dark:text-slate-400">យើងបានចាប់ផ្ដើមសាងសង់អាគារដំបូងដែលមានត្រឹមតែ ២០ បន្ទប់ប៉ុណ្ណោះ។</p>
                </div>
                <div>
                    <h4 class="font-bold text-blue-600 text-xl">2025 - ការពង្រីកខ្លួន</h4>
                    <p class="text-slate-600 dark:text-slate-400">P&T Palace បានពង្រីកបន្ថែមដល់ ៨០ បន្ទប់ និងបន្ថែមអាងហែលទឹកកម្រិតអន្តរជាតិ។</p>
                </div>
                <div>
                    <h4 class="font-bold text-blue-600 text-xl">បច្ចុប្បន្ន</h4>
                    <p class="text-slate-600 dark:text-slate-400">ក្លាយជាសណ្ឋាគារឈានមុខគេមួយក្នុងខេត្តសៀមរាប ជាមួយការទទួលស្គាល់ពីភ្ញៀវទូទាំងពិភពលោក។</p>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-8 mb-20">
        <div class="text-center">
            <h2 class="text-3xl font-black dark:text-white italic">វិចិត្រសាលរូបភាព (Photo Gallery)</h2>
            <p class="text-gray-500">ទស្សនាទិដ្ឋភាពជុំវិញសណ្ឋាគាររបស់យើង</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @for ($i = 1; $i <= 8; $i++)
                <div class="group relative h-64 overflow-hidden rounded-3xl cursor-pointer">
                <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070&auto=format&fit=crop"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="Gallery">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <i class="fas fa-search-plus text-white text-2xl"></i>
                </div>
        </div>
        @endfor
    </section>


    <section class="py-24 container mx-auto px-4 text-center mb-20">
        <h2 class="text-3xl font-bold mb-16">បរិក្ខារលេចធ្លោរបស់យើង</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="group">
                <div class="overflow-hidden rounded-3xl mb-4">
                    <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&w=500&q=80"
                        class="group-hover:scale-110 transition duration-500 h-64 w-full object-cover">
                </div>
                <h4 class="font-bold text-xl">ភោជនីយដ្ឋាន</h4>
                <p class="text-gray-500 text-sm">បម្រើអាហារបែបខ្មែរ និងអឺរ៉ុប</p>
            </div>
            <div class="group">
                <div class="overflow-hidden rounded-3xl mb-4">
                    <img src="https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=500&q=80"
                        class="group-hover:scale-110 transition duration-500 h-64 w-full object-cover">
                </div>
                <h4 class="font-bold text-xl">អាងហែលទឹក</h4>
                <p class="text-gray-500 text-sm">អាងហែលទឹកធំទូលាយលើអាកាស</p>
            </div>
            <div class="group">
                <div class="overflow-hidden rounded-3xl mb-4">
                    <img src="https://images.unsplash.com/photo-1506521781263-d8422e82f27a?auto=format&fit=crop&w=500&q=80"
                        class="group-hover:scale-110 transition duration-500 h-64 w-full object-cover">
                </div>
                <h4 class="font-bold text-xl">ចំណតរថយន្ត</h4>
                <p class="text-gray-500 text-sm">សុវត្ថិភាពខ្ពស់ និងធំទូលាយ</p>
            </div>
        </div>
    </section>
</div>
@endsection