@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
    class="fixed top-24 right-5 z-[110] max-w-sm w-full bg-green-500 text-white p-4 rounded-2xl shadow-lg flex items-center gap-3 transition-all">
    <i class="fas fa-check-circle text-2xl"></i>
    <div class="flex-1">
        <p class="font-bold text-sm">ជោគជ័យ!</p>
        <p class="text-xs opacity-90">{{ session('success') }}</p>
    </div>
    <button @click="show = false" class="hover:bg-white/20 p-1 rounded-lg">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
    class="fixed top-24 right-5 z-[110] max-w-sm w-full bg-red-500 text-white p-4 rounded-2xl shadow-lg flex items-center gap-3 transition-all">
    <i class="fas fa-exclamation-circle text-2xl"></i>
    <div class="flex-1">
        <p class="font-bold text-sm">មានបញ្ហា!</p>
        <p class="text-xs opacity-90">{{ session('error') }}</p>
    </div>
    <button @click="show = false" class="hover:bg-white/20 p-1 rounded-lg">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif