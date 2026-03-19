<script>
    window.confirmDelete = (form) => {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'តើអ្នកប្រាកដទេ?',
            text: "ទិន្នន័យនេះនឹងត្រូវលុបជារៀងរហូត",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'លុប',
            cancelButtonText: 'បោះបង់',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: isDark ? '#374151' : '#9ca3af',
            background: isDark ? '#111827' : '#ffffff',
            color: isDark ? '#f3f4f6' : '#111827',
            reverseButtons: true,
            showLoaderOnConfirm: true,

            didOpen: () => {
                const swalContainer = document.querySelector('.swal2-container');
                if (swalContainer) {
                    swalContainer.style.zIndex = '9000';
                }
            },
            heightAuto: false,
            customClass: {
                popup: 'rounded-[2rem] border-none shadow-2xl font-kantumruy',
                title: 'text-xl font-bold',
                htmlContainer: 'text-sm opacity-80',
                confirmButton: 'rounded-2xl px-6 py-3 font-bold text-sm tracking-wide focus:ring-0',
                cancelButton: 'rounded-2xl px-6 py-3 font-bold text-sm tracking-wide focus:ring-0'
            },
            preConfirm: () => {
                return new Promise((resolve) => {
                    setTimeout(() => {
                        resolve(true);
                    }, 500);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>