<!-- ADD TOUR MODAL -->

<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">

        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
            @click="showAddModal=false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden">

            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែថ្មី</h3>

                <button
                    @click="showAddModal=false"
                    class="text-gray-400 hover:text-gray-600 text-3xl">
                    × </button>

            </div>

            <form action="{{ route('tours.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-8 space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="col-span-2">
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">
                                ឈ្មោះកន្លែង
                            </label>

                            <input type="text"
                                name="name"
                                required
                                class="w-full h-[52px] px-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white">

                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">
                                ចម្ងាយ
                            </label>

                            <input type="text"
                                name="distance"
                                placeholder="15 mins from hotel"
                                class="w-full h-[52px] px-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white">

                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">
                                Google Map Link
                            </label>

                            <input type="url"
                                name="google_map_link"
                                placeholder="https://maps.google.com/..."
                                class="w-full h-[52px] px-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white">

                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">
                                រូបភាព
                            </label>

                            <input type="file"
                                name="image"
                                class="w-full h-[52px] px-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white">

                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">
                                Description
                            </label>

                            <textarea name="description"
                                rows="3"
                                class="w-full p-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white"></textarea>

                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold mb-2 dark:text-gray-300">
                                Status
                            </label>

                            <select name="status"
                                class="w-full h-[52px] px-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white">

                                <option value="1">សកម្ម</option>
                                <option value="0">ផ្អាក</option>

                            </select>
                        </div>

                    </div>

                </div>

                <div class="px-8 py-6 bg-gray-50 dark:bg-gray-800 flex justify-end gap-3">

                    <button type="button"
                        @click="showAddModal=false"
                        class="px-6 py-3 font-bold text-gray-500">
                        បោះបង់ </button>

                    <button type="submit"
                        class="px-10 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl">
                        រក្សាទុក </button>

                </div>

            </form>
        </div>
    </div>
</div>

<!-- EDIT TOUR MODAL -->

<div x-show="showEditModal" class="fixed inset-0 z-[60]" x-cloak>

    <div class="flex items-center justify-center min-h-screen px-4">

        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
            @click="showEditModal=false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl border dark:border-gray-800 overflow-hidden">

            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-gray-800/50">

                <h3 class="font-black text-xl dark:text-white">
                    កែប្រែកន្លែង៖
                    <span x-text="currentTour.name"></span>
                </h3>

                <button
                    @click="showEditModal=false"
                    class="text-gray-400 text-3xl">
                    × </button>

            </div>

            <form :action="`{{ url('admin/tours') }}/${currentTour.id}`"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="p-8 space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="col-span-2">
                            <label class="text-sm font-bold mb-2 dark:text-gray-300">
                                ឈ្មោះកន្លែង
                            </label>

                            <input type="text"
                                name="name"
                                x-model="currentTour.name"
                                class="w-full h-[52px] px-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white">

                        </div>

                        <div>
                            <label class="text-sm font-bold mb-2 dark:text-gray-300">
                                ចម្ងាយ
                            </label>

                            <input type="text"
                                name="distance"
                                x-model="currentTour.distance"
                                class="w-full h-[52px] px-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white">

                        </div>

                        <div>
                            <label class="text-sm font-bold mb-2 dark:text-gray-300">
                                Google Map
                            </label>

                            <input type="url"
                                name="google_map_link"
                                x-model="currentTour.google_map_link"
                                class="w-full h-[52px] px-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white">

                        </div>

                        <div class="col-span-2">
                            <label class="text-sm font-bold mb-2 dark:text-gray-300">
                                រូបភាពថ្មី
                            </label>

                            <input type="file"
                                name="image"
                                class="w-full h-[52px] px-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white">

                        </div>

                        <div class="col-span-2">
                            <label class="text-sm font-bold mb-2 dark:text-gray-300">
                                Description
                            </label>

                            <textarea name="description"
                                rows="3"
                                x-model="currentTour.description"
                                class="w-full p-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white"></textarea>

                        </div>

                        <div class="col-span-2">
                            <label class="text-sm font-bold mb-2 dark:text-gray-300">
                                Status
                            </label>

                            <select name="status"
                                x-model="currentTour.status"
                                class="w-full h-[52px] px-4 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white">

                                <option value="1">សកម្ម</option>
                                <option value="0">ផ្អាក</option>

                            </select>
                        </div>

                    </div>

                </div>

                <div class="px-8 py-6 bg-gray-50 dark:bg-gray-800 flex justify-end gap-3">

                    <button type="button"
                        @click="showEditModal=false"
                        class="px-6 py-3 font-bold text-gray-500">
                        បោះបង់ </button>

                    <button type="submit"
                        class="px-10 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl">
                        ធ្វើបច្ចុប្បន្នភាព </button>

                </div>

            </form>

        </div>
    </div>
</div>

<!-- DETAIL MODAL -->

<div x-show="showDetailModal"
    class="fixed inset-0 z-[60]"
    x-cloak>

    <div class="flex items-center justify-center min-h-screen px-4">

        <div class="fixed inset-0 bg-gray-900/60"
            @click="showDetailModal=false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-xl border dark:border-gray-800 overflow-hidden">

            <div class="relative h-56 overflow-hidden">

                <img
                    :src="currentTour.image ? '/storage/'+currentTour.image : '/images/no-image.jpg'"
                    class="w-full h-full object-cover">

            </div>

            <div class="p-6">

                <h2 class="text-2xl font-bold mb-2 dark:text-white"
                    x-text="currentTour.name"></h2>

                <p class="text-sm text-gray-500 mb-2"
                    x-text="currentTour.distance"></p>

                <a
                    :href="currentTour.google_map_link"
                    target="_blank"
                    class="text-blue-600 text-sm">
                    Open Google Map </a>

                <p class="mt-4 text-sm dark:text-gray-300"
                    x-text="currentTour.description"></p>

                <button
                    @click="showDetailModal=false"
                    class="w-full mt-6 py-3 bg-gray-900 text-white rounded-2xl font-bold">

                    បិទ

                </button>

            </div>

        </div>

    </div>
</div>