//file: app/frontend/src/pages/auth/LoginPage.jsx

import React from 'react';
import { useNavigate } from 'react-router-dom';

import { ROUTES } from '../../constants/routes.js'; // Import the DASHBOARD route constant
import AuthLayout from '../../components/layout/AuthLayout'; // Import the AuthLayout component
import LoginForm from '../../components/ui/auth/LoginForm'; // Import the LoginForm component

export default function LoginPage() {
    const navigate = useNavigate();
    const handleLoginSuccess = () => {
        console.log("Login successful!");
        
        navigate(ROUTES.DASHBOARD); // Redirect to dashboard or another page after successful login
    }

    return (
        <AuthLayout authType="login">
            <LoginForm onLogingSuccess={handleLoginSuccess} />
        </AuthLayout>
    );
}

