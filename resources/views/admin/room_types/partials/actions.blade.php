<div class="flex items-center gap-2 {{ isset($fullWidth) ? 'w-full' : 'justify-end' }}">
    <button @click="currentRoomType = { 
        id: '{{ $type->id }}', 
        name: '{{ $type->name }}', 
        hotel_name: '{{ $type->hotel->name ?? 'N/A' }}',
        base_price: '{{ $type->base_price }}',
        max_guests: '{{ $type->max_guests }}',
        description: '{{ addslashes($type->description) }}',
        facilities: {{ $type->facilities->toJson() }},
        images: {{ $type->images->toJson() }}
    }; showDetailModal = true"
        class="h-9 px-3 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all {{ isset($fullWidth) ? 'flex-1' : '' }}">
        <i class="fa-solid fa-eye text-xs"></i>
    </button>

    <button @click="currentRoomType = { ... }; showEditModal = true"
        class="h-9 px-3 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-xl hover:bg-amber-500 hover:text-white transition-all {{ isset($fullWidth) ? 'flex-1' : '' }}">
        <i class="fa-solid fa-pen-to-square text-xs"></i>
    </button>
</div>