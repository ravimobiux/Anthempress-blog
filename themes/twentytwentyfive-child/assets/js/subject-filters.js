document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.querySelector('.mobile-dropdown');
    if (dropdown) {
        dropdown.addEventListener('change', function() {
            if (this.value) {
                window.location.href = this.value;
            }
        });
    }
});
