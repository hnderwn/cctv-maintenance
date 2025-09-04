document.addEventListener('DOMContentLoaded', function () {
  const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
  const body = document.body;

  // Buat dan sisipkan overlay ke body
  const overlay = document.createElement('div');
  overlay.className = 'sidebar-overlay';
  body.appendChild(overlay);

  if (sidebarToggleBtn) {
    sidebarToggleBtn.addEventListener('click', function (e) {
      e.preventDefault();

      // Cek lebar layar untuk menentukan aksi
      if (window.innerWidth > 768) {
        // --- LOGIKA DESKTOP ---
        // Toggle class untuk sidebar mini
        body.classList.toggle('sidebar-mini');
      } else {
        // --- LOGIKA MOBILE ---
        // Toggle class untuk membuka/menutup sidebar mobile
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

});
