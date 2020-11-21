// to import react just write "imr"
import React from 'react';
import ReactDOM from 'react-dom';
import { HashRouter, Switch, Route } from 'react-router-dom';


// any CSS you import will output into a single css file (app.css in this case)
import '../styles/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';


import Navbar from './components/Navbar';
import HomePage from './pages/HomePage';
import CustomerPage from './pages/CustomerPage';
import InvoicePage from './pages/InvoicePage';
import LoginPage from './pages/loginPage';

console.log('Hello Webpack Encore!');

const App = () => {
     return <HashRouter> 
                         <Navbar />
                         <main className="container pt-5">
                              <Switch>
                                   <Route path="/login" component={LoginPage} />
                                   <Route path="/invoices" component={InvoicePage} />
                                   <Route path="/customers" component={CustomerPage} />
                                   <Route path="/" component={HomePage} />
                              </Switch>
                         </main>
                    </HashRouter> ;
}

const rootElement = document.querySelector("#app");
ReactDOM.render(<App />, rootElement);