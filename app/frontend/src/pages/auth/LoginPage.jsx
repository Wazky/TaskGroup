//file: app/frontend/src/pages/auth/LoginPage.jsx

import React from 'react';
import AuthLayout from '../../components/layout/AuthLayout'; // Import the AuthLayout component
import LoginForm from '../../components/ui/auth/LoginForm'; // Import the LoginForm component

export default function LoginPage() {
    
    function handleLogin(event) {
        event.preventDefault();
        // Handle login logic here
    }

    return (
        <AuthLayout authType="login">
            <LoginForm />
        </AuthLayout>
    );
}

