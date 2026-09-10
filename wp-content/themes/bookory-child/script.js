document.addEventListener("DOMContentLoaded", function() {
    const toggle = document.querySelector(".search-toggle");
    const form   = document.querySelector(".search-form");

    if(toggle && form){
        toggle.addEventListener("click", function(e){
            e.preventDefault();
            form.classList.toggle("active");
        });
    }
});
