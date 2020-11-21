import React, { useEffect, useState } from 'react';
import axios from 'axios' ;
import CustomerApi from '../services/CustomerApi';
import LoginApi from '../services/LoginApi';
import { awrap } from 'regenerator-runtime';

const LoginPage = (props) => {

          const [credentials, setCredentials] = useState({
                    username:"",
                    password:""
          })

          const [error, setError] = useState("")

          // gestion des champs (récuperation des champs username et password)
          const handleChange = ({currentTarget}) => {
                    const value = currentTarget.value ;
                    const name = currentTarget.name ;
                    setCredentials({
                              ...credentials,
                              [name]:value
                    }) ;
          }

          // soumission du formulaire, tentative de connexion de l'utilisation, récupération token User et mise en  mémoire windows
          const handleSubmit = async event => {

                    event.preventDefault() ;
                    
                    try {
                              await LoginApi.userLogin(credentials);
                              setError("");
                    } catch (error) {
                              console.log(error);
                              setError("Aucun compte ne possède cette adresse ou alors les indentifiants sont incorrecte. Merci de réessayer s\'il vous plait !");
                    }
                    console.log(credentials) ;

          }



          return ( 
                    <>
                              <h1 className="text-center">Log In</h1>
                              <form
                                        onSubmit={handleSubmit}
                              >
                                        <div className="form-group">
                                                  <label htmlFor="username">Email address</label>
                                                  <input
                                                            type="email"
                                                            className={"form-control" + (error && " is-invalid")}
                                                            id="username"
                                                            name="username"
                                                            aria-describedby="emailHelp"
                                                            placeholder="Enter email"
                                                            value={credentials.username}
                                                            onChange={handleChange}
                                                  />
                                                  <small id="emailHelp" className="form-text text-muted">We'll never share your email with anyone else.</small>

                                                  { error && <p className="invalid-feedback"> {error} </p>}
                                        </div>

                                        <div className="form-group">
                                                  <label htmlFor="password">Password</label>
                                                  <input 
                                                            type="password"
                                                            className="form-control"
                                                            id="password"
                                                            name="password"
                                                            placeholder="Password"
                                                            value={credentials.password}
                                                            onChange={handleChange}
                                                  />
                                        </div>

                                        <div className="form-group">
                                                  <button type="submit" className="btn btn-success">Connection</button>
                                        </div>
                              </form>
                    </>
          );
}
 
export default LoginPage;