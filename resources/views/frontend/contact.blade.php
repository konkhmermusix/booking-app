@extends('layouts.app')
@section('title', 'ទំនាក់ទំនង')
@section('content')

<div class="bg-gray-50 dark:bg-[#0b1120] min-h-screen py-20">
    <div class="container mx-auto px-4">
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
                ទំនាក់ទំនងមកយើង <span class="text-blue-600">ភីអេនធី</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
                យើងនៅទីនេះដើម្បីជួយលោកអ្នក ២៤/៧។ សូមផ្ញើសារមកយើងសម្រាប់រាល់ចម្ងល់ ឬការកក់ទុកផ្សេងៗ។
            </p>
            <div class="h-1.5 w-24 bg-blue-600 mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-20 mb-20">
            {{-- Contact Info Cards --}}
            <div class="lg:col-span-1 space-y-6">
                @forelse($contacts as $item)
                <div class="contact-card group bg-white/80 dark:bg-slate-900/80 backdrop-blur-md p-3 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-2 hover:shadow-xl transition-all">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl group-hover:scale-110 transition-transform">
                        <i class="fas {{ $item->icon }}"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white">{{ $item->label }}</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $item->value }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-slate-400">មិនទាន់មានព័ត៌មានទំនាក់ទំនង។</p>
                @endforelse
            </div>

            {{-- Contact Form Section --}}
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 p-8 md:p-10">
                <h3 class="text-2xl font-bold mb-8 flex items-center gap-3 dark:text-white">
                    ផ្ញើសារមកយើង
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8">ប្រសិនបើអ្នកមានចម្ងល់ ឬចង់សាកសួរព័ត៌មានបន្ថែម សូមបំពេញទម្រង់ខាងក្រោម។</p>

                <form id="contactForm" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">ឈ្មោះពេញ *</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="name" placeholder="ឈ្មោះរបស់អ្នក" required
                                    class="w-full py-3 bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px] pl-14 pr-4 transition-all">

                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">អ៊ីមែល</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="email" name="email" placeholder="pnt@mail.com"
                                    class="w-full py-3 bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px] pl-14 pr-4 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">ទូរស័ព្ទ *</label>
                        <div class="relative">
                            <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="tell" placeholder="012345678" required
                                class="w-full py-3 bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px] pl-14 pr-4 transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">សាររបស់អ្នក</label>
                        <textarea rows="5" name="description" placeholder="តើមានអ្វីឱ្យយើងជួយលោកអ្នក?"
                            class="w-full py-3 bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-blue-500 focus:bg-white dark:focus:bg-slate-900 p-4 rounded-2xl outline-none transition-all resize-none"></textarea>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="group w-full py-4 md:w-max bg-blue-600 text-white px-10 rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <span id="btnText">ផ្ញើសារឥឡូវនេះ</span>
                        <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Google Map Section --}}
        @if($mapData)
        <div class="mt-16 group relative rounded-3xl overflow-hidden shadow-2xl border-8 border-white dark:border-slate-800 h-[450px]">
            <iframe src="{{ $mapData->value }}"
                class="w-full h-full" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            <div class="absolute bottom-4 left-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur px-4 py-2 rounded-xl text-xs font-bold shadow-lg">
                {{ $mapData->label }}
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const formData = new FormData(this);

        // UI State: Loading
        btn.disabled = true;
        btnText.innerText = 'កំពុងបញ្ជូន...';

        axios.post('{{ route("contact.store") }}', formData)
            .then(response => {
                // ដោយសារអ្នកមិនចង់កែកូដ Alert 
                // យើងត្រូវបង្ខំឱ្យវា Reload ទំព័រ ដើម្បីឱ្យ Alert ចាប់យក Session មកបង្ហាញ
                window.location.reload();
            })
            .catch(error => {
                btn.disabled = false;
                btnText.innerText = 'ផ្ញើសារឥឡូវនេះ';

                // បង្ហាញ Error Alert បែបធម្មជាតិរបស់ Browser ប្រសិនបើមានបញ្ហា
                alert('មានបញ្ហា៖ ' + (error.response.data.message || 'សូមពិនិត្យទិន្នន័យឡើងវិញ'));
            });
    });
</script>
@endsection