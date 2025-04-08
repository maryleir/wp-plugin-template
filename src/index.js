import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import App from './App';
import 'react-datepicker/dist/react-datepicker.css';
import './styles.css';


const rootElement = document.getElementById('root');
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <BrowserRouter basename="/">
                <App />
            </BrowserRouter>
        </React.StrictMode>
    );
} else {
    console.error('Root element not found');
}