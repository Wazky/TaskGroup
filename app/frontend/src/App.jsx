// file : app/frontend/src/App.jsx

/**
 * Main App component of the React application.
 * This component serves as the root component for the application.
 * It can be extended to include global state management, theming,
 * or layout components that should be present across all pages.
 */

// Import necessary libraries
import { Routes, Route } from 'react-router-dom'; // Import Routes and Route for routing
import { ROUTES } from './constants/routes';  // Import route constants
import LoginPage from './pages/auth/LoginPage'; // Import the LoginPage component
import RegisterPage from './pages/auth/RegisterPage'; // Import the RegisterPage component


function App() {
  return (
      <Routes>
        {/* Define application routes here */}
        <Route path={ROUTES.HOME} element={<h1>Home</h1>} />
        <Route path={ROUTES.LOGIN} element={<LoginPage />} />
        <Route path={ROUTES.REGISTER} element={<RegisterPage />} />
        
        <Route path={ROUTES.DASHBOARD} element={<h1>Dashboard</h1>} />
        
      </Routes>
  );
}

export default App;