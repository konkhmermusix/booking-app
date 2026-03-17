<nav class="bg-blue-800 text-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex-shrink-0 flex items-center">
                <span class="text-xl font-bold tracking-tighter">BOOKING<span class="text-yellow-400">ADMIN</span></span>
            </div>

            <div class="hidden md:flex space-x-8 text-sm font-medium">
                <a href="{{ route('hotels.index') }}" class="hover:text-yellow-400 transition">សណ្ឋាគារ</a>
                <a href="#" class="hover:text-yellow-400 transition">ប្រភេទបន្ទប់</a>
                <a href="#" class="hover:text-yellow-400 transition">ការកក់</a>
            </div>

            <div class="flex items-center space-x-4">
                <span class="hidden sm:inline text-xs bg-blue-700 px-3 py-1 rounded-full text-blue-100">Admin</span>
                <button class="md:hidden text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>