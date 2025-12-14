//file: app/frontend/src/components/ui/auth/AuthFooter.jsx

import { Link } from 'react-router-dom';
import { ROUTES } from '../../../constants/routes.js';

import { useTranslation } from 'react-i18next';
import LanguageDropdown from '../LanguageDropdown.jsx';

export default function AuthFooter({ type }) {
    const { t } = useTranslation();

    const content = {
        login: {
            message: t('auth.login.footer.noAccount'),
            altAuth: t('auth.login.footer.registerHere'),
            altAuthLink: ROUTES.REGISTER
        },
        register: {
            message: t('auth.register.footer.haveAccount'),
            altAuth: t('auth.register.footer.loginHere'),
            altAuthLink: ROUTES.LOGIN
        },
    };
    
    return (
        <footer className="auth-footer d-flex justify-content-between align-items-center">
            <p>
                {content[type].message}{' '}
                <Link to={content[type].altAuthLink}>{content[type].altAuth}</Link>
            </p>
            <LanguageDropdown />
        </footer>
    );
}