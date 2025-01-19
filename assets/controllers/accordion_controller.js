import { Controller } from '@hotwired/stimulus';
import { Accordion } from 'flowbite';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        const accordionElement = document.getElementById('accordion');

        // Définit les éléments de l'accordéon
        const accordionItems = [
            {
                id: 'accordion-1',
                triggerEl: document.querySelector('#accordion-1 button'),
                targetEl: document.querySelector('#accordion-body-1'),
                active: false
            },
           ];

        // Configuration par défaut
        const options = {
            alwaysOpen: true,
            activeClasses: 'bg-gray-100 dark:bg-gray-800 text-blue-900 dark:text-blue',
            inactiveClasses: 'text-gray-500 dark:text-gray-400'
        };

        // Initialise l'accordéon
        new Accordion(accordionElement, accordionItems, options);
    }
}
