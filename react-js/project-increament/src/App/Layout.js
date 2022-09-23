import React from "react";

//import Enviroment from "./../Illuminate/Enviroment";

import Header from "./../Component/Header";
import Footer from "./../Component/Footer";

//import { dd } from "./../Helper/Utilities";

export default class Layout extends React.Component
{
    render() {

        return (
            <>
                <Header />
                <main>{this.props.children}</main>
                <Footer  />
            </>
        )
    }
}
