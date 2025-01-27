import './bootstrap.js';
import './styles/app.css';
import { shouldPerformTransition, performTransition } from 'turbo-view-transitions';

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

document.addEventListener('turbo:before-render', (event) => {
    if (shouldPerformTransition()) {
        event.preventDefault();
        performTransition(document.body, event.detail.newBody, async () => {
            await event.detail.resume();
        });
    }
});
document.addEventListener('turbo:load', () => {
    // View Transitions don't play nicely with Turbo cache
    if (shouldPerformTransition()) Turbo.cache.exemptPageFromCache();
});
document.addEventListener('turbo:before-frame-render', (event) => {
    if (shouldPerformTransition() && !event.target.hasAttribute('data-skip-transition')) {
        event.preventDefault();
        performTransition(event.target, event.detail.newFrame, async () => {
            await event.detail.resume();
        });
    }
});
