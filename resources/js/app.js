<<<<<<< Updated upstream


import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
=======
document.addEventListener("DOMContentLoaded", () => {

    const button = document.getElementById("theme-toggle");

    if (!button) return;

    button.addEventListener("click", () => {

        document.documentElement.classList.toggle("dark");

    });

});
>>>>>>> Stashed changes
