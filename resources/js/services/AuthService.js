export default class AuthService {
    storeUser (email, token) {
        localStorage.setItem('email', email);
        localStorage.setItem('token', token);
    }
}
