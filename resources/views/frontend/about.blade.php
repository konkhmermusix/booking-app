@extends('layouts.app')
@section('title', 'អំពីយើង')
@section('content')

<section class="relative h-[45vh] flex items-center justify-center text-white">
    <img src="https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=1500&q=80"
        class="absolute inset-0 w-full h-full object-cover brightness-50">
    <div class="relative text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-2 tracking-wide">អំពីយើង</h1>
        <div class="w-20 h-1 bg-blue-600 mx-auto rounded-full"></div>
    </div>
</section>

<section class="py-24 container mx-auto px-4">
    <div class="flex flex-col lg:flex-row items-center gap-16">
        <div class="lg:w-1/2">
            <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80"
                class="rounded-[3rem] shadow-2xl rotate-2 hover:rotate-0 transition-transform duration-500">
        </div>
        <div class="lg:w-1/2 space-y-6">
            <h2 class="text-3xl font-bold text-blue-900 dark:text-blue-400">ប្រវត្តិសណ្ឋាគារ</h2>
            <p class="text-gray-600 dark:text-gray-400 leading-loose">
                PNT Hotel បានចាប់ផ្តើមដំណើរការតាំងពីឆ្នាំ ២០១០
                ក្នុងគោលបំណងផ្តល់ជូននូវការស្នាក់នៅបែបប្រណិតដែលបូករួមជាមួយបដិសណ្ឋារកិច្ចខ្មែរពិតៗ។ ក្នុងរយៈពេលជាង ១៥
                ឆ្នាំមកនេះ យើងបានក្លាយជាគោលដៅឈប់សម្រាកដ៏ពេញនិយមសម្រាប់ភ្ញៀវជាតិ និងអន្តរជាតិ
                ដែលស្វែងរកភាពស្ងប់ស្ងាត់ និងផាសុកភាព។
            </p>
            <div class="grid grid-cols-3 gap-4 pt-4">
                <div class="text-center">
                    <h3 class="text-3xl font-bold text-blue-600">15+</h3>
                    <p class="text-xs uppercase text-gray-500">Year Exp</p>
                </div>
                <div class="text-center">
                    <h3 class="text-3xl font-bold text-blue-600">120</h3>
                    <p class="text-xs uppercase text-gray-500">Rooms</p>
                </div>
                <div class="text-center">
                    <h3 class="text-3xl font-bold text-blue-600">50K</h3>
                    <p class="text-xs uppercase text-gray-500">Happy Clients</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-10">
        <div class="bg-white dark:bg-gray-800 p-10 rounded-3xl shadow-lg border-l-8 border-blue-600">
            <i class="fas fa-bullseye text-4xl text-blue-600 mb-6"></i>
            <h3 class="text-2xl font-bold mb-4">បេសកកម្ម (Mission)</h3>
            <p class="text-gray-500 dark:text-gray-400">ផ្តល់ជូននូវសេវាកម្មកម្រិតផ្កាយ ៥
                និងបង្កើតការចងចាំដ៏ល្អបំផុតសម្រាប់ភ្ញៀវគ្រប់រូបតាមរយៈការយកចិត្តទុកដាក់ខ្ពស់បំផុត។</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-10 rounded-3xl shadow-lg border-l-8 border-yellow-500">
            <i class="fas fa-eye text-4xl text-yellow-500 mb-6"></i>
            <h3 class="text-2xl font-bold mb-4">ចក្ខុវិស័យ (Vision)</h3>
            <p class="text-gray-500 dark:text-gray-400">ក្លាយជាសណ្ឋាគារឈានមុខគេនៅក្នុងតំបន់
                ដែលត្រូវបានគេទទួលស្គាល់លើគុណភាពសេវាកម្ម និងការច្នៃប្រឌិតថ្មីៗ។</p>
        </div>
    </div>
</section>

<section class="py-24 container mx-auto px-4 text-center">
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

<section class="py-24 bg-blue-900 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-16">ថ្នាក់ដឹកនាំរបស់យើង</h2>
        <div class="flex flex-wrap justify-center gap-12">
            <div class="w-64">
                <div
                    class="w-48 h-48 mx-auto bg-gray-200 rounded-full mb-6 border-4 border-yellow-500 overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&q=80"
                        class="w-full h-full object-cover">
                </div>
                <h4 class="text-xl font-bold">លោក ភីអិនធី</h4>
                <p class="text-blue-300">ស្ថាបនិក និងជាម្ចាស់សណ្ឋាគារ</p>
            </div>
            <div class="w-64">
                <div
                    class="w-48 h-48 mx-auto bg-gray-200 rounded-full mb-6 border-4 border-white overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80"
                        class="w-full h-full object-cover">
                </div>
                <h4 class="text-xl font-bold">អ្នកស្រី រចនា</h4>
                <p class="text-blue-300">អ្នកគ្រប់គ្រងទូទៅ (General Manager)</p>
            </div>
        </div>
    </div>
</section>

<section class="py-24 container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-16">ទីតាំងរបស់យើង</h2>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-center">
        <div class="lg:col-span-1 space-y-6">
            <div class="flex gap-4">
                <i class="fas fa-map-marked-alt text-3xl text-blue-600"></i>
                <div>
                    <h4 class="font-bold">អាសយដ្ឋាន</h4>
                    <p class="text-gray-500">ផ្លូវជាតិលេខ ៦, ក្រុងសៀមរាប, កម្ពុជា</p>
                </div>
            </div>
            <div class="flex gap-4">
                <i class="fas fa-car text-3xl text-blue-600"></i>
                <div>
                    <h4 class="font-bold">ការធ្វើដំណើរ</h4>
                    <p class="text-gray-500">១៥ នាទីពីព្រលានយន្តហោះ</p>
                </div>
            </div>
        </div>
        <div class="lg:col-span-2 h-[400px] rounded-3xl overflow-hidden shadow-2xl border-2 dark:border-gray-800">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62095.534273874!2d103.820120!3d13.367097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3110173f458338fd%3A0x6e789a7f34c114!2sSiem%20Reap!5e0!3m2!1sen!2skh!4v1700000000000"
                class="w-full h-full" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

@endsection