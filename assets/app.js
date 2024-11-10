import './bootstrap.js';
import './styles/app.css';

// BOUTON BACK TO TOP
document.addEventListener('DOMContentLoaded', function () {
    let toTopButton = document.getElementById("to-top-button");
    const isSmallScreen = window.matchMedia("(max-width: 640px)");

    function toggleToTopButton() {
        if (!isSmallScreen.matches) { // Si l'écran n'est pas de petite taille
            if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
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

// Fonction pour normaliser les chaînes de caractères
function normalizeString(str) {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
}

// Fonction pour rechercher les Pokémon avec plusieurs critères
function searchPokemonsMulti(criteria, noResultsId) {
    let pokemonCards = document.querySelectorAll('.pokemon-card');
    let noResults = document.getElementById(noResultsId);
    let hasResults = false;

    pokemonCards.forEach(function (card) {
        let matches = true;

        for (let key in criteria) {
            let value = card.getAttribute(key);
            value = normalizeString(value);
            
            if (!value.includes(criteria[key])) {
                matches = false;
                break;
            }
        }

        if (matches) {
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

// Mise à jour de la recherche
function updateSearch() {
    const nameValue = normalizeString(document.getElementById('search-input-name').value);
    const typeValue = normalizeString(document.getElementById('search-input-type').value);
    const genValue = normalizeString(document.getElementById('search-input-gen').value);

    let criteria = {};
    if (nameValue) {
        criteria['data-name'] = nameValue;
    }
    if (typeValue) {
        criteria['data-type'] = typeValue;
    }
    if (genValue) {
        criteria['data-gen'] = genValue;
    }

    searchPokemonsMulti(criteria, 'no-results');
}

// Réinitialiser les filtres
function resetFilters() {
    document.getElementById('search-input-name').value = '';
    document.getElementById('search-input-type').value = '';
    document.getElementById('search-input-gen').value = '';

    let pokemonCards = document.querySelectorAll('.pokemon-card');
    pokemonCards.forEach(function (card) {
        card.style.display = 'block';
    });

    document.getElementById('no-results').classList.add('hidden');
}

// Ajouter des écouteurs d'événements pour les champs de recherche
document.addEventListener('DOMContentLoaded', function () {
    const inputName = document.getElementById('search-input-name');
    const inputType = document.getElementById('search-input-type');
    const inputGen = document.getElementById('search-input-gen');
    const resetButton = document.getElementById('reset-filters');

    if (inputName) {
        inputName.addEventListener('input', updateSearch);
    }
    if (inputType) {
        inputType.addEventListener('change', updateSearch);
    }
    if (inputGen) {
        inputGen.addEventListener('change', updateSearch);
    }
    if (resetButton) {
        resetButton.addEventListener('click', resetFilters);
    }
});
