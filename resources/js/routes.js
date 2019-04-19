import { store } from './store'
import Home from './pages/Home.vue'
import Login from './pages/auth/Login.vue'
import ResetPassword from './pages/auth/ResetPassword.vue'
import SendPasswordReset from './pages/auth/SendPasswordReset.vue'
import SignUp from './pages/auth/SignUp.vue'
import Welcome from './pages/Welcome.vue'

const checkAuth = function (to, from, next) {
    if (localStorage.getItem('token')) {
        next();
    } else {
        next('/');
    }
}

export const routes = [
    { path: '', component: Welcome },
    { path: '/auth/signup', component: SignUp },
    { path: '/auth/login', component: Login },
    { path: '/auth/send_password_reset', component: SendPasswordReset },
    { path: '/auth/reset_password', component: ResetPassword },
    { path: '/home', component: Home, beforeEnter: checkAuth },
];
