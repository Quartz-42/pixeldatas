import './bootstrap.js';
import './styles/app.css';

// BOUTON BACK TO TOP
document.addEventListener('DOMContentLoaded', function () {
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
});

// Fonction pour normaliser les chaînes de caractères
function normalizeString(str) {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
}

// Fonction pour rechercher les Pokémon
function searchPokemons(attribute, query, noResultsId) {
    let pokemonCards = document.querySelectorAll('.pokemon-card');
    let noResults = document.getElementById(noResultsId);
    let hasResults = false;

    query = normalizeString(query);

    pokemonCards.forEach(function (card) {
        let value = card.getAttribute(attribute);
        value = normalizeString(value);

        if (value.includes(query)) {
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
}

// RECHERCHE POKEMON PAR NOM
document.addEventListener('DOMContentLoaded', function () {
    let inputName = document.getElementById('search-input-name');
    if (inputName) {
        inputName.addEventListener('input', function () {
            searchPokemons('data-name', this.value.toLowerCase(), 'no-results');
        });
    }
});

// RECHERCHE POKEMON PAR TYPE
document.addEventListener('DOMContentLoaded', function () {
    let inputType = document.getElementById('search-input-type');
    if (inputType) {
        inputType.addEventListener('input', function () {
            searchPokemons('data-type', this.value.toLowerCase(), 'no-results2');
        });
    }
});

// FORCER LE RELOAD DE LA PAGE
document.addEventListener('DOMContentLoaded', function () {
    let retourLien = document.querySelectorAll('.retour-lien');
    retourLien.forEach(function (lien) {
        lien.addEventListener('click', function (event) {
            event.preventDefault();
            window.location.href = this.href;
        });
    });
});
