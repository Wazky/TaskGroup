//file: app/frontend/src/hooks/useAuth.js

import { useState, useEffect, useCallback } from 'react';
import AuthService from '../services/AuthService.js';

export const useAuth = () => {

    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    /**
     * Check for existing session on component mount
     */
    useEffect(() => {
        checkSession();
    }, []);

    /**
     * Check for existing user session
     */
    const checkSession = useCallback(async () => {
        try {
            setLoading(true);
            setError(null);

            const result = await AuthService.loginWithSessionData();
            
            console.log("Session check result: ", result);
            if (result.success) {
                setUser(result.user);
            } else {
                setUser(null);
            }
        } catch (error) {
            setError(error.message || "Error checking session");
            setUser(null);
        } finally {
            setLoading(false);            
        }
    }, []);

    /**
     * Login function
     */
    const login = useCallback(async (userIdentifier, password) => {
        try {
            // Start loading state and clear previous errors
            setLoading(true);
            setError(null);

            // Call AuthService login
            const result = await AuthService.login(userIdentifier, password);

            // Handle login result

            // Login successful
            if (result.success) {   
                setUser(result.user);
                return { success: true, user: result.user };

            // Login failed
            } else {    
                setError(result.error);
                return { success: false, error: result.error };
            }

        // Error during login
        } catch (error) {
            const errorMsg = error.message || "auth.login.form.formErrors.loginFailed";
            setError(errorMsg);
            return { success: false, error: errorMsg };

        // Finally block to reset loading state
        } finally {
            setLoading(false);
        }
    }, []);

    const register = useCallback(async (userData) => {
        try {
            // Start loading state and clear previous errors
            setLoading(true);
            setError(null);

            const result = await AuthService.register(userData);            
            
            // Handle registration result

            // Registration successful
            if (result.success) {   
                setUser(result.user.username);
                return { success: true, user: result.user.username };
            
            // Registration failed
            } else {                
                setError(result.error);
                return { 
                    success: false, 
                    error: result.error,
                    errors: result.errors
                };
            }            

        } catch (error) {
            const errorMsg = error.message || 'auth.register.form.formErrors.registrationFailed';
            setError(errorMsg);
            return { 
                success: false, 
                error: errorMsg };

        // Finally block to reset loading state
        } finally {
        setLoading(false);
    }
    }, []);

    const logout = useCallback(async () => {
        try {
            setLoading(true);

            await AuthService.logout();

            setUser(null);
            setError(null);
            return { success: true };

        } catch (error) {
            const errorMsg = error.message || "Logout failed";
            setError(errorMsg);
            return { success: false, error: errorMsg };

        } finally {
            setLoading(false);
        }
    }, []);

    const clearError = useCallback(() => {
        setError(null);
    }, []);

    return {
        user,
        loading,
        error,        
        isAuthenticated: !!user,
        login,
        logout,
        register,
        checkSession,
        
        clearError
    }
};