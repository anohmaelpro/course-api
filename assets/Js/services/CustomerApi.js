import axios from 'axios' ;

// get All Customers from BDD with axios
function findAllCustomer(){
          
         return  axios
                              .get("http://127.0.0.1:8000/api/customers")
                              .then((response) => response.data["hydra:member"])
}


// get all customers invoices link to the  id set in parameter
function getAllCustomerInvoices(idCustomer){
          return axios
                              .get("http://127.0.0.1:8000/api/customers/" + idCustomer)
                              .then((response) => console.log(response.data))
}

// delete de customer link to the Id set in parameter
function deleteCustomer(idCustomer){
          return axios
                              .delete("http://127.0.0.1:8000/api/customers/" + idCustomer)
                              .then((response) => console.log("Suppression du customers '" + idCustomer + "' éfectué"))
}



export default {
          findAllCustomer,
          getAllCustomerInvoices,
          deleteCustomer
}