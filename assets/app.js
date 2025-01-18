import './bootstrap.js';
import './styles/app.css';

// BOUTON BACK TO TOP
document.addEventListener('DOMContentLoaded', function () {
    let toTopButton = document.getElementById("to-top-button");
    const isSmallScreen = window.matchMedia("(max-width: 640px)");

    function toggleToTopButton() {
        if (!isSmallScreen.matches) { // Si l'écran n'est pas de petite taille
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                toTopButton.classList.remove("hidden");
            } else {
                toTopButton.classList.add("hidden");
            }
        } else {
            // Cache le bouton sur les petits écrans
            toTopButton.classList.add("hidden");
        }
    }

    if (toTopButton) {
        window.onscroll = toggleToTopButton;
        
        // Vérifie la taille d'écran lors du chargement
        isSmallScreen.addEventListener("change", toggleToTopButton);

        window.goToTop = function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
    }
});
