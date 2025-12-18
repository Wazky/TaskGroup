//file: app/frontend/src/pages/auth/RegisterPage.jsx


import { useNavigate } from 'react-router-dom';

import AuthLayout from '../../components/layout/AuthLayout'; // Import the AuthLayout component
import RegisterForm from '../../components/ui/auth/RegisterForm'; // Import the RegisterForm component
import { ROUTES } from '../../constants/routes';

export default function RegisterPage() {
    const navigate = useNavigate();

    const handleRegisterSuccess = () => {
        navigate(ROUTES.DASHBOARD);
    }

    return (
        <AuthLayout authType="register">
            <RegisterForm onRegisterSuccess={handleRegisterSuccess} />
        </AuthLayout>
    );

}