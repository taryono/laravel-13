// resources/js/app.js
import './bootstrap';

import Alpine from 'alpinejs';
import * as bootstrap from 'bootstrap'; 
import Swal from 'sweetalert2';
// jadikan global supaya bisa dipanggil langsung di blade/inline script
window.Swal = Swal;
window.Alpine = Alpine;
window.bootstrap = bootstrap; // supaya bisa dipanggil manual di modal.js (bootstrap.Modal.getOrCreateInstance)

Alpine.start();