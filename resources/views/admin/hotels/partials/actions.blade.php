<div class="flex items-center gap-1 {{ isset($fullWidth) ? 'w-full justify-around' : 'justify-end' }}">
    <button type="button"
        @click="openDetailModal({{ json_encode([
            'id' => $hotel->id,
            'name' => $hotel->name,
            'email' => $hotel->email,
            'phone' => $hotel->phone,
            'address' => $hotel->address,
            'description' => $hotel->description ?? '',
            'latitude' => $hotel->latitude,
            'longitude' => $hotel->longitude,
            'status' => $hotel->status,
            'logo' => $hotel->logo,
            'rooms_count' => $hotel->rooms_count ?? count($hotel->rooms ?? [])
        ]) }})"
        class="p-2 text-gray-400 hover:text-blue-500 transition-colors"
        title="មើលលម្អិត">
        <i class="fas fa-eye text-sm"></i>
    </button>

    <button type="button"
        @click="openEditModal({{ json_encode([
            'id' => $hotel->id,
            'name' => $hotel->name,
            'email' => $hotel->email,
            'phone' => $hotel->phone,
            'address' => $hotel->address,
            'description' => $hotel->description ?? '',
            'latitude' => $hotel->latitude,
            'longitude' => $hotel->longitude,
            'status' => $hotel->status,
            'logo' => $hotel->logo
        ]) }})"
        class="p-2 text-gray-400 hover:text-amber-500 transition-colors"
        title="កែប្រែ">
        <i class="fas fa-edit text-sm"></i>
    </button>

    <form action="{{ route('hotels.destroy', $hotel->id) }}" method="POST" class="inline m-0">
        @csrf
        @method('DELETE')
        <button type="button"
            onclick="confirmDelete(this.form)"
            class="p-2 text-gray-400 hover:text-red-500 transition-colors"
            title="លុប">
            <i class="fas fa-trash text-sm"></i>
        </button>
    </form>
</div>
