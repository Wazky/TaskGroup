//file: app/frontend/src/hooks/useLogin.js

import { useState, useCallback } from 'react';
import { useAuth } from './useAuth.js';

export const useLogin = () => {
    const { login: authLogin, loading, error, clearError } = useAuth(); // Get login function and state from useAuth
    const [formData, setFormData] = useState({ userIdentifier: '', password: ''}); // Form state
    const [formErrors, setFormErrors] = useState({}); // Form validation errors

    const handleChange = useCallback((event) => {
        const { name, value } = event.target;   // Destructure name and value from event target

        // Update form data state
        setFormData((prevData) => ({
            ...prevData,
            [name]: value
        }));
    
        // Clear specific field error on change
        if (formErrors[name]) {
            setFormErrors((prevErrors) => ({
                ...prevErrors,
                [name]: ''
            }));
        }

        if (error) clearError(); // Clear general auth error if present

    }, [formErrors, error, clearError]);

    const validateForm = useCallback(() => {
        const errors = {};

        if (!formData.userIdentifier.trim()) {
            errors.userIdentifier = 'User identifier is required';
        }

        if (!formData.password) {
            errors.password = 'Password is required';
        }

        if (formData.password && formData.password.length < 6) {
            errors.password = 'Password must be at least 6 characters';
        }

        setFormErrors(errors);
        
        return Object.keys(errors).length === 0;

    }, [formData]);

    const handleSubmit = useCallback(async (event) => {
        event.preventDefault();

        if (!validateForm()) {
            return { success: false, error: 'Validation errors' };
        }

        return await authLogin(formData.userIdentifier, formData.password);

    }, [formData, validateForm, authLogin]);

    const resetForm = useCallback(() => {
        setFormData({ userIdentifier: '', password: ''});
        setFormErrors({});
        clearError();

    }, [clearError]);

    return {
        // 
        formData,
        formErrors,

        loading,
        error,
        
        handleChange,
        handleSubmit,
        resetForm,
    }

};

