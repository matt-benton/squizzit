import SendPasswordReset from './pages/auth/SendPasswordReset.vue'
import Welcome from './pages/Welcome.vue'
import SignUp from './pages/auth/SignUp.vue'
import Login from './pages/auth/Login.vue'
import Home from './pages/Home.vue'
import { store } from './store'

const checkAuth = function (to, from, next) {
    if (localStorage.getItem('token')) {
        next();
    } else {
        next('/');
    }
}

export const routes = [
    { path: '', component: Welcome },
    { path: '/signup', component: SignUp },
    { path: '/login', component: Login },
    { path: '/send_password_reset', component: SendPasswordReset },
    { path: '/home', component: Home, beforeEnter: checkAuth },
];
