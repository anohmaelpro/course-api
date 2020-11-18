import React, { useEffect, useState } from 'react';
import Pagination from '../components/Pagination';
import CustomerApi from '../services/CustomerApi';

const CustomerPage = (props) => {
          //  tableau qui va contenir tous les customers
          const [customers, setCustomers] = useState([]);

          // Permet de savoir sur quelle page on se trouve afin de pouvoir gérer la paination 
          const [currentPage, setCurrentPage] = useState(1);

          // Permet de faire la recherche d'un customer => le champ de la barre de recherche
          const [search, setSearch] = useState('');


          // on recupère tous les  customers de notre BDD au chargeement de la page et on met tout dans un tableau vide
          const fetchcustomers = async () => {
                    try {
                              const data = await CustomerApi.findAllCustomer();
                              setCustomers(data) ;
                    } catch (error) {
                              console.log(error.response)  
                    }
          }


          // exécution de la fonction précédante au chargement
          useEffect( () => {
                    fetchcustomers();
          }, []);

          // fonction qui supprime un customer de la BDD
          const handleDelete = async(id) => {

                    // on fait une copie de l'état actuel des customers
                    const originalCustomers = [...customers] ;

                    // 1. on  affiche tous les customers sauf celui qui à été passé en paramettre
                    setCustomers(customers.filter(customer => customer.id !== id))
                    
                    // 2. suppresion de l'id sélectionner si cela échoue on revoie l'ancien état d'affichage des customers
                    try {
                              await CustomerApi.deleteCustomer(id) 
                    } catch (error) {
                              setCustomers(originalCustomers);
                              console.log("ErrorSatut : "+error.response.data["status"]) ;
                              console.log("ErrorDetail : "+error.response.data["detail"]) ;
                    }
          }         


          // fonction qui change l'indice de la page où l'on se trouve sur notre site
          const handleChangePage = (page) =>  setCurrentPage(page);

          // fonction qui permet d'afficher la saisie de l'utilisateur dans la barre de recherche
          const handleSearch = ({currentTarget}) => {
                    setSearch(currentTarget.value);
                    setCurrentPage(1);
          };

          // nombre d'élément par  page
          const itemPerPage = 12 ;

          // fonction qui filtre la liste des customers en fontion d'une valeur donnée
          const filteredCustomers = customers.filter(
                    c => 
                              c.firstName.toLowerCase().startsWith(search.toLowerCase()) || 
                              c.lastName.toLowerCase().startsWith(search.toLowerCase()) ||
                              c.email.toLowerCase().includes(search.toLowerCase()) ||
                              c.company.toLowerCase().includes(search.toLowerCase())
          );

          // fonction from Pagination qui permet de calculer ou de définir un tableau de customers avec le nombre de customers à afficher
          const paginatedCustomers = Pagination.getData(filteredCustomers, currentPage, itemPerPage);

          

          //  on return l'affichage qui sera vu par le visiteur
          return (
                    <>
                              <h1>Customers Pages</h1>

                              <div className="form-group">
                                        <input 
                                                  type="text"
                                                  className="form-control"
                                                  placeholder="Search"
                                                  onChange={handleSearch}
                                                  value={search}
                                        />
                              </div>
                              {/* affichage des customers */}
                              <table className="table table-hover">
                                        <thead>
                                                  <tr>
                                                            <th>Customer ID</th>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Company</th>
                                                            <th className="text-center">Invoices</th>
                                                            <th className="text-center">Total Amount</th>
                                                            <th>
                                                            </th>
                                                  </tr>
                                        </thead>
                                        <tbody>
                                                  {paginatedCustomers.map(
                                                            customer => 
                                                                      <tr className="baseTable" key={customer.id}>
                                                                                <td> {customer.id} </td>
                                                                                <td>
                                                                                          <a href={"#/customers/invoicesCustomer/"+customer.id}>{customer.firstName} {customer.lastName}</a>
                                                                                </td>
                                                                                <td>{customer.email}</td>
                                                                                <td>{customer.company}</td>
                                                                                <td className="text-center">{customer.invoicesCustomer.length}</td>
                                                                                <td className="text-center"> {customer.totalAmount.toLocaleString()} €</td>
                                                                                <td>
                                                                                          <a href={"#/customers/updateCustomer/"+customer.id} className="btn btn-sm btn-warning">Update</a>
                                                                                          <button 
                                                                                                    disabled={customer.invoicesCustomer.length > 0}
                                                                                                    className="btn btn-sm btn-danger  ml-1" id="deleteCustomer"
                                                                                                    onClick={() => handleDelete(customer.id)}
                                                                                                    >Delete
                                                                                          </button>
                                                                                </td>
                                                                      </tr>

                                                  )}
                                        </tbody>
                              </table>
                              

                              {/* paginattion de la pages des customers */}
                              {itemPerPage < filteredCustomers.length && 
                              
                                        <Pagination
                                                  currentPage={currentPage} // c'est la page courant afin que le bon numéro de page soit mis en evidence
                                                  itemPerPage={itemPerPage}  // c'est le nombre d'éléments à afficher par page, ici en Occurence on aurra environ "itemPerPage" Customers par page
                                                  dataLength={filteredCustomers.length} // c'est la taille du nombre d'éléments qu'on récupère de la BDD lorsqu'on Get toutes les données à paginer
                                                  onPageChange={handleChangePage} // cette fonction de changer de state
                                        />
                              
                              }
                    </>
          );
}

//  on export notre composant
export default CustomerPage;