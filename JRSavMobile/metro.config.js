// metro.config.js
const { getDefaultConfig } = require('expo/metro-config');

const config = getDefaultConfig(__dirname);

// Configuration pour supporter les WebSockets et Reverb
config.transformer = {
  ...config.transformer,
  minifierConfig: {
    compress: {
      drop_console: false, // Garder les logs en développement
    },
  },
};

// Ajouter les extensions supportées
config.resolver = {
  ...config.resolver,
  sourceExts: [...config.resolver.sourceExts, 'cjs', 'mjs'],
  assetExts: [...config.resolver.assetExts, 'db', 'sqlite'],
};

// Configuration du serveur Metro
config.server = {
  ...config.server,
  port: 8082,
  enhanceMiddleware: (middleware) => {
    return (req, res, next) => {
      // Ajouter les headers CORS pour le développement
      res.setHeader('Access-Control-Allow-Origin', '*');
      res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
      res.setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
      return middleware(req, res, next);
    };
  },
};

module.exports = config;