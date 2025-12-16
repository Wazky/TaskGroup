//file: app/frontend/src/hooks/useAuth.js

import { useState, useEffect, useCallback } from 'react';
import AuthService from '../services/AuthService.js';

export const useAuth = () => {

    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [isInitializing, setInitializing] = useState(true);

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

            if (result.success) {
                setUser(data.user); //Maybe data.username ??                
            } else {
                setUser(null);
            }
        } catch (error) {
            setError(error.message || "Error checking session");
            setUser(null);
        } finally {
            setLoading(false);
            setInitializing(false);
        }
    }, []);

    /**
     * Login function
     */
    const login = useCallback(async (userIdentifier, password) => {
        try {
            setLoading(true);
            setError(null);

            const result = await AuthService.login(userIdentifier, password);

            if (result.success) {
                setUser(result.data.username);
                return { success: true, user: result.data.username };
            } else {
                setError(result.error);
                return { success: false, error: result.error };
            }

        } catch (error) {
            const errorMsg = error.message || "Login failed";
            setError(errorMsg);
            return { success: false, error: errorMsg };

        } finally {
            setLoading(false);
        }
    }, []);

    const register = useCallback(async (userData) => {
        try {
            setLoading(true);
            setError(null);

            const result = await AuthService.register(userData);

            if (result.success) {
                // Adapt to auto-login after registration if needed
                setUser(result.data.username);
                return { success: true, user: result.data.username };
            } else {
                setError(result.error);
                return { success: false, error: result.error };
            }

        } catch (error) {
            const errorMsg = error.message || "Registration failed";
            setError(errorMsg);
            return { success: false, error: errorMsg };
            
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
        isInitializing,
        isAuthenticated: !!user,
        login,
        logout,
        register,
        checkSession,
        
        clearError
    }
};