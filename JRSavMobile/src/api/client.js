// JRSavMobile/src/api/client.js
import axios from 'axios';
import * as SecureStore from 'expo-secure-store';
import { API_BASE_URL } from '../../config';

// ✅ Utiliser la variable d'environnement centralisée
const API_URL = API_BASE_URL;

const api = axios.create({
  baseURL: API_URL,
  timeout: 60000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Connection': 'close', // ✅ AJOUT — force une nouvelle connexion TCP à chaque requête
  }
});

// Intercepteur pour ajouter le token
api.interceptors.request.use(
  async (config) => {
    try {
      const token = await SecureStore.getItemAsync('token');
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
        console.log(`🔑 Token ajouté: ${token.substring(0, 20)}...`);
      } else {
        console.log('⚠️ Pas de token trouvé');
      }
      console.log(`📤 [${config.method?.toUpperCase()}] ${config.url}`);
      return config;
    } catch (error) {
      console.error('Erreur interceptor:', error);
      return config;
    }
  },
  (error) => Promise.reject(error)
);

// Intercepteur pour les réponses
api.interceptors.response.use(
  (response) => {
    console.log(`📥 [${response.status}] ${response.config.url}`);
    return response;
  },
  async (error) => {
    if (error.response) {
      console.error(`❌ Erreur ${error.response.status}: ${error.response.config?.url}`);
      console.error(`   Message: ${JSON.stringify(error.response.data)}`);

      if (error.response.status === 401) {
        console.log('🔐 Token expiré, déconnexion...');
        await SecureStore.deleteItemAsync('token');
        await SecureStore.deleteItemAsync('user');
      }
    } else if (error.request) {
      console.error('❌ Pas de réponse du serveur.');
      console.error(`   URL: ${error.config?.url}`);
      console.error(`   Vérifiez que: php artisan serve --host=0.0.0.0 --port=8000`);
    } else {
      console.error('❌ Erreur:', error.message);
    }
    return Promise.reject(error);
  }
);

export default api;