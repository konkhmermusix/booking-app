@extends('layouts.admin')
@section('title', 'បញ្ជីអ្នកប្រើប្រាស់')

@section('content')

<div class="space-y-6" x-data="{ showAddModal: false, showEditModal: false, showDetailModal: false, currentUser: {}}">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold dark:text-white">គ្រប់គ្រងអ្នកប្រើប្រាស់</h2>
            <p class="text-gray-500 dark:text-gray-400">គ្រប់គ្រងអ្នកប្រើប្រាស់ និងមើលស្ថានភាព</p>
        </div>

        <button @click="showAddModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-2xl shadow-xl shadow-blue-500/20 transition-all flex items-center justify-center gap-2 font-bold">
            <i class="fas fa-plus-circle"></i>បន្ថែម
        </button>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-2xl border dark:border-gray-800">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 text-xs uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">ឈ្មោះអ្នកប្រើ / អ៊ីម៉ែល</th>
                    <th class="px-6 py-4">ទូរស័ព្ទ</th>
                    <th class="px-6 py-4">តួនាទី</th>
                    <th class="px-4 py-4">ស្ថានភាព</th>
                    <th class="px-4 py-4">បង្កើតឡើង</th>
                    <th class="px-7 py-4 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-800">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.$user->name }}" class="w-10 h-10 rounded-lg object-cover">
                        <div>
                            <div class="font-bold dark:text-white">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm dark:text-gray-300">{{ $user->phone ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg 
                            {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-600' : ($user->role == 'staff' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600') }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        @if($user->status == 'active')
                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-green-100 text-green-600 dark:bg-green-500/10 dark:text-green-400">
                            សកម្ម (Active)
                        </span>
                        @elseif($user->status == 'pending')
                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                            រង់ចាំ (Pending)
                        </span>
                        @else
                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                            អសកម្ម (Inactive)
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-sm dark:text-gray-300">{{ $user->created_at ?? 'N/A' }}</td>

                    <td class="px-6 py-4 text-right flex gap-3 justify-end">
                        <div class="flex justify-end gap-2 space-x-3">
                            <button @click="currentUser = {{ $user }}; showDetailModal = true" class=" text-gray-400 hover:text-indigo-500 transition-colors" title="មើលលម្អិត"> <i class="fas fa-eye"></i></button>
                            <button @click="currentUser = {{ $user }}; showEditModal = true" class=" text-blue-500 hover:text-blue-700" title="កែប្រែ"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="delete-form ">
                                @csrf @method('DELETE')
                                <button type="button" class="text-red-500 hover:text-red-700 btn-delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-6 py-4 border-t dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
            {{ $users->links() }}
        </div>
    </div>

    @include('admin.users.modals')
</div>

@endsection