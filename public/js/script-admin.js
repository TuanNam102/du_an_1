document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.getElementById('admin-sidebar');
  const menuBtn = document.getElementById('mobile-menu-btn');

  if (menuBtn && sidebar) {
    menuBtn.addEventListener('click', function () {
      const isOpen = sidebar.style.display === 'block';
      sidebar.style.display = isOpen ? 'none' : 'block';
    });
  }

  const animateTargets = [];
  document.querySelectorAll('.stat-card, .recent-orders-card, .table-card-admin, .admin-table tbody tr').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(8px)';
    animateTargets.push(el);
  });

  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const el = entry.target;
        el.style.transition = 'opacity 300ms ease, transform 300ms ease';
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
        io.unobserve(el);
      }
    });
  }, { threshold: 0.1 });

  animateTargets.forEach(el => io.observe(el));
});

