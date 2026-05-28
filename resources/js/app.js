import './bootstrap';
import { initScrollExperience } from './scroll-experience';

// Alpine is managed by Livewire 4 — do not start it manually here.
// Livewire 4 bundles and starts Alpine via @livewireScripts.

// Boot scroll experience on pages that opt-in via [data-scroll-experience]
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('[data-scroll-experience]')) {
        initScrollExperience();
    }
});
