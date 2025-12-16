//file: app/frontend/src/components/ui/auth/LoginForm.jsx

import { useTranslation } from 'react-i18next'  // Import the translation hook
import { useLogin } from '../../../hooks/useLogin.js';  // Import the custom useLogin hook

export default function LoginForm({ onLoginSuccess}) {
    const { t } = useTranslation(); // Translation function
    
    // Destructure values and functions from useLogin hook
    const { 
        formData, 
        formErrors, 
        loading, 
        error, 
        handleChange, 
        handleSubmit, 
        resetForm 
    } = useLogin();

    const submitLogin = async (event) => {
        const result = await handleSubmit(event);

        if (result.success && onLoginSuccess) {
            onLoginSuccess(); // Call the success callback if provided
        }

        return result;
    }
    
    return (
        <form className="auth-form" onSubmit={submitLogin}>

            {/* Display general error message */}
            {error && (
                <div className='alert alert-error'>
                    {error} {/* Change to closeable error message as in ViewLogin from MVC version*/}
                </div>
            )}

            {/* User Identifier Field */}
            <div className="form-group">
                <label htmlFor="userIdentifier">
                    {t('auth.login.form.usernameLabel')}
                </label>
                <input 
                    type="text" 
                    id="userIdentifier" 
                    name="userIdentifier"
                    value={formData.userIdentifier}
                    onChange={handleChange}
                    placeholder={t('auth.login.form.usernamePlaceholder')}
                    className={formErrors.userIdentifier ? 'input-error' : ''}
                    disabled={loading}
                    required
                />
                {formErrors.userIdentifier && (
                    <span className="error-message">{formErrors.userIdentifier}</span>
                )}
            </div>

            {/* Password Field */}
            <div className="form-group">
                <label htmlFor="auth-password">
                    {t('auth.login.form.passwordLabel')}
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password"
                    value={formData.password}
                    onChange={handleChange}
                    placeholder={t('auth.login.form.passwordPlaceholder')} 
                    className={formErrors.password ? 'input-error' : ''}
                    disabled={loading}
                    required
                />
                {formErrors.password && (
                    <span className="error-message">{formErrors.password}</span>
                )}
            </div>

            {/* Submit Button */}
            <div className="form-actions">
                <button 
                    type="submit" 
                    className="btn btn-primary"
                    disabled={loading}
                >
                    {loading ? t('auth.login.form.submitting') : t('auth.login.form.submit')}
                </button>
            </div>
        </form>
    );
}