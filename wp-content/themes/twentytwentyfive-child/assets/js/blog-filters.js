document.addEventListener('DOMContentLoaded', function() {
    // Add loading state when filters change
    const filterForm = document.querySelector('.filter-form');
    if (filterForm) {
        filterForm.addEventListener('change', function() {
            const blogGrid = document.querySelector('.blog-grid');
            if (blogGrid) {
                blogGrid.style.opacity = '0.5';
                blogGrid.style.pointerEvents = 'none';
            }
        });
    }
});
