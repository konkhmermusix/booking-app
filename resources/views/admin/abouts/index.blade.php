@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងអំពីយើង')

@section('content')
<div x-data="{ 
    activeTab: 'about', 
    showAddAbout: false, 
    showEditAbout: false, 
    showAddHistory: false, 
    showEditHistory: false,
    currentAbout: {}, 
    currentHistory: {} 
}" class="p-2">

    <div class="flex flex-col lg:flex-row justify-between items-center bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 gap-4">
        <div class="flex gap-6">
            <button @click="activeTab = 'about'" :class="activeTab === 'about' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400'" class="font-bold pb-1 transition-all">មាតិកាអំពីយើង</button>
            <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400'" class="font-bold pb-1 transition-all">ប្រវត្តិសណ្ឋាគារ</button>
        </div>

        <button @click="activeTab === 'about' ? showAddAbout = true : showAddHistory = true" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-md hover:bg-blue-700 transition-all">
            <i class="fas fa-plus-circle mr-1"></i> បន្ថែមថ្មី
        </button>
    </div>

    <div x-show="activeTab === 'about'" class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 text-xs uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">ចំណងជើង</th>
                    <th class="px-6 py-4">Key</th>
                    <th class="px-6 py-4">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($aboutContents as $about)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ asset('storage/'.$about->image) }}" class="w-10 h-10 rounded-lg object-cover">
                        <span class="font-bold dark:text-white">{{ $about->title_kh }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $about->key }}</td>
                    <td class="px-6 py-4">
                        <span class="{{ $about->status ? 'text-green-600' : 'text-red-600' }} text-[10px] font-bold uppercase">
                            {{ $about->status ? 'បង្ហាញ' : 'បិទ' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button @click="currentAbout = {{ $about->toJson() }}; showEditAbout = true" class="text-amber-500 p-2"><i class="fas fa-edit"></i></button>
                        <form action="{{ route('about.destroy', $about->id) }}" method="POST" class="inline-block">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('លុប?')" class="text-red-500 p-2"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="activeTab === 'history'" x-cloak class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 text-xs uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">ឆ្នាំ</th>
                    <th class="px-6 py-4">ចំណងជើង</th>
                    <th class="px-6 py-4">អាទិភាព</th>
                    <th class="px-6 py-4 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($hotelHistories as $history)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="px-6 py-4 font-bold text-blue-600">{{ $history->year }}</td>
                    <td class="px-6 py-4 dark:text-white">{{ $history->title_kh }}</td>
                    <td class="px-6 py-4 text-gray-500 text-sm">លំដាប់: {{ $history->order_priority }}</td>
                    <td class="px-6 py-4 text-right">
                        <button @click="currentHistory = {{ $history->toJson() }}; showEditHistory = true" class="text-amber-500 p-2"><i class="fas fa-edit"></i></button>
                        <form action="{{ route('history.destroy', $history->id) }}" method="POST" class="inline-block">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('លុប?')" class="text-red-500 p-2"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('admin.abouts.modals')
</div>
@endsection