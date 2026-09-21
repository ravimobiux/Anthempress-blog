document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const primaryMenu = document.querySelector('.primary-menu');
    const navWrapper = document.querySelector('.nav-wrapper');

    if (!menuToggle || !primaryMenu || !navWrapper) {
        return;
    }

    const setMenuState = function (isOpen) {
        primaryMenu.classList.toggle('open', isOpen);
        navWrapper.classList.toggle('is-open', isOpen);
        menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    };

    menuToggle.addEventListener('change', function () {
        setMenuState(menuToggle.checked);
    });

    primaryMenu.addEventListener('click', function (event) {
        if (event.target.closest('a')) {
            setMenuState(false);
            menuToggle.checked = false;
        }
    });

    setMenuState(menuToggle.checked);
});
