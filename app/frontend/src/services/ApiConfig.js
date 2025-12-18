//file: app/frontend/src/services/ApiConfig.js

import $ from 'jquery';
import { ROUTES } from '../constants/routes.js';
import { useNavigate } from 'react-router-dom';

const BACKEND_SERVER_URL = 'http://localhost/TaskGroup/app';  // Base backend server URL

//export const API_BASE_URL = `${BACKEND_SERVER_URL}/api`;    // Base API URL

// En desarrollo, usa rutas relativas
export const API_BASE_URL = import.meta.env.DEV 
  ? '/api'  // Usará el proxy de Vite
  : 'http://localhost/TaskGroup/app/api';  // En producción


// Set up jQuery AJAX defaults
$.ajaxSetup({
    contentType: 'application/json',
    dataType: 'json',
    cache: false,

    // Global error handling 
    error: function(xhr, status, error) {
        console.error('AJAX Error: ' + status + ' - ' + error);

        if (xhr.status === 401) {
            // Handle unauthorized access globally
            localStorage.removeItem('basic_auth');
            localStorage.removeItem('user');

            // Redirect to login page
            if (window.location.pathname !== ROUTES.LOGIN) {
                window.location.href = ROUTES.LOGIN;
            }
        } 

    }
});

// Helper function to build full API URLs
export const buildUrl = (endpoint) => `${API_BASE_URL}${endpoint}`;

export const createBasicAuthHeader = (username, password) => {
    return `Basic ${btoa(`${username}:${password}`)}`;
}

export default $;


