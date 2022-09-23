import React from "react";
import { useSelector, useDispatch } from "react-redux";
import { incNumber, decNumber } from "./../Providers/actions/index";

const IncDecContainer = () =>
{
    let myState = useSelector( (state) => state.changeTheNumber );
    let dispatch = useDispatch();

    return (
       <>
           <div className="input-group mb-3">
               <div className="input-group-prepend">
                   <span onClick={ () => dispatch(decNumber()) } className="input-group-text">Dec</span>
               </div>
               <input type="text" className="form-control" value={myState}  />
               <div className="input-group-prepend">
                   <span onClick={ () => dispatch(incNumber()) } className="input-group-text">Inc</span>
               </div>
           </div>
       </>
    );
}

export default  IncDecContainer;
