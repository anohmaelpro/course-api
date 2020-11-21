import axios from "axios";


function userLogout(){
          // In the window local storage, we delete the token saved at the  user authentification
          window.localStorage.removeItem("authToken");

          // we delete the header in axios name Autorization
          delete axios.defaults.headers["Authorization"];
}

function userLogin(credentials){

          return axios
                              .post("http://127.0.0.1:8000/api/login_check", credentials)
                              .then((response) => response.data.token)
                              .then(tokenUser => {
                                        
                                        // je stock le token dans le local storage window
                                        window.localStorage.setItem("authToken", tokenUser) ;

                                        // on prévient  et on ajoute le token dans le header de Axios
                                        axios.defaults.headers["Authorization"] = "Bearer " + tokenUser;
                              })

}


export default {
          userLogin,
          userLogout
};