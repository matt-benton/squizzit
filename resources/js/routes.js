import { store } from './store'
import Home from './pages/Home.vue'
import Login from './pages/auth/Login.vue'
import ResetPassword from './pages/auth/ResetPassword.vue'
import SendPasswordReset from './pages/auth/SendPasswordReset.vue'
import SignUp from './pages/auth/SignUp.vue'
import Welcome from './pages/Welcome.vue'
import QuizCreate from './pages/quizzes/QuizCreate.vue'
import QuizEdit from './pages/quizzes/QuizEdit.vue'
import QuizList from './pages/quizzes/QuizList.vue'
import QuizShare from './pages/quizzes/QuizShare.vue'
import QuizTake from './pages/quizzes/QuizTake.vue'
import Invites from './pages/Invites.vue'

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
    { path: '/auth/reset_password/:token', component: ResetPassword },
    { path: '/home', component: Home, beforeEnter: checkAuth },
    { path: '/invites', component: Invites, beforeEnter: checkAuth },
    { path: '/quizzes/create', component: QuizCreate, beforeEnter: checkAuth },
    { path: '/quizzes/:id', component: QuizEdit, beforeEnter: checkAuth },
    { path: '/quizzes/:id/share', component: QuizShare, beforeEnter: checkAuth },
    { path: '/quizzes', component: QuizList, beforeEnter: checkAuth },
    { path: '/quizzes/:id/take', component: QuizTake, beforeEnter: checkAuth }
];
