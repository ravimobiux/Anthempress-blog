document.addEventListener('DOMContentLoaded', function () {
    const searchToggles = document.querySelectorAll('[data-nav-search-toggle]');
    const searchBar = document.getElementById('nav-search-bar');

    if (!searchBar) {
        return;
    }

    function closeSearch() {
        searchBar.hidden = true;
        searchBar.classList.remove('is-visible');
    }

    searchToggles.forEach(function (toggle) {
        toggle.addEventListener('click', function (event) {
            event.preventDefault();
            const isOpen = !searchBar.hidden;

            searchBar.hidden = isOpen;
            searchBar.classList.toggle('is-visible', !isOpen);

            if (!isOpen) {
                window.setTimeout(function () {
                    const input = searchBar.querySelector('input[type="search"]');
                    if (input) {
                        input.focus();
                    }
                }, 50);
            }
        });
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('[data-nav-search-toggle], #nav-search-bar')) {
            closeSearch();
        }
    });
});
