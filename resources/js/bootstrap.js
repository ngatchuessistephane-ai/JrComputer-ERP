// resources/js/bootstrap.js
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}
// ⚠️ NE PAS importer Alpine ici (déjà dans app.js)
// ⚠️ NE PAS importer Bootstrap ici (déjà via CDN)
// ⚠️ NE PAS importer Echo ici (déjà dans app.js)