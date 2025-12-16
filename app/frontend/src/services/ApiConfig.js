//file: app/frontend/src/services/ApiConfig.js

import $ from 'jquery';

const BACKEND_SERVER_URL = 'http://localhost/TaskGroup/app';  // Base backend server URL
export const API_BASE_URL = `${BACKEND_SERVER_URL}/api`;    // Base API URL

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
            if (window.location.pathname !== '/login') {
                window.location.href = '/login';
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


