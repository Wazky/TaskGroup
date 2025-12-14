// file: app/frontend/src/main.jsx

/**
 * Entry point of the React application.
 * This file sets up the React application with routing capabilities
 * and renders the main App component into the root DOM element.
 * 
 * It uses React Router for client-side routing and React Strict Mode
 * for highlighting potential problems in the application.
 */

// Import necessary libraries and components
import React from 'react'; 
import ReactDOM from 'react-dom/client';  
import { BrowserRouter } from 'react-router-dom';
//import { I18nextProvider } from 'react-i18next'; // Internationalization provider
import './../../../public/css/main.css'; // Import global styles
import App from './App'; // Main App component
import './utils/i18n.js'; // i18n configuration


// Render the application into the root element
ReactDOM.createRoot(document.getElementById('root')).render(
  // Wrap the application in React Strict Mode for highlighting potential issues
  <React.StrictMode>
    {/* Internationalization provider */}
    {/* <I18nextProvider i18n={i18n}> */}
      {/* Set up BrowserRouter for client-side routing */}
      <BrowserRouter>     
        {/* Render the main App component */}
        <App /> 
      </BrowserRouter>
    {/* </I18nextProvider> */}
  </React.StrictMode>
);


