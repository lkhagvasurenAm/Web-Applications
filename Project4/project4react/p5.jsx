import React from 'react';
import ReactDOM from 'react-dom';
import Example from './components/example/Example';
import States from './components/states/States';
import Header from './components/header/Header';
import {HashRouter, Route, Link} from "react-router-dom";

ReactDOM.render(
    <HashRouter>
        <div>
            <Header />
            <div className="switchbar">
                <Link to="/states" className="statesbar">
                    States
                </Link>
                <Link to="/example" className="examplebar">
                    Example
                </Link>
            </div>
            <Route path="/states" component={States}/>
            <Route path="/example" component={Example}/>
        </div>
    </HashRouter>,
    document.getElementById("reactapp"),
)