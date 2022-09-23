import React from "react";
import {Route, Routes} from "react-router";

import Home from "./App/Home/Home";
import About from "./App/About/About";
import Contact from "./App/Contact/Contact";

//Aditional Components
import LoaderSpinner from "./Component/LoaderSpinner";

export default class App extends React.Component
{
    // Constructor
   constructor()
   {
       super();

       this.state = {
           site_settings: {},
           is_spiner_loader: false
       };
   }

   // ComponentDidMount is used to
   // execute the code
   componentDidMount()
   {
       fetch(process.env.REACT_APP_APP_API_URL +  "/global/settings")
       .then((res) => res.json())
       .then((json) => {

           this.setState({
               site_settings: json.data,
               is_spiner_loader: true
           });
       });
   }

    render()
    {
        //const { api_data, setApiData} = useState(0);
        const { is_spiner_loader, site_settings } = this.state;

        if (!is_spiner_loader) return  <LoaderSpinner />;

        return (
            <>
                <Routes>
                    <Route path="/" element={<Home site_settings={site_settings}  />}></Route>
                    <Route path="/about" element={<About site_settings={site_settings}  />}></Route>
                    <Route path="/contact" element={<Contact site_settings={site_settings}  />}></Route>
                </Routes>
            </>
        );
    }
}
