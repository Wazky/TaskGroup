//file: app/frontend/src/pages/auth/RegisterPage.jsx


import AuthLayout from '../../components/layout/AuthLayout'; // Import the AuthLayout component
import RegisterForm from '../../components/ui/auth/RegisterForm'; // Import the RegisterForm component

export default function RegisterPage() {

    function handleRegister(event) {
        event.preventDefault();
        // Handle registration logic here
    }

    return (
        <AuthLayout authType="register">
            <RegisterForm />
        </AuthLayout>
    );

}