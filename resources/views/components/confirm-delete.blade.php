<script>
    window.confirmDelete = (target, url = null) => {
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
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if (target && typeof target.submit === 'function') {
                    target.submit();
                } else if (typeof target === 'string' || typeof target === 'number') {
                    let form = document.getElementById('delete-form');
                    let targetUrl = url || `${window.location.pathname.replace(/\/$/, '')}/${target}`;
                    
                    if (form) {
                        form.action = targetUrl;
                        form.submit();
                    } else {
                        const tempForm = document.createElement('form');
                        tempForm.method = 'POST';
                        tempForm.action = targetUrl;

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        if (csrfToken) {
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = csrfToken;
                            tempForm.appendChild(csrfInput);
                        }

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        tempForm.appendChild(methodInput);

                        document.body.appendChild(tempForm);
                        tempForm.submit();
                    }
                }
            }
        });
    }
</script>

<script>
    // បង្កើតជា Global Function សម្រាប់ហៅប្រើគ្រប់ទីកន្លែង
    // window.confirmDelete = (form) => {
    //     const isDark = document.documentElement.classList.contains('dark');

    //     Swal.fire({
    //         title: 'តើអ្នកប្រាកដទេ?',
    //         text: "ទិន្នន័យនេះនឹងត្រូវលុបជារៀងរហូត!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonText: 'យល់ព្រម, លុបវា!',
    //         cancelButtonText: 'បោះបង់',
    //         confirmButtonColor: '#ef4444',
    //         cancelButtonColor: isDark ? '#374151' : '#9ca3af',
    //         background: isDark ? '#111827' : '#ffffff',
    //         color: isDark ? '#f3f4f6' : '#111827',
    //         reverseButtons: true,
    //         showLoaderOnConfirm: true,
    //         heightAuto: false,
    //         didOpen: () => {
    //             const container = Swal.getContainer();
    //             if (container) container.style.zIndex = '9999';
    //         },
    //         customClass: {
    //             popup: 'rounded-[2rem] border-none shadow-2xl font-kantumruy',
    //             title: 'text-xl font-bold',
    //             htmlContainer: 'text-sm opacity-80',
    //             confirmButton: 'rounded-2xl px-6 py-3 font-bold text-sm tracking-wide focus:ring-0',
    //             cancelButton: 'rounded-2xl px-6 py-3 font-bold text-sm tracking-wide focus:ring-0'
    //         },
    //         preConfirm: () => {
    //             return new Promise((resolve) => {
    //                 setTimeout(() => resolve(true), 500); // បង្ហាញ Loading បន្តិចឱ្យមើលទៅរលូន
    //             });
    //         }
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             // ប្រសិនបើ User ចុច Confirm វានឹង Submit form នោះភ្លាមៗ
    //             form.submit();
    //         }
    //     });
    // }
</script>