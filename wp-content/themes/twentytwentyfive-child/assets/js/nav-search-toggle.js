document.addEventListener('DOMContentLoaded', function() {
    // Get all search toggle elements (mobile and desktop)
    const searchToggles = document.querySelectorAll('#toggle-search');
    const searchBar = document.getElementById('nav-search-bar');
    
    // Close search bar when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-search-inline, .nav-search-inline-desktop, #nav-search-bar')) {
            searchBar.style.display = 'none';
        }
    });
    
    // Handle both mobile and desktop search toggles
    searchToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            searchBar.style.display = searchBar.style.display === 'block' ? 'none' : 'block';
            
            // Focus the input field when search bar appears
            if (searchBar.style.display === 'block') {
                setTimeout(() => {
                    searchBar.querySelector('input[type="search"]').focus();
                }, 50);
            }
        });
    });
    
    // Close button functionality (if you have one)
    const closeSearch = document.querySelector('.close-search');
    if (closeSearch) {
        closeSearch.addEventListener('click', function(e) {
            e.preventDefault();
            searchBar.style.display = 'none';
        });
    }
});
