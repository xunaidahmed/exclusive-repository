import React from 'react';
import ReactDOM from "react-dom/client";
import { Provider } from "react-redux";
import { BrowserRouter } from "react-router-dom";

import Layout from './App/Layout';
import App from './App';
import store from './Providers/store';

const root = ReactDOM.createRoot(document.getElementById('root'));

root.render(
    <BrowserRouter>
        <Provider store={store}>
            <Layout>
                <App/>
            </Layout>
        </Provider>
    </BrowserRouter>
);
