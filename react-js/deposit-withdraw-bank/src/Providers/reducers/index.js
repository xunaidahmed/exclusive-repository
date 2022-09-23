import { combineReducers } from "redux";
import amount_reducers from "./amount_reducers";

const reducers = combineReducers({
    amount: amount_reducers
});

export default reducers;
