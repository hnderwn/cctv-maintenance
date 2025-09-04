<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
        const body = document.body;

        // Cek apakah overlay sudah ada, jika belum, buat baru
        let overlay = document.querySelector('.sidebar-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            body.appendChild(overlay);
        }

        if (sidebarToggleBtn) {
            sidebarToggleBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Cek lebar layar untuk menentukan aksi
                if (window.innerWidth > 768) {
                    // --- LOGIKA DESKTOP ---
                    body.classList.toggle('sidebar-mini');
                } else {
                    // --- LOGIKA MOBILE ---
                    body.classList.toggle('sidebar-mobile-open');
                }
            });
        }

        // Tambahkan event listener ke overlay untuk menutup sidebar mobile
        overlay.addEventListener('click', function() {
            if (body.classList.contains('sidebar-mobile-open')) {
                body.classList.remove('sidebar-mobile-open');
            }
        });

        // Script untuk expandable rows (biarkan saja, tidak bentrok)
        const expandableRows = document.querySelectorAll('.expandable-row');
        expandableRows.forEach(row => {
            const actions = row.querySelectorAll('td,.text-center');
            actions.forEach(action => {
                action.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            });
        });
    });
</script>


</body>

</html>