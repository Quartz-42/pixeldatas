import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

//BOUTON BACK TO TOP
let toTopButton = document.getElementById("to-top-button");

if (toTopButton) {
    window.onscroll = function () {
        if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
            toTopButton.classList.remove("hidden");
        } else {
            toTopButton.classList.add("hidden");
        }
    };

    window.goToTop = function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };
}

//RECHERCHE POKEMON
//PAR NOM
document.getElementById('search-input-name').addEventListener('input', function () {
    let searchQuery = this.value.toLowerCase();
    let pokemonCards = document.querySelectorAll('.pokemon-card');
    let noResults = document.getElementById('no-results');
    let hasResults = false;

    pokemonCards.forEach(function (card) {
        let pokemonName = card.getAttribute('data-name');

        if (pokemonName.includes(searchQuery)) {
            card.style.display = 'block';
            hasResults = true;
        } else {
            card.style.display = 'none';
        }
    });

    if (hasResults) {
        noResults.classList.add('hidden');
    } else {
        noResults.classList.remove('hidden');
    }
});

//PAR TYPE
document.getElementById('search-input-type').addEventListener('input', function () {
    let searchQuery = this.value.toLowerCase();
    let pokemonCards = document.querySelectorAll('.pokemon-card');
    let noResults2 = document.getElementById('no-results2');
    let hasResults = false;

    pokemonCards.forEach(function (card) {
        let pokemonName = card.getAttribute('data-type');

        if (pokemonName.includes(searchQuery)) {
            card.style.display = 'block';
            hasResults = true;
        } else {
            card.style.display = 'none';
        }
    });

    if (hasResults) {
        noResults2.classList.add('hidden');
    } else {
        noResults2.classList.remove('hidden');
    }
});

//FORCER LE RELOAD DE LA PAGE
$(document).ready(function () {
    $('.retour-lien').click(function (event) {
        event.preventDefault();
        window.location.href = this.href;
    });
});