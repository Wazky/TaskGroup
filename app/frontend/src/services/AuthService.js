//file: app/frontend/src/services/AuthService.js

import $, { buildUrl, createBasicAuthHeader } from './ApiConfig.js';    // Import jQuery and helper functions

const AuthRestBase = '/auth';

class AuthService {

    constructor(){
        this.authKey = 'basic_auth_header';
        this.userKey = 'user';
    }

    handleError(error) {
        console.error('AuthService Error:', error);
        
        // Manejo específico de errores de jQuery AJAX
        if (error.status === 401) {
            return 'Credenciales incorrectas o sesión expirada';
        } 
        else if (error.status === 404) {
            return 'Endpoint no encontrado. Verifica la URL.';
        }
        else if (error.status === 500) {
            return 'Error interno del servidor';
        }
        else if (error.status === 0) {
            return 'No se pudo conectar al servidor. Verifica tu conexión.';
        }
        else if (error.responseJSON && error.responseJSON.error) {
            return error.responseJSON.error;
        }
        else if (error.responseText) {
            return error.responseText;
        }
        else {
            return 'Error desconocido. Intenta nuevamente.';
        }
    }

    async register(userData) {
        try {
            const tempPassword = userData.password; // Store the plain password temporarily (BAD PRACTICE)

            const response = await $.ajax({
                url: buildUrl(`${AuthRestBase}/register`),
                method: 'POST',
                data: JSON.stringify(userData)
            });

            // Restore the plain password (BAD PRACTICE)
            const loginResponse = await this.login((userData.username || userData.email), tempPassword); 

            return loginResponse;
        } catch (error) {
            return this.handleError(error);
        }
    }

    async login(userIdentifier, password) {
        try {
            const response  = await $.ajax({
                url: buildUrl(`${AuthRestBase}/login`),
                method: 'POST',
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('Authorization', createBasicAuthHeader(userIdentifier, password));
                }                
            });

            // If login is successful, store auth info in localStorage
            window.localStorage.setItem(this.authKey, createBasicAuthHeader(userIdentifier, password));
            window.localStorage.setItem(this.userKey, JSON.stringify(response.user));

            // Set up jQuery AJAX to include auth header in future requests
            $.ajaxSetup({
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('Authorization', createBasicAuthHeader(userIdentifier, password));
                }
            });

            // Return success response
            return { 
                success: true, 
                user: {username: response.data.username},
                message: response.message
            };

        } catch (error) {
            // Clear any stored auth info on failed login
            window.localStorage.removeItem(this.authKey);

            // Reset AJAX setup to avoid sending invalid auth headers
            $.ajaxSetup({
                beforeSend: (xhr) => {}
            });

            return { 
                success: false, 
                error: this.handleError(error)  
            };
        }
    }

    async loginWithSessionData() {
        const authHeader = window.localStorage.getItem(this.authKey);
        
        if (!authHeader) {
            return { 
                success: false, 
                error: 'No session data found' 
            };
        }

        try {
            const response  = await $.ajax({
                url: buildUrl(`${AuthRestBase}/login`),
                method: 'POST',
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('Authorization', authHeader);
                }                
            });

            $.ajaxSetup({
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('Authorization', authHeader);
                }
            });

            return {
                success: true,
                data: response
            };

        } catch (error) {
            // Clear any stored auth info on failed login
            window.localStorage.removeItem(this.authKey);

            // Reset AJAX setup to avoid sending invalid auth headers
            $.ajaxSetup({
                beforeSend: (xhr) => {}
            });

            return { 
                success: false, 
                error: this.handleError(error)  
            };  
        }
    }

    async logout() {
        window.localStorage.removeItem(this.authKey);
        window.localStorage.removeItem(this.userKey);

        $.ajaxSetup({
            beforeSend: (xhr) => {}
        });

    }

}

export default new AuthService();