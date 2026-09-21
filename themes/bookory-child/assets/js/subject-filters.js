document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.mobile-dropdown select').forEach(function (dropdown) {
        dropdown.addEventListener('change', function () {
            if (this.value) {
                window.location.href = this.value;
            }
        });
    });
});
