//file: app/frontend/src/components/ui/LanguageDropdown.jsx

import { useTranslation } from 'react-i18next'
import esFlag from '../../../../../public/images/flag-es.png';
import ukFlag from '../../../../../public/images/flag-en.png';

export default function LanguageDropdown() {
    const { i18n } = useTranslation();

    const handleChangeLanguage = (lang) => {
        i18n.changeLanguage(lang);
        localStorage.setItem('preferredLanguage', lang);     
    };

    return (
        <div className="dropdown me-3">
            <button className="btn btn-link text-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i className="bi bi-translate"></i>
            </button>
            <ul className="dropdown-menu dropdown-menu-end bg-dark">
                <li>
                    <button className="dropdown-item text-light" onClick={() => handleChangeLanguage('es')}>
                        <img src={esFlag} alt="Spanish" className="me-2" style={{ width: '20px', height: '15px' }}></img>
                        Spanish
                    </button>
                </li>
                <li>
                    <button className="dropdown-item text-light" onClick={() => handleChangeLanguage('en')}>
                        <img src={ukFlag} alt="English" className="me-2" style={{ width: '20px', height: '15px' }}></img>
                        English
                    </button>
                </li>
            </ul>
        </div>
    );
}