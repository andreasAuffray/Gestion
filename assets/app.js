import './bootstrap.js';
import 'bootstrap/dist/css/bootstrap.min.css';

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

import './styles/app.css';

document.addEventListener('DOMContentLoaded', () => {
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
    
    dropdownElementList.forEach(element => {
        new bootstrap.Dropdown(element);
    });
});

const setBootstrapTheme = () => {
    const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    document.documentElement.setAttribute('data-bs-theme', prefersDarkMode ? 'dark' : 'light');
};

setBootstrapTheme();

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', setBootstrapTheme);
