//file: app/frontend/src/hooks/useRegister.js

import { useState, useCallback } from 'react';

import { useAuth } from './useAuth.js';
import { createFormHandler, resetForm } from '../utils/forms.js';

export const useRegister = (onSuccessCallback) => {
    // Initial form state
    const initialFormState = { username: '', email: '', password: '', confirmPassword: '' };    

    // Get register function and state from useAuth
    const { register, login, loading, error, clearError } = useAuth(); 
    // Form state
    const [formData, setFormData] = useState(initialFormState);
    // Form validation errors
    const [formErrors, setFormErrors] = useState({});  
    // Registration status
    const [registrationStatus, setRegistrationStatus] = useState('idle'); // 'idle' | 'logging-in' | 'success' | 'error'

    // Handle form input changes
    const handleChange = useCallback((event) => {
        const { name, value } = event.target;   // Destructure name and value from event target

        // Update form data state
        setFormData(prevData => ({
            ...prevData,
            [name]: value
        }));

        // Clear specific field error on change
        if (formErrors[name]) {
            setFormErrors(prevErrors => ({
                ...prevErrors,
                [name]: ''
            }));
        }

        if (clearError) clearError(); // Clear general auth error if present

        // Additional validation for password match on change
        if (name === 'password' || name === 'confirmPassword') {
            const password = (name === 'password') ? value : formData.password;
            const confirmPassword = (name === 'confirmPassword') ? value : formData.confirmPassword;

            // If passwords do not match, set error
            if (password && confirmPassword && password !== confirmPassword) {
                setFormErrors(prevErrors => ({
                    ...prevErrors,
                    confirmPassword: 'auth.register.form.formErrors.passwordsDoNotMatch' // Translation key for passwords do not match error
                }));
            }
            else if (password === confirmPassword) {
                // Clear confirmPassword error if passwords match
                setFormErrors(prevErrors => ({
                    ...prevErrors,
                    confirmPassword: ''
                }));
                
            }
        }

    }, [formData, formErrors, clearError]);

    const validateForm = useCallback(() => {
        const errors = {};

        // Username validation
        if (!formData.username.trim()) { 
            errors.username = 'auth.register.form.formErrors.usernameRequired'; // Translation key for username required error
        }

        if (formData.username && formData.username.length < 3) {
            errors.username = 'auth.register.form.formErrors.usernameMinLength'; // Translation key for username min length error
        }

        // Email validation
        if (!formData.email.trim()) {
            errors.email = 'auth.register.form.formErrors.emailRequired'; // Translation key for email required error
        } else {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(formData.email)) {
                errors.email = 'auth.register.form.formErrors.emailInvalid'; // Translation key for invalid email error
            }
        }

        // Password validation
        if (!formData.password) {
            errors.password = 'auth.register.form.formErrors.passwordRequired'; // Translation key for password required error
        } else if (formData.password.length < 6) {
            errors.password = 'auth.register.form.formErrors.passwordMinLength'; // Translation key for password min length error
        }

        // Confirm Password validation
        if (formData.password !== formData.confirmPassword) {
            errors.confirmPassword = 'auth.register.form.formErrors.passwordsDoNotMatch'; // Translation key for passwords do not match error
        }

        setFormErrors(errors);

        return Object.keys(errors).length === 0; // Return true if no errors
    }, [formData]);

    /**
     * Form submit handler
     */
    const handleSubmit = useCallback(async (event) => {
        event.preventDefault(); // Prevent default form submission

        // Validate from before submitting
        if (!validateForm()) {
            return { success: false};   // Return early if validation fails
        }

        // Prepare user data for registration
        const userData = {username: formData.username, email: formData.email, password: formData.password};
        
        // Call register function from useAuth
        const result =await register(userData);

        // If registration failed with field errors, set them in formErrors state
        if (!result.success && result.errors) {
            setFormErrors(prevErrors => ({
                ...prevErrors,
                ...result.errors
            }));
        }

        // If registration successful, perform auto-login
        if (result.success) {
            setRegistrationStatus('success');

            // Wait a moment before auto-login 
            setTimeout(async () => {
                // Perform auto-login after registration
                return await performAutoLogin(formData.username, formData.password);
            }, 2000);
        }

        return result;

    }, [formData, validateForm, register]);

    const performAutoLogin = async (username, password) => {
        setRegistrationStatus('logging-in');

        setTimeout(async () => {
            const loginResult = await login(username, password);

            if (loginResult.success && onSuccessCallback) {
                // Llamar al callback solo cuando el login sea exitoso
                onSuccessCallback();
            }
        }, 2000);

    };

    const handleReset = useCallback(resetForm(
        initialFormState,
        setFormData,
        setFormErrors,
        clearError
    ), [clearError]);

    return {
        // State
        formData,
        formErrors,
        registrationStatus,
        loading,
        error,

        // Functions
        handleChange,
        handleSubmit,
        handleReset,
        clearError
    }

};