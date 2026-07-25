// resources/js/app.js
import './bootstrap';

import Alpine from 'alpinejs';
import * as bootstrap from 'bootstrap'; 
window.Alpine = Alpine;
window.bootstrap = bootstrap; // supaya bisa dipanggil manual di modal.js (bootstrap.Modal.getOrCreateInstance)

Alpine.start();