//file: app/frontend/src/components/ui/auth/RegisterForm.jsx

import { useTranslation } from 'react-i18next';
import { useRegister } from '../../../hooks/useRegister.js';
import { useState } from 'react';

export default function RegisterForm({ onRegisterSuccess }) {
    const { t } = useTranslation();

    const { 
        formData, 
        formErrors,
        registrationStatus,
        loading, 
        error, 
        handleChange, 
        handleSubmit, 
        handleReset,
        clearError
    } = useRegister(onRegisterSuccess);

    const submitRegister = async (event) => {
        const result = await handleSubmit(event);
        
        return result;
    }


    if (registrationStatus === 'success') {
        return (
            <div className="auth-success-state text-center">
                <div className="success-icon mb-4">
                    <i className="bi bi-check-circle-fill text-success" style={{ fontSize: '4rem' }}></i>
                </div>
                
                <h3 className="text-success mb-3">
                    {t('auth.register.success.title')}
                </h3>
                
                <p className="mb-3">
                    {t('auth.register.success.accountCreated')}
                </p>
                
                <div className="mt-4">
                    <div className="spinner-border text-primary" role="status">
                        <span className="visually-hidden">Loading...</span>
                    </div>
                    <p className="mt-2 text-muted">
                        {t('auth.register.success.preparingLogin')}
                    </p>
                </div>
            </div>
        );
    }

    if (registrationStatus === 'logging-in') {
        return (
            <div className="auth-logging-in text-center">
                <div className="spinner-container mb-4">
                    <div className="spinner-border text-primary" style={{ width: '3rem', height: '3rem' }} role="status">
                        <span className="visually-hidden">Loading...</span>
                    </div>
                </div>
                
                <h3 className="text-primary mb-3">
                    {t('auth.register.success.loggingIn')}
                </h3>
                
                <p className="text-muted">
                    {t('auth.register.success.redirecting')}
                </p>
            </div>
        );
    }

    return (
        <form className="auth-form" onSubmit={submitRegister}>
            
            {/* Display general error message */}
            {error && (
                <div className='mx-auto rounded w-auto border border-danger border-2 py-2 px-3' onClick={clearError}>
                    <p className='text-center text-danger fw-bold fs-6 mb-1'>
                        {t(error)}
                        <i className='ms-3 bi bi-x-circle-fill'></i>
                    </p>
                </div>  
            )}

            {/* Username Field */}
            <div className="form-group">
                <label htmlFor="username">
                    {t('auth.register.form.usernameLabel')}
                </label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value={formData.username}
                    onChange={handleChange}
                    placeholder={t('auth.register.form.usernamePlaceholder')} 
                    className={formErrors.username ? 'border-danger' : ''}
                    disabled={loading}
                />
                { formErrors.username &&
                    <span className='text-danger error-message'>
                        {t(formErrors.username)}
                    </span>}
            </div>

            {/* Email Field */}
            <div className="form-group">
                <label htmlFor="email">
                    {t('auth.register.form.emailLabel')}
                </label> 
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value={formData.email}
                    onChange={handleChange}
                    placeholder={t('auth.register.form.emailPlaceholder')} 
                    className={formErrors.email ? 'border-danger' : ''}
                    disabled={loading}
                />
                { formErrors.email &&
                    <span className='text-danger error-message'>
                        {t(formErrors.email)}
                    </span>
                }
            </div>

            {/* Password Field */}
            <div className="form-group">
                <label htmlFor="password">
                    {t('auth.register.form.passwordLabel')}
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    value={formData.password}
                    onChange={handleChange}
                    placeholder={t('auth.register.form.passwordPlaceholder')} 
                    className={formErrors.password ? 'border-danger' : ''}
                    disabled={loading}
                />
                { formErrors.password &&
                    <span className='text-danger error-message'>
                        {t(formErrors.password)}
                    </span>
                }
            </div>

            {/* Confirm Password Field */}
            <div className="form-group">
                <label htmlFor="confirmPassword">
                    {t('auth.register.form.confirmPasswordLabel')}
                </label>
                <input 
                    type="password" 
                    id="confirmPassword" 
                    name="confirmPassword" 
                    value={formData.confirmPassword}
                    onChange={handleChange}
                    placeholder={t('auth.register.form.confirmPasswordPlaceholder')} 
                    className={formErrors.confirmPassword ? 'border-danger' : ''}
                    disabled={loading}
                />
                { formErrors.confirmPassword &&
                    <span className='text-danger error-message'>
                        {t(formErrors.confirmPassword)}
                    </span>
                }
            </div>

            {/* Submit Button */}
            <div className="d-flex form-actions align-center gap-2">
                <button 
                    type="submit" 
                    className="btn btn-primary"
                >
                    {t('auth.register.form.submit')}
                </button>
                <button 
                    onClick={handleReset}
                    type="button"
                    className=" btn bg-tg-secondary text-light w-25"
                    disabled={loading || (formErrors.confirmPassword)}
                >
                    <i className="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </form>
    );

}