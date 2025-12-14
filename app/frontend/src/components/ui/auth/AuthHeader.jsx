//file: app/frontend/src/components/ui/auth/AuthHeader.jsx

import { useTranslation } from 'react-i18next';

export default function AuthHeader({ type }) {
    const { t } = useTranslation();
    const content = {
        login: {
            title: t('auth.login.header.title'),
            subtitle: t('auth.login.header.subtitle')
        },
        register: {
            title: t('auth.register.header.title'),
            subtitle: t('auth.register.header.subtitle')
        }
    };

    return (
        <div>
            <h1 className="auth-title">{content[type].title}</h1>
            <p className="auth-subtitle">{content[type].subtitle}</p>
        </div>
    );
}