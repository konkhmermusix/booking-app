@extends('layouts.app')
@section('title', 'ព័ត៌មាន និងព្រឹត្តិការណ៍')

@section('content')
<div class="mx-auto">
    <div class="pt-20 text-center mb-30 relative z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
            ព័ត៌មាន និង <span class="text-blue-600">ព្រឹត្តិការណ៍</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
            តាមដានរាល់ព័ត៌មានថ្មីៗ ការផ្សព្វផ្សាយ និងព្រឹត្តិការណ៍ផ្សេងៗ។
        </p>
    </div>

    <section class="py-10 bg-gray-50 dark:bg-[#0b1120] transition-colors duration-300">
        <div class="container mx-auto px-4 max-w-3xl space-y-6">
            {{-- Search Form --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 border border-gray-100 dark:border-gray-800 shadow-sm">
                <form id="search-form" action="{{ route('frontend.posts') }}" method="GET" class="relative" onsubmit="event.preventDefault();">
                    <input type="text" id="search-input" name="search" value="{{ request('search') }}"
                        placeholder="ស្វែងរកព័ត៌មាន ឬព្រឹត្តិការណ៍ផ្សេងៗ..."
                        class="w-full pl-11 pr-16 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-inner">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </div>

                    <button type="button" id="clear-search-btn" class="{{ request('search') ? '' : 'hidden' }} absolute inset-y-0 right-0 pr-4 flex items-center text-xs text-red-500 font-bold hover:underline">
                        សម្អាត
                    </button>
                </form>
            </div>

            {{-- Posts List Container --}}
            <div id="post-list-container" class="transition-opacity duration-300">
                @include('frontend.partials.post_list')
            </div>
        </div>
    </section>
</div>

<script>
    function toggleText(postId) {
        const shortText = document.getElementById('short-text-' + postId);
        const fullText = document.getElementById('full-text-' + postId);
        const btnText = document.getElementById('btn-' + postId);

        if (fullText.classList.contains('hidden')) {
            // បង្ហាញអត្ថបទពេញ និងប្តូរទៅជា បិទវិញ
            fullText.classList.remove('hidden');
            shortText.classList.add('hidden');
            btnText.innerHTML = 'លាក់វិញ';
        } else {
            // លាក់អត្ថបទពេញ និងបង្ហាញអត្ថបទខ្លីវិញ
            fullText.classList.add('hidden');
            shortText.classList.remove('hidden');
            btnText.innerHTML = 'មើលបន្ថែម';
        }
    }

    // AJAX Search and Pagination
    let debounceTimer;
    const searchInput = document.getElementById('search-input');
    const clearSearchBtn = document.getElementById('clear-search-btn');
    const postListContainer = document.getElementById('post-list-container');

    if (searchInput && clearSearchBtn && postListContainer) {
        searchInput.addEventListener('input', function() {
            const query = this.value;
            
            // Toggle clear button
            if (query.trim() !== '') {
                clearSearchBtn.classList.remove('hidden');
            } else {
                clearSearchBtn.classList.add('hidden');
            }
            
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const url = new URL(window.location.href);
                url.searchParams.set('search', query);
                url.searchParams.set('page', 1); // Reset to page 1 on new search
                fetchPosts(url.toString());
            }, 300);
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(debounceTimer);
                const url = new URL(window.location.href);
                url.searchParams.set('search', this.value);
                url.searchParams.set('page', 1);
                fetchPosts(url.toString());
            }
        });

        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            this.classList.add('hidden');
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            url.searchParams.set('page', 1);
            fetchPosts(url.toString());
        });

        // Event delegation for pagination links
        postListContainer.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && (link.href.includes('page=') || link.closest('nav') || link.closest('.pagination'))) {
                e.preventDefault();
                fetchPosts(link.href);
            }
        });

        function fetchPosts(url) {
            postListContainer.style.opacity = '0.5';
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                postListContainer.innerHTML = html;
                postListContainer.style.opacity = '1';
                window.history.pushState(null, '', url);
                
                // Scroll to top of posts list smoothly
                const headerOffset = 100;
                const elementPosition = postListContainer.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            })
            .catch(error => {
                console.error('Error fetching posts:', error);
                postListContainer.style.opacity = '1';
            });
        }

        window.addEventListener('popstate', function() {
            location.reload();
        });
    }
</script>
@endsection