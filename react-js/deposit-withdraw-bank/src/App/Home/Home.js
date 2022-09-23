import React from 'react';

export default class Home extends React.Component {
    render() {
        return (
            <>
                <div className="carousel slide" data-ride="carousel">

                    <div className="carousel-inner" role="listbox">
                        <div className="item active">
                            <img src="https://www.w3schools.com/bootstrap/ny.jpg" alt="New York" width="100%" style={{ height:"700px"}} />
                            <div className="carousel-caption">
                                <h3>New York</h3>
                                <p>The atmosphere in New York is lorem ipsum.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </>
        );
    }
}
