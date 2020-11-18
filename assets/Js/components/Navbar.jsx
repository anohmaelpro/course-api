import React from 'react';


const Navbar = (props) => {
     return (
          <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
               <a className="navbar-brand" href="#">API PLATEFORM</a>

               <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
               </button>

               <div className="collapse navbar-collapse" id="navbarColor02">
                    <ul className="navbar-nav mr-auto">
                         <li className="nav-item">
                              <a className="nav-link" href="#/customers">Customers</a>
                         </li>
                         <li className="nav-item">
                              <a className="nav-link" href="#/invoices">Invoices</a>
                         </li>
                    </ul>
                    <ul className="navbar-nav ml-auto">
                         <li className="nav-item">
                              <a href="#" className="nav-link">Inscription</a>
                         </li>
                         <li className="nav-item">
                              <a href="#" className="btn btn-light">Log In</a>
                         </li>
                         <li className="nav-item">
                              <a href="#" className="btn btn-light">Logout</a>
                         </li>
                    </ul>
               </div>
          </nav>
     );
}
 
export default Navbar;