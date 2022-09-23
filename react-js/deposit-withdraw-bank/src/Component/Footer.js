import React from "react";

export default class Footer extends React.Component
{
    /*constructor() {
        super();
        console.log('props', this.props)
    }*/

    render() {
        return (
           <>
               <div className="container border-top" style={{ width: "100%", background: "#eee", padding: "12px 0px" }}>
                   <div className="copyright text-center">
                       <div className="copyright">
                           © 2021 Koderlabs. All rights reserved
                       </div>
                   </div>
               </div>
           </>
        );
    }
}
