//file: app/frontend/src/hooks/useLogin.js

import { useState, useCallback } from 'react';

import { useAuth } from './useAuth.js';
import { createFormHandler } from '../utils/forms.js';

export const useLogin = () => {
    // Initial form state
    const initialFormState = { userIdentifier: '', password: '' };

    // Get login function and state from useAuth
    const { login: authLogin, loading, error, clearError } = useAuth(); 
    // Form state
    const [formData, setFormData] = useState(initialFormState);
    // Form validation errors
    const [formErrors, setFormErrors] = useState({});  

    // Handle form input changes
    const handleChange = useCallback(createFormHandler(
        setFormData,
        setFormErrors,
        formErrors,
        clearError 
    ), [formErrors, clearError]);

/*
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
*/

    // Login form validation function
    const validateForm = useCallback(() => {
        const errors = {};
        
        // Set form errors with translation keys for use in the component

        if (!formData.userIdentifier.trim()) {
            errors.userIdentifier = 'auth.login.form.formErrors.usernameRequired';  // Translation key for username required error
        }

        if (!formData.password) {
            errors.password = 'auth.login.form.formErrors.passwordRequired';    // Translation key for password required error
        }

        setFormErrors(errors);
        
        return Object.keys(errors).length === 0;

    }, [formData]);

    /**
     * Form submit handler
     */
    const handleSubmit = useCallback(async (event) => {
        event.preventDefault(); // Prevent default form submission

         // Validate form before submitting
        if (!validateForm()) {
            return { success: false, error: 'Validation Failed'};  // Return early if validation fails
        }

        return await authLogin(formData.userIdentifier, formData.password);

    }, [formData, validateForm, authLogin]);
    
    /*
    Reset form function (login form won't need it often)
    const resetForm = useCallback(() => {
        setFormData({ userIdentifier: '', password: ''});
        setFormErrors({});
        clearError();

    }, [clearError]);
    */

    return {
        // 
        formData,
        formErrors,

        loading,
        error,
        
        handleChange,
        handleSubmit,        
    }

};

