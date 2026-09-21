document.addEventListener('DOMContentLoaded', function () {
    const filterControls = document.querySelectorAll(
        '.content-type-button, .subject-categories a, .dropdown-content a'
    );

    filterControls.forEach(function (control) {
        control.addEventListener('click', function () {
            const blogGrid = document.querySelector('.posts-grid, .posts-row');

            if (blogGrid) {
                blogGrid.style.opacity = '0.5';
                blogGrid.style.pointerEvents = 'none';
            }
        });
    });
});
