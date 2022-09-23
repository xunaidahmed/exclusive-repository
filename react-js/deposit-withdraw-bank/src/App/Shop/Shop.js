import React from 'react';
import { useDispatch } from "react-redux";
import { bindActionCreators } from "redux";
import * as actionCreators from "./../../Providers/action-creators/index";

const Shop  = () =>
{
    const dispatch = useDispatch();
    const { withdrawMoney, depositMoney } = bindActionCreators(actionCreators, dispatch );

    return (
        <>
            <div className="container text-center" style={{marginTop: "70px", marginBottom: "70px"}}>
                <h3>Deposit/Withdraw Money</h3>
                <div className="d-flex p-3 ml-5 bg-secondary text-white">
                    <div style={{ marginLeft: "200px"}}><button className="btn btn-success mr-2 float-left" onClick={ () => withdrawMoney(100) }>-</button></div>
                    <div className="p-2 w-50 bg-warning float-left">Update Balance</div>
                    <div><button className="btn btn-success  ml-2 float-left" onClick={ () => depositMoney(100) }>+</button></div>
                </div>
            </div>
        </>
    );
};

export default Shop;
