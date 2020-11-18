import axios from 'axios';



// get All Invoices from BDD with axios
function findAllInvoice(){   
         return axios
                    .get("http://127.0.0.1:8000/api/invoices")
                    .then((response) => response.data["hydra:member"]);
}

// get all customers invoices link to the  id set in parameter
function getAllCustomerInvoices(idCustomer){
          return axios
                              .get("http://127.0.0.1:8000/api/customers/" + idCustomer)
                              .then((response) => console.log(response.data))
}

// delete de customer link to the Id set in parameter
function deleteInvoice(idInvoice){
          return axios
                              .delete("http://127.0.0.1:8000/api/invoices/" + idInvoice)
                              .then((response) => console.log("Suppression de l\'invoice " + idInvoice + " éfectué"))
}



export default {
          findAllInvoice,
          getAllCustomerInvoices,
          deleteInvoice
}