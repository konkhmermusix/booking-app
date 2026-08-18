<style>
    .ck-editor__editable {
        min-height: 250px;
    }

    .dark .ck-editor__editable {
        background-color: #1f2937 !important;
        color: #ffffff !important;
    }

    .dark .ck-toolbar {
        background-color: #111827 !important;
        border-color: #374151 !important;
    }

    .dark .ck-button {
        color: #ffffff !important;
    }

    .dark .ck-button:hover {
        background-color: #374151 !important;
    }

    .ck-content {
        color: #000;
    }

    .ck-editor {
        width: 100%;
    }

    .ck-content h1 {
        font-size: 2em;
        font-weight: bold;
        margin-top: 0.67em;
        margin-bottom: 0.67em;
    }

    .ck-content h2 {
        font-size: 1.5em;
        font-weight: bold;
        margin-top: 0.83em;
        margin-bottom: 0.83em;
    }

    .ck-content h3 {
        font-size: 1.17em;
        font-weight: bold;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    .ck-content h4 {
        font-size: 1em;
        font-weight: bold;
        margin-top: 1.33em;
        margin-bottom: 1.33em;
    }

    .ck-content p {
        margin-top: 0;
        margin-bottom: 1rem;
    }

    .ck-content ul {
        list-style-type: disc;
        padding-left: 40px;
    }

    .ck-content ol {
        list-style-type: decimal;
        padding-left: 40px;
    }
</style>

<div x-show="showAddModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false; clearAllPreviews('add_images')"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បង្កើតព័ត៌មាន ឬអត្ថបទថ្មី</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Title, Content & Gallery Images</p>
                </div>
                <button @click="showAddModal = false; clearAllPreviews('add_images')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form @submit.prevent="savePost()" enctype="multipart/form-data">
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ចំណងជើងព័ត៌មាន <span class="text-red-500">*</span></label>
                            <input type="text" x-model="currentPost.title" required placeholder="សូមបញ្ចូលចំណងជើងអត្ថបទ..."
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ស្ថានភាពផុស <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <select x-model="currentPost.status" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-bold text-sm">
                                    <option value="draft">សេចក្តីព្រាង</option>
                                    <option value="published">សាធារណៈ</option>
                                    <option value="private">ឯកជន</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>

                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">ខ្លឹមសារព័ត៌មានលម្អិត (Content)</label>
                        <div class="dark:text-black" @click.away="if(window.addEditorInstance) currentPost.content = window.addEditorInstance.getData()">
                            <textarea id="add_editor" class="w-full"></textarea>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">រូបភាពអាល់ប៊ុមអត្ថបទ (Gallery - អតិបរមា ២០ សន្លឹក)</label>
                        <div class="relative group">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-2xl bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer transition-all border-blue-500/30">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa-solid fa-images text-2xl text-blue-500 mb-2"></i>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-black uppercase tracking-widest text-center px-4">
                                        ចុចដើម្បីជ្រើសរើសរូបភាពច្រើន​សន្លឹក <br>
                                        <span class="text-blue-400">(JPG, PNG, WEBP, GIF)</span>
                                    </p>
                                </div>
                                <input type="file" id="add_images" name="images[]" multiple class="hidden" accept="image/*" @change="handleFileSelect" />
                            </label>
                        </div>

                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 mt-4 max-h-60 overflow-y-auto p-1 custom-scrollbar">
                            <template x-for="(src, index) in previews" :key="index">
                                <div class="relative aspect-square group/item">
                                    <img :src="src" class="w-full h-full object-cover rounded-lg border border-blue-500/30 shadow-sm">
                                    <button type="button" @click="removeFile(index, 'add_images')" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[8px] shadow-lg opacity-100 sm:opacity-0 group-hover/item:opacity-100 transition-opacity z-10">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </template>

                            <template x-if="previews.length > 0">
                                <div @click="clearAllPreviews('add_images')" class="aspect-square flex flex-col items-center justify-center border-2 border-dashed border-red-200 hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg cursor-pointer transition-all group">
                                    <i class="fa-solid fa-trash-can text-red-400 group-hover:text-red-600 text-xs"></i>
                                    <span class="text-[8px] font-black text-red-400 group-hover:text-red-600 uppercase mt-1">Clear All</span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false; clearAllPreviews('add_images')" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic">បោះបង់</button>
                    <button type="submit" class="px-8 h-10 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-500/20 active:scale-95 transition-all">រក្សាទុកព័ត៌មាន</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
            @click="showEditModal = false; clearAllPreviews('edit_images')"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight text-amber-500">កែប្រែទិន្នន័យអត្ថបទ</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Title, Content & Gallery Images</p>
                </div>
                <button type="button" @click="showEditModal = false; clearAllPreviews('edit_images')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form @submit.prevent="updatePost()" enctype="multipart/form-data">
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ចំណងជើងព័ត៌មាន <span class="text-red-500">*</span></label>
                            <input type="text" x-model="currentPost.title" required placeholder="សូមបញ្ចូលចំណងជើងអត្ថបទ..."
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all font-bold placeholder:font-normal text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ស្ថានភាពផុស <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <select x-model="currentPost.status" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all appearance-none font-bold text-sm">
                                    <option value="draft">សេចក្តីព្រាង</option>
                                    <option value="published">សាធារណៈ</option>
                                    <option value="private">ឯកជន</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">ខ្លឹមសារព័ត៌មានលម្អិត (Content)</label>
                        <div class="dark:text-black" @click.away="if(window.editEditorInstance) currentPost.content = window.editEditorInstance.getData()">
                            <textarea id="edit_editor" class="w-full"></textarea>
                        </div>
                    </div>

                    <div class="space-y-4">

                        <template x-if="currentPost.images && currentPost.images.length > 0">
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">រូបភាពបច្ចុប្បន្ន (ចុចលើរូបដើម្បីលុប)</label>
                                <div class="grid grid-cols-4 sm:grid-cols-6 gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/50 max-h-64 overflow-y-auto custom-scrollbar">
                                    <template x-for="(imagePath, index) in currentPost.images" :key="index">
                                        <div class="relative aspect-square group/old-item">
                                            <img :src="`{{ asset('storage') }}/${imagePath}`" class="w-full h-full object-cover rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm transition-opacity group-hover/old-item:opacity-70">

                                            <button type="button"
                                                @click="deleteOldImage(currentPost.id, imagePath)"
                                                class="absolute inset-0 flex items-center justify-center bg-black/60 opacity-0 group-hover/old-item:opacity-100 transition-opacity rounded-lg z-10"
                                                title="លុបរូបភាពនេះ">
                                                <i class="fa-solid fa-trash-can text-white text-lg"></i>
                                            </button>

                                            <span class="absolute top-1 left-1 bg-gray-900/70 text-white text-[8px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">Old</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">ជ្រើសរើសរូបភាពបន្ថែម (អតិបរមា ២០ សន្លឹក)</label>
                            <div class="relative group">
                                <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed rounded-2xl bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer transition-all border-amber-500/30">
                                    <div class="flex flex-col items-center justify-center pt-4 pb-5">
                                        <i class="fa-solid fa-images text-xl text-amber-500 mb-2"></i>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-black uppercase tracking-widest text-center px-4">
                                            ចុចដើម្បីជ្រើសរើសរូបភាពបន្ថែមចូលអាល់ប៊ុម <br>
                                            <span class="text-amber-400">(JPG, PNG, WEBP, GIF)</span>
                                        </p>
                                    </div>
                                    <input type="file" id="edit_images" name="images[]" multiple class="hidden" accept="image/*" @change="handleFileSelect" />
                                </label>
                            </div>
                        </div>

                        <template x-if="previews.length > 0">
                            <div class="space-y-2 mt-4">
                                <label class="block text-[10px] font-black uppercase text-amber-600 dark:text-amber-400 ml-2 tracking-widest"
                                    x-text="'រូបភាពថ្មីត្រៀម Upload (+' + previews.length + ')'"></label>
                                <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 max-h-60 overflow-y-auto p-1 custom-scrollbar">
                                    <template x-for="(src, index) in previews" :key="index">
                                        <div class="relative aspect-square group/item">
                                            <img :src="src" class="w-full h-full object-cover rounded-lg border border-amber-500/30 shadow-sm">

                                            <button type="button" @click="removeFile(index, 'edit_images')" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[8px] shadow-lg opacity-100 sm:opacity-0 group-hover/item:opacity-100 transition-opacity z-10">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </template>

                                    <div @click="clearAllPreviews('edit_images')" class="aspect-square flex flex-col items-center justify-center border-2 border-dashed border-red-200 hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg cursor-pointer transition-all group">
                                        <i class="fa-solid fa-trash-can text-red-400 group-hover:text-red-600 text-xs"></i>
                                        <span class="text-[8px] font-black text-red-400 group-hover:text-red-600 uppercase mt-1">Clear All</span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false; clearAllPreviews('edit_images')" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic">បោះបង់</button>
                    <button type="submit" class="px-8 h-10 bg-amber-500 hover:bg-amber-600 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-amber-500/20 active:scale-95 transition-all">កែប្រែទិន្នន័យ</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 3. DETAIL MODAL (SHOW DETAIL POST) --}}
<div x-show="showDetailModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
            @click="showDetailModal = false"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all z-10"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            {{-- Modal Header --}}
            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="font-extrabold text-lg dark:text-white uppercase tracking-tight">មើលលម្អិតអត្ថបទព័ត៌មាន</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Article Details & Content Preview</p>
                    </div>
                </div>
                <button type="button" @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            {{-- Modal Body --}}
            <div class="p-8 space-y-6 max-h-[75vh] overflow-y-auto custom-scrollbar">
                
                {{-- Status & Meta Badges --}}
                <div class="flex flex-wrap items-center justify-between gap-4 bg-gray-50 dark:bg-gray-800/60 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 font-medium">
                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-user text-blue-500"></i> <strong class="text-gray-700 dark:text-gray-200" x-text="currentPost.user ? currentPost.user.name : 'អ្នកគ្រប់គ្រង'"></strong></span>
                        <span>•</span>
                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-eye text-emerald-500"></i> <strong class="text-gray-700 dark:text-gray-200" x-text="currentPost.views || 0"></strong> នាក់បានមើល</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <template x-if="currentPost.status === 'published'">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                                <i class="fa-solid fa-circle text-[6px] mr-1"></i> សាធារណៈ (Published)
                            </span>
                        </template>
                        <template x-if="currentPost.status === 'draft'">
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                                <i class="fa-solid fa-circle text-[6px] mr-1"></i> សេចក្តីព្រាង (Draft)
                            </span>
                        </template>
                        <template x-if="currentPost.status === 'private'">
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                                <i class="fa-solid fa-lock text-[8px] mr-1"></i> ឯកជន (Private)
                            </span>
                        </template>
                    </div>
                </div>

                {{-- Post Title --}}
                <h1 class="text-2xl font-black text-gray-900 dark:text-white leading-snug" x-text="currentPost.title"></h1>

                {{-- Post Image Gallery --}}
                <template x-if="currentPost.images && currentPost.images.length > 0">
                    <div class="space-y-2">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider"><i class="fa-solid fa-images mr-1"></i> អាល់ប៊ុមរូបភាព (<span x-text="currentPost.images.length"></span>)</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            <template x-for="(img, idx) in currentPost.images" :key="idx">
                                <a :href="`{{ asset('storage') }}/${img}`" target="_blank" class="aspect-video rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 block group/img relative shadow-sm">
                                    <img :src="`{{ asset('storage') }}/${img}`" class="w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-300" alt="Post Image">
                                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center text-white text-xs">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Post Content Body --}}
                <div class="border-t border-gray-100 dark:border-gray-800 pt-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3"><i class="fa-solid fa-file-lines mr-1"></i> ខ្លឹមសារព័ត៌មានលម្អិត</p>
                    <div class="ck-content dark:text-gray-200 prose dark:prose-invert max-w-none text-sm leading-relaxed" x-html="currentPost.content"></div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center border-t dark:border-gray-800">
                <button type="button" @click="showEditModal = true; showDetailModal = false; openEditModal(currentPost)" class="px-5 h-9 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-pen-to-square"></i> កែប្រែអត្ថបទនេះ
                </button>
                <button type="button" @click="showDetailModal = false" class="px-6 h-9 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold text-xs rounded-xl transition cursor-pointer">
                    បិទ
                </button>
            </div>

        </div>
    </div>
</div>