//file: app/frontend/src/utils/i18n.js

import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';

// Import translation files
import enTranslation from '../locales/en.json';
import esTranslation from '../locales/es.json';

// Initialize i18n
i18n
    .use(initReactI18next) // Passes i18n down to react-i18next
    .init({
        resources: {
            en: { translation: enTranslation },
            es: { translation: esTranslation },
        },
        lng: 'en', // Default language
        interpolation: {
            escapeValue: false, // React already safes from xss
        }
    });

export default i18n;