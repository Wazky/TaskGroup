//file: app/frontend/src/services/AuthService.js

import $, { buildUrl, createBasicAuthHeader } from './ApiConfig.js';    // Import jQuery and helper functions

const AuthRestBase = '/auth';

class AuthService {

    constructor(){
        this.authKey = 'basic_auth_header';
        this.userKey = 'user';
    }

    async register(userData) {
        try {
            const tempPassword = userData.password; // Store the plain password temporarily (BAD PRACTICE)

            const response = await $.ajax({
                url: buildUrl(`${AuthRestBase}/register`),
                method: 'POST',
                data: JSON.stringify(userData)
            });

            console.log("Registered user: (AuthService)", response);
            // Return success response
            return {
                success: true,
                user: {
                    username: response.data.username,
                    email: response.data.email
                },
                message: response.message
            };

        } catch (error) {

            console.log("Registration error: ", error.responseJSON);
            return { 
                success: false,
                error: error.responseJSON ? error.responseJSON.message : '',
                errors: error.responseJSON ? error.responseJSON.errors : {}
            };
        }
    }

    /**
     * Login user with given identifier and password
     * 
     * @param {string} userIdentifier - Username or email
     * @param {string} password - User password
     */
    async login(userIdentifier, password) {
        try {
            const authHeader = createBasicAuthHeader(userIdentifier, password);

            // Make login request
            const response  = await $.ajax({
                url: buildUrl(`${AuthRestBase}/login`),
                method: 'POST',
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('Authorization', authHeader);
                }                
            });

            // If login is successful, store auth info in localStorage
            window.localStorage.setItem(this.authKey, authHeader);
            window.localStorage.setItem(this.userKey, JSON.stringify(response.user));

            // Set up jQuery AJAX to include auth header in future requests
            $.ajaxSetup({
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('Authorization', authHeader);
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
                beforeSend: null
            });
            
            return { 
                success: false, 
                error: error.responseJSON ? error.responseJSON.message : '',                
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
                user: response.data,
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

    async logout() {
        window.localStorage.removeItem(this.authKey);
        window.localStorage.removeItem(this.userKey);

        $.ajaxSetup({
            beforeSend: null
        });

        return { success: true, message: 'Logged out successfully' };
    }



}

export default new AuthService();