import axios from 'axios'

export default class AuthService {
    storeUser (email, token) {
        localStorage.setItem('email', email);
        localStorage.setItem('token', token);

        this.setRequestHeaders(token);
    }

    setRequestHeaders (token) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    }

    clearRequestHeaders () {
        delete axios.defaults.headers.common["Authorization"];
    }
}
