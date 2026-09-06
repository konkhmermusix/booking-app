<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ឈ្មោះ / អ៊ីម៉ែល / លេខទូរស័ព្ទ</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">តួនាទី</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ចូលដោយ</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ស្ថានភាព</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">IP Address & ឧបករណ៍</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ពេលចូលប្រើប្រាស់</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($users as $user)
                <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-all">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-4">
                            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" class="w-12 h-12 rounded-xl object-cover shadow-sm">
                            <div>
                                <div class="font-bold text-sm dark:text-white">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email ?? 'មិនមាន' }}</div>
                                <div class="text-xs whitespace-nowrap text-sm dark:text-gray-300">{{ $user->phone ?? 'មិនមាន' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-md {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-500/10' : ($user->role == 'staff' ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/10' : 'bg-blue-100 text-blue-700 dark:bg-blue-500/10') }}">
                            {{ $user->role }}
                        </span>
                    </td>

                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                        @if(!empty($user->facebook_id))
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-bold border border-indigo-200 dark:border-indigo-500/20 shadow-sm">
                            <i class="fab fa-facebook-f text-[11px]"></i>
                            Facebook
                        </span>
                        @elseif(!empty($user->google_id))
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-xs font-bold border border-red-200 dark:border-red-500/20 shadow-sm">
                            <i class="fab fa-google text-[11px]"></i>
                            Google
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-xs font-bold border border-blue-200 dark:border-blue-500/20 shadow-sm">
                            <i class="fas fa-envelope text-[11px]"></i>
                            អ៊ីមែល
                        </span>
                        @endif
                    </td>

                    <td class="px-4 py-4 whitespace-nowrap">
                        @if($user->status == 'active')
                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-green-100 text-green-600 dark:bg-green-500/10 dark:text-green-400">
                            សកម្ម
                        </span>
                        @elseif($user->status == 'pending')
                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                            រង់ចាំ
                        </span>
                        @else
                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                            អសកម្ម
                        </span>
                        @endif
                    </td>

                    <td class="px-4 py-4 whitespace-nowrap text-xs">
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center gap-1.5 text-xs font-mono font-bold text-gray-800 dark:text-gray-200">
                                <i class="fas fa-network-wired text-blue-500 text-[10px]"></i>
                                {{ $user->last_login_ip ?? 'មិនទាន់មាន' }}
                            </span>
                            @if($user->last_login_device)
                            <span class="inline-flex items-center gap-1.5 text-[10px] text-gray-400 font-medium">
                                <i class="fas fa-desktop text-gray-400 text-[9px]"></i>
                                {{ $user->last_login_device }}
                            </span>
                            @endif
                        </div>
                    </td>

                    <td class="px-4 py-4 whitespace-nowrap text-xs dark:text-gray-300">
                        <div class="flex flex-col gap-0.5">
                            <span class="font-medium text-xs">{{ $user->last_login_at ? $user->last_login_at->format('d-m-Y H:i') : ($user->updated_at ? $user->updated_at->format('d-m-Y H:i') : 'មិនមាន') }}</span>
                            <span class="text-[10px] text-gray-400">ចូល {{ $user->login_count ?? 0 }} ដង</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                        <div class="flex justify-end items-center gap-1">
                            <button type="button" @click="currentUser = {{ json_encode($user) }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត & Security Logs"><i class="fas fa-eye text-sm"></i></button>
                            <button type="button" @click="currentUser = {{ json_encode($user) }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline m-0">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(this.form)"
                                    class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                                    title="លុប">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">មិនមានទិន្នន័យឡើយ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div x-show="viewMode === 'list'" class="space-y-3" x-cloak>
    @foreach($users as $user)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all border-none">
        <div class="flex items-center gap-4">
            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" class="w-12 h-12 rounded-xl object-cover">
            <div class="flex flex-col gap-0.5">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</span>

                    @if($user->facebook_id)
                    <i class="fab fa-facebook text-indigo-600 text-xs" title="បណ្តាញ Facebook"></i>
                    @elseif($user->google_id)
                    <i class="fab fa-google text-red-500 text-xs" title="បណ្តាញ Google"></i>
                    @else
                    <i class="fas fa-envelope text-blue-500 text-xs" title="គណនីអ៊ីមែលផ្ទាល់ខ្លួន"></i>
                    @endif

                    <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-500/10' : 'bg-blue-100 text-blue-700 dark:bg-blue-500/10' }}">
                        {{ $user->role }}
                    </span>
                </div>
                <span class="text-xs text-gray-400">{{ $user->email }} - {{ $user->phone ?? 'គ្មានលេខទូរស័ព្ទ' }}</span>
                <div class="flex items-center gap-3 text-[11px] text-gray-500 dark:text-gray-400 font-mono mt-0.5">
                    <span><i class="fas fa-network-wired text-blue-500 text-[10px]"></i> IP: {{ $user->last_login_ip ?? 'មិនទាន់មាន' }}</span>
                    <span><i class="fas fa-desktop text-gray-400 text-[9px]"></i> {{ $user->last_login_device ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
        <div class="flex justify-end items-center gap-1">
            <button type="button" @click="currentUser = {{ json_encode($user) }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត & Security Logs"><i class="fas fa-eye text-sm"></i></button>
            <button type="button" @click="currentUser = {{ json_encode($user) }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline m-0">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete(this.form)"
                    class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                    title="លុប">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<div x-show="viewMode === 'grid'" class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4" x-cloak>
    @foreach($users as $user)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group border-none flex flex-col justify-between">
        <div>
            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" class="w-20 h-20 rounded-2xl object-cover shadow-md mb-3">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="font-semibold text-sm text-gray-900 dark:text-white truncate">{{ $user->name }}</span>

                    @if($user->facebook_id)
                    <i class="fab fa-facebook text-indigo-600 text-xs" title="បណ្តាញ Facebook"></i>
                    @elseif($user->google_id)
                    <i class="fab fa-google text-red-500 text-xs" title="បណ្តាញ Google"></i>
                    @else
                    <i class="fas fa-envelope text-blue-500 text-xs" title="គណនីអ៊ីមែលផ្ទាល់ខ្លួន"></i>
                    @endif
                </div>
                <span class="text-xs text-gray-400 truncate">{{ $user->email }}</span>
                <span class="text-[10px] font-mono text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-1">
                    <i class="fas fa-network-wired text-blue-500 text-[9px]"></i> {{ $user->last_login_ip ?? 'No IP' }}
                </span>
            </div>
        </div>

        <div class="flex justify-end items-center gap-1 mt-4 pt-2 border-t border-gray-100 dark:border-gray-700/50">
            <button type="button" @click="currentUser = {{ json_encode($user) }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត & Security Logs"><i class="fas fa-eye text-sm"></i></button>
            <button type="button" @click="currentUser = {{ json_encode($user) }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline m-0">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete(this.form)"
                    class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                    title="លុប">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none transition-colors">
    <div class="dark:text-white">
        {{ $users->links() }}
    </div>
</div>