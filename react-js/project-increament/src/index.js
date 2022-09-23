import React from 'react';
import { BrowserRouter } from "react-router-dom";
import ReactDOM from "react-dom/client";

import store from "./Providers/store";
import { Provider } from "react-redux";

import Layout from './App/Layout';
import App from './App';

store.subscribe( () => console.log(  store.getState() ) );

const root = ReactDOM.createRoot(document.getElementById('root'));

root.render(
    <BrowserRouter>
        <Provider store={ store }>
            <Layout>
                <App/>
            </Layout>
        </Provider>
    </BrowserRouter>
);
