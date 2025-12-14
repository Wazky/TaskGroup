//file: app/frontend/src/components/ui/auth/LoginForm.jsx

import { useTranslation } from 'react-i18next'

export default function LoginForm({ handleLogin }) {
    const { t } = useTranslation();
    
    return (
        <form className="auth-form" onSubmit={handleLogin}>
            {/* User Identifier Field */}
            <div className="form-group">
                <label htmlFor="auth-identifier">
                    {t('auth.login.form.usernameLabel')}
                </label>
                <input type="text" id="auth-identifier" name="auth-identifier"
                placeholder={t('auth.login.form.usernamePlaceholder')} required></input>
            </div>

            {/* Password Field */}
            <div className="form-group">
                <label htmlFor="auth-password">
                    {t('auth.login.form.passwordLabel')}
                </label>
                <input type="password" id="auth-password" name="auth-password"
                placeholder={t('auth.login.form.passwordPlaceholder')} required></input>
            </div>

            {/* Submit Button */}
            <div className="form-actions">
                <button type="submit" className="btn btn-primary">
                    {t('auth.login.form.submit')}
                </button>
            </div>
        </form>
    );
}