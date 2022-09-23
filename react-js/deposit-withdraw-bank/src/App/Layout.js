import React from "react";

import Header from "./../Component/Header";
import Footer from "./../Component/Footer";

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
