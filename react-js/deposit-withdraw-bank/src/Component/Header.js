import {Link} from "react-router-dom";
import { useSelector } from "react-redux";

const Header = () =>
{
    const total_balance = useSelector( state => state.amount );

    return (
        <nav className="navbar navbar-expand-lg navbar-light bg-light">
            <a className="navbar-brand" href="/">Koderlabs Bank</a>
            <div className="collapse float-right  navbar-collapse" id="navbarSupportedContent">
                <ul className="navbar-nav">
                    <li className="nav-item active"><Link className="nav-link" to="/">Home</Link></li>
                    <li className="nav-item"><Link className="nav-link" to="/about">About</Link></li>
                    <li className="nav-item"><Link className="nav-link" to="/contact">Contact</Link></li>
                    <li className="nav-item"><Link className="nav-link" to="/shop">Shop</Link></li>
                </ul>
            </div>
            <div className="bg-light float-right">
                <div className="btn btn-success">Balance: Rs. { total_balance }</div>
            </div>
        </nav>
    );
}

export default Header;
