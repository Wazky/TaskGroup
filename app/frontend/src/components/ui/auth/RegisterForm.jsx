//file: app/frontend/src/components/ui/auth/RegisterForm.jsx

import { useTranslation } from 'react-i18next';

export default function RegisterForm({ handleRegister }) {
    const { t } = useTranslation();

    return (
        <form className="auth-form" onSubmit={handleRegister}>
            {/* Username Field */}
            <div className="form-group">
                <label htmlFor="username">
                    {t('auth.register.form.usernameLabel')}
                </label>
                <input type="text" id="username" name="username" placeholder={t('auth.register.form.usernamePlaceholder')} 
                className="border border-secondary"/>
                <p className="text-danger error-message" style={{ display: "none" }}>
                    {/* Username error message will be displayed here */}
                </p>
            </div>

            {/* Email Field */}
            <div className="form-group">
                <label htmlFor="email">
                    {t('auth.register.form.')}
                </label> 
                <input type="email" id="email" name="email" placeholder={t('auth.register.form.emailPlaceholder')} 
                className="border border-secondary"/>
                <p className="text-danger error-message" style={{ display: "none" }}>
                    {/* Email error message will be displayed here */}
                </p>
            </div>

            {/* Password Field */}
            <div className="form-group">
                <label htmlFor="password">
                    {t('auth.register.form.passwordLabel')}
                </label>
                <input type="password" id="password" name="password" placeholder={t('auth.register.form.passwordPlaceholder')} 
                className="border border-secondary"/>
                <p className="text-danger error-message" style={{ display: "none" }}>
                    {/* Password error message will be displayed here */}
                </p>
            </div>

            <div className="form-group">
                <label htmlFor="confirm_password">
                    {t('auth.register.form.confirmPasswordLabel')}
                </label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder={t('auth.register.form.confirmPasswordPlaceholder')} 
                className="border border-secondary"/>
                <p className="text-danger error-message" style={{ display: "none" }}>
                    {t('auth.register.form.notMatchError')}
                </p>
            </div>

            <div className="form-actions">
                <button type="submit" className="btn btn-primary">
                    {t('auth.register.form.submit')}
                </button>
            </div>
        </form>
    );

}