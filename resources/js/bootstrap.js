import axios from 'axios'; 
window.axios = axios; // biar bisa dipakai global
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}


import $ from 'jquery';
window.$ = window.jQuery = $;

// Tambahkan ini
import 'datatables.net';
import 'datatables.net-bs5';