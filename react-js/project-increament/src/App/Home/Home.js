import React from 'react';

export default class Home extends React.Component {
    render() {
        return (
            <>
                <div className="carousel slide" data-ride="carousel">

                    <ol className="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" className="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1" className=""></li>
                        <li data-target="#myCarousel" data-slide-to="2" className=""></li>
                    </ol>

                    <div className="carousel-inner" role="listbox">
                        <div className="item active">
                            <img src="https://www.w3schools.com/bootstrap/ny.jpg" alt="New York" width="100%" style={{ height:"700px"}} />
                            <div className="carousel-caption">
                                <h3>New York</h3>
                                <p>The atmosphere in New York is lorem ipsum.</p>
                            </div>
                        </div>

                        <div className="item">
                            <img src="https://www.w3schools.com/bootstrap/chicago.jpg" alt="Chicago" width="100%" style={{ height:"700px"}} />
                            <div className="carousel-caption">
                                <h3>Chicago</h3>
                                <p>Thank you, Chicago - A night we won't forget.</p>
                            </div>
                        </div>

                        <div className="item">
                            <img src="https://www.w3schools.com/bootstrap/la.jpg" alt="Los Angeles" width="100%" style={{ height:"700px"}} />
                            <div className="carousel-caption">
                                <h3>LA</h3>
                                <p>Even though the traffic was a mess, we had the best time playing at Venice Beach!</p>
                            </div>
                        </div>
                    </div>

                    <a className="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                        <span className="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span className="sr-only">Previous</span>
                    </a>
                    <a className="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                        <span className="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span className="sr-only">Next</span>
                    </a>
                </div>
            </>
        );
    }
}
