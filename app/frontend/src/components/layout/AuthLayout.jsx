// file: app/frontend/src/components/layout/AuthLayout.jsx

import React from 'react';
import AuthLogo from '../ui/auth/AuthLogo'; // Import the AuthLogo component
import FlashMessage from '../ui/FlashMessage'; // Import the ErrorFadeMessage component
import AuthFooter from '../ui/auth/AuthFooter'; // Import the AuthFooter component
import AuthHeader from '../ui/auth/AuthHeader'; // Import the AuthHeader component

// Define component for authentication layout
const AuthLayout =  ({ children, authType } ) => { // Accept children components as props
    return (
        // HTML structure for the layout
        <div className='auth-page'>
            <div className='auth-container'>

                {/* Header Section */}
                <div className='auth-header'>
                    {/* Logo Component */}
                    <AuthLogo />
                    {/* Header Text Component */}
                    <AuthHeader type={authType} />
                </div>

                {/* Main Content Area */}
                <main>
                    {/* Maybe here place error div */}
                    {children} {/* Render children components here */}
                </main>

                {/* Footer Section */}
                <AuthFooter type={authType} />

            </div>        
        </div>    
    );
}

export default AuthLayout; // Export the component for use in other parts of the application