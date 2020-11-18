import React, { useEffect, useState } from 'react';
import InvoiceApi from '../services/InvoiceApi';
import moment from 'moment';
import Pagination from '../components/Pagination';
import { HashRouter, Switch, Route } from 'react-router-dom';

// cette constant permet de changer  la couleur (css) des champs en fonction de la valeur reçu de la BDD
const STATUS_CLASSES = {
          PAID : "success",
          SENT: "primary",
          CANCELLED: "danger"
}

// cette constantes permet d'attribuer le bon label en  fonction de la valeur reçu de la BDD
const STATUS_LABEL = {
          PAID : "PAID",
          SENT: "SENT",
          CANCELLED: "CANCELLED"
}

const InvoicePage = (props) => {

          // instancie un tableau d'invoices vide
          const [invoices, setInvoices] = useState([]) ;
          const [currentPage, setCurrentPage] = useState(1) ;
          const [search, setSearch] = useState('');

          // get tous les invoices de la BDD
          const invoiceFromBDD = async () => {
                    try {
                              const invoicesGet = await InvoiceApi.findAllInvoice();
                              setInvoices(invoicesGet);
                              console.log();
                    } catch (error) {
                              console.log(error.message);
                    }
          }

          // au chargement de la page on exécute la requête  qui va récupérer toutes les informations lié à un customer donné
          useEffect(() => {
                    invoiceFromBDD() ;
          }, [])

          // nombre d'élément par page
          const itemPerPage = 13 ;

          // fonction qui permet de faire le changement de page
          const handleChangePage = (page) => setCurrentPage(page) ;


          // fonction qui permet d'afficher la saisie de l'utilisateur dans la barre de recherche
          const handleSearch = ({currentTarget}) => {
                    setSearch(currentTarget.value);
                    setCurrentPage(1);
          };

          // fonction qui filtre la liste des customers en fontion d'une valeur donnée
          const filteredInvoices = invoices.filter(
                    i => 
                              i.customer.firstName.toLowerCase().startsWith(search.toLowerCase()) || 
                              i.customer.lastName.toLowerCase().startsWith(search.toLowerCase()) ||
                              i.chrono.toString().startsWith(search.toLowerCase()) ||
                              i.amout.toString().startsWith(search.toLowerCase()) ||
                              STATUS_LABEL[i.status].toLowerCase().includes(search.toLowerCase())
          );


          // fonction qui supprime un customer de la BDD
          const handleDelete = async(id) => {

                    // on fait une copie de l'état actuel des customers
                    const originalInvoices = [...invoices] ;

                    // 1. on  affiche tous les customers sauf celui qui à été passé en paramettre
                    setInvoices(invoices.filter(invoice => invoice.id !== id))
                    
                    // 2. suppresion de l'id sélectionner si cela échoue on revoie l'ancien état d'affichage des customers
                    try {
                              await InvoiceApi.deleteInvoice(id) 
                    } catch (error) {
                              setInvoices(originalInvoices);
                              console.log("ErrorSatut : "+error.response.data["status"]) ;
                              console.log("ErrorDetail : "+error.response.data["detail"]) ;
                    }
          }     



          const paginatedInvoices = Pagination.getData(filteredInvoices, currentPage, itemPerPage);


          // formattage de la date
          const formatDate = (date) => moment(date).format("DD/MM/YYYY")







          

          return ( 
                    <>
                              <h1 className="text-center">Invoices Page</h1>

                              <div className="form-group">
                                        <input 
                                                  type="text"
                                                  className="form-control"
                                                  placeholder="Search"
                                                  onChange = {handleSearch}
                                                  value={search}
                                        />
                              </div>
                              <table className="table table-hover">
                                        <thead>
                                                  <tr>
                                                            <th className="text-center">Number Invoice</th>
                                                            <th className="text-center" >Customer</th>
                                                            <th className="text-center">Sent The</th>
                                                            <th className="text-center">State</th>
                                                            <th className="text-center">Price</th>
                                                            <th></th>
                                                  </tr>
                                        </thead>
                                        <tbody>
                                                  {paginatedInvoices.map(
                                                            invoice => 
                                                                      <tr className="baseTable" key={invoice.id}>
                                                                                <td className="text-center">{invoice.chrono}</td>
                                                                                <td className="text-center" >
                                                                                          <a href={"#/Invoices/customerDetails/"+invoice.customer.id}>{invoice.customer.firstName} {invoice.customer.lastName}</a>
                                                                                </td>
                                                                                <td className="text-center">{formatDate(invoice.sentAt)}</td>
                                                                                <td className="text-center">
                                                                                          <span className={"badge badge-"+STATUS_CLASSES[invoice.status]}>{STATUS_LABEL[invoice.status]}</span>
                                                                                </td>
                                                                                <td className="text-center">{invoice.amout.toLocaleString()} €</td>
                                                                                <td>
                                                                                          <a href={"#/invoices/updateInvoice/" + invoice.id} className="btn btn-sm btn-warning">Update</a>
                                                                                          <button
                                                                                                    className="btn btn-sm btn-danger  ml-1"
                                                                                                    id="deleteCustomer"
                                                                                                    onClick= {() => handleDelete(invoice.id)}
                                                                                                    >Delete
                                                                                          </button>
                                                                                </td>
                                                                      </tr>
                                                  )}
                                                  
                                        </tbody>
                              </table>

                              {itemPerPage < filteredInvoices.length && 
                              
                                        <Pagination
                                                  currentPage={currentPage} // c'est la page courant afin que le bon numéro de page soit mis en evidence
                                                  itemPerPage={itemPerPage}  // c'est le nombre d'éléments à afficher par page, ici en Occurence on aurra environ "itemPerPage" Customers par page
                                                  dataLength={filteredInvoices.length} // c'est la taille du nombre d'éléments qu'on récupère de la BDD lorsqu'on Get toutes les données à paginer
                                                  onPageChange={handleChangePage} // cette fonction de changer de state
                                        />
                    
                              }
                    </>
          );
}
 
export default InvoicePage;