<!-- GRID VIEW -->
<div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-transition>
    @forelse($posts as $post)
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col group hover:shadow-md transition-all duration-300">

        <div class="relative h-48 bg-gray-100 dark:bg-gray-800 overflow-hidden">
            @if(!empty($post->images) && isset($post->images[0]))
            <img src="{{ asset('storage/' . $post->images[0]) }}" alt="Post Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                <i class="far fa-image text-3xl mb-2"></i>
                <span class="text-xs">គ្មានរូបភាព</span>
            </div>
            @endif

            @if(!empty($post->images) && count($post->images) > 1)
            <span class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm text-white text-[10px] px-2 py-1 rounded-lg font-bold">
                +{{ count($post->images) - 1 }} រូបភាព
            </span>
            @endif

            <div class="absolute bottom-3 left-3">
                @if($post->status === 'published')
                <span class="px-2.5 py-1 bg-emerald-500 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider">សាធារណៈ</span>
                @elseif($post->status === 'draft')
                <span class="px-2.5 py-1 bg-amber-500 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider">សេចក្ដីព្រាង</span>
                @else
                <span class="px-2.5 py-1 bg-gray-500 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider">ឯកជន</span>
                @endif
            </div>
        </div>

        <div class="p-4 flex-1 flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors text-sm" title="{{ $post->title }}">
                    {{ $post->title }}
                </h3>

                <div class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-3 mb-3 font-medium">
                    <span><i class="far fa-user mr-1"></i> {{ $post->user->name ?? 'អ្នកគ្រប់គ្រង' }}</span>
                    <span><i class="far fa-eye mr-1"></i> {{ $post->views ?? 0 }} នាក់</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-1 border-t border-gray-50 dark:border-gray-800/50 pt-3 mt-2">
                <button type="button" @click="openDetailModal({{ json_encode($post) }})"
                    class="p-2 text-gray-400 hover:text-blue-500 transition-colors"
                    title="មើលលម្អិត">
                    <i class="fas fa-eye text-sm"></i>
                </button>

                <button type="button" @click="openEditModal({{ json_encode($post) }})"
                    class="p-2 text-gray-400 hover:text-amber-500 transition-colors"
                    title="កែប្រែ">
                    <i class="fas fa-edit text-sm"></i>
                </button>

                <button type="button"
                    onclick="confirmDelete('{{ $post->id }}')"
                    class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                    title="លុប">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white dark:bg-gray-800 rounded-2xl p-12 text-center text-gray-400 border border-dashed border-gray-200 dark:border-gray-800">
        <i class="far fa-folder-open text-4xl mb-3 block text-gray-300 dark:text-gray-700"></i>
        <span class="text-sm font-medium">មិនមានទិន្នន័យព័ត៌មានឡើយ</span>
    </div>
    @endforelse
</div>

<!-- LIST VIEW -->
<div x-show="viewMode === 'list'" class="space-y-3" x-transition>
    @forelse($posts as $post)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm flex items-center justify-between gap-4 border border-gray-100 dark:border-gray-800 hover:shadow-md transition-all">
        <div class="flex items-center gap-4 min-w-0">
            <div class="w-16 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 overflow-hidden flex-shrink-0">
                @if(!empty($post->images) && isset($post->images[0]))
                <img src="{{ asset('storage/' . $post->images[0]) }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs"><i class="far fa-image"></i></div>
                @endif
            </div>
            <div class="min-w-0">
                <h3 class="font-bold text-gray-800 dark:text-white truncate text-sm" title="{{ $post->title }}">{{ $post->title }}</h3>
                <div class="flex items-center gap-3 text-xs text-gray-400 mt-1">
                    <span><i class="far fa-user mr-1"></i> {{ $post->user->name ?? 'Admin' }}</span>
                    <span><i class="far fa-calendar-alt mr-1"></i> {{ $post->created_at->format('d-M-Y') }}</span>
                    <span><i class="far fa-eye mr-1"></i> {{ $post->views ?? 0 }} នាក់</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 flex-shrink-0">
            <div>
                @if($post->status === 'published')
                <span class="px-2.5 py-1 bg-emerald-500 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider">សាធារណៈ</span>
                @elseif($post->status === 'draft')
                <span class="px-2.5 py-1 bg-amber-500 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider">សេចក្ដីព្រាង</span>
                @else
                <span class="px-2.5 py-1 bg-gray-500 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider">ឯកជន</span>
                @endif
            </div>

            <div class="flex items-center gap-1">
                <button type="button" @click="openDetailModal({{ json_encode($post) }})" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត"><i class="fas fa-eye text-sm"></i></button>
                <button type="button" @click="openEditModal({{ json_encode($post) }})" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>
                <button type="button" onclick="confirmDelete('{{ $post->id }}')" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="លុប"><i class="fas fa-trash text-sm"></i></button>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-12 text-center text-gray-400 border border-dashed border-gray-200 dark:border-gray-800">
        <i class="far fa-folder-open text-4xl mb-3 block text-gray-300 dark:text-gray-700"></i>
        <span class="text-sm font-medium">មិនមានទិន្នន័យព័ត៌មានឡើយ</span>
    </div>
    @endforelse
</div>

<!-- TABLE VIEW -->
<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-800" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/50 text-gray-400 dark:text-gray-500 text-xs font-bold uppercase border-b border-gray-100 dark:border-gray-800">
                    <th class="px-6 py-4 text-center">រូបភាព</th>
                    <th class="px-6 py-4">ចំណងជើងព័ត៌មាន</th>
                    <th class="px-6 py-4">ស្ថានភាព</th>
                    <th class="px-6 py-4">អ្នកផុស</th>
                    <th class="px-6 py-4">អ្នកមើល</th>
                    <th class="px-6 py-4 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                @forelse($posts as $post)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4 text-center">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 overflow-hidden mx-auto">
                            @if(!empty($post->images) && isset($post->images[0]))
                            <img src="{{ asset('storage/' . $post->images[0]) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs"><i class="far fa-image"></i></div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ Str::limit($post->title, 40) }}</td>
                    <td class="px-6 py-4">
                        @if($post->status === 'published')
                        <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-600 text-[10px] font-bold rounded-lg uppercase tracking-wider">សាធារណៈ</span>
                        @elseif($post->status === 'draft')
                        <span class="px-2.5 py-1 bg-amber-500/10 text-amber-600 text-[10px] font-bold rounded-lg uppercase tracking-wider">សេចក្ដីព្រាង</span>
                        @else
                        <span class="px-2.5 py-1 bg-gray-500/10 text-gray-600 text-[10px] font-bold rounded-lg uppercase tracking-wider">ឯកជន</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $post->user->name ?? 'Admin' }}</td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $post->views ?? 0 }} នាក់</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                        <div class="flex justify-end items-center gap-1">
                            <button type="button" @click="openDetailModal({{ json_encode($post) }})" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត"><i class="fas fa-eye text-sm"></i></button>
                            <button type="button" @click="openEditModal({{ json_encode($post) }})" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>
                            <button type="button" onclick="confirmDelete('{{ $post->id }}')" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="លុប"><i class="fas fa-trash text-sm"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-12 text-center text-gray-400">
                        <span>មិនមានទិន្នន័យព័ត៌មានឡើយ</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none">
    <div class="dark:text-white">
        {{ $posts->links() }}
    </div>
</div>

<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'តើអ្នកប្រាកដទេ?',
            text: "អ្នកនឹងមិនអាចត្រឡប់ទិន្នន័យនេះមកវិញបានទេ!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'លុប',
            cancelButtonText: 'បោះបង់',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('delete-form');
                form.action = `/admin/posts/${id}`;
                form.submit();
            }
        })
    }
</script>