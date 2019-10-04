<template>
    <div>
        <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
            <div class="container">
                <div class="navbar-menu">
                    <div class="navbar-start">
                        <router-link to="/home" class="navbar-item">
                            Home
                        </router-link>

                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                Quizzes
                            </a>

                            <div class="navbar-dropdown">
                                <router-link to="/quizzes/create" class="navbar-item">
                                    Make A New Quiz
                                </router-link>
                                <router-link to="/quizzes" class="navbar-item">
                                    My Quizzes
                                </router-link>
                            </div>
                        </div>
                    </div>

                    <div class="navbar-end">
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                <i class="far fa-bell"></i>
                            </a>

                            <div class="navbar-dropdown">
                                <router-link to="/invites" class="navbar-item">
                                    Invites
                                    &nbsp;
                                    <span class="tag is-info is-rounded">3</span>
                                </router-link>
                            </div>
                        </div>

                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                {{ email }}
                            </a>

                            <div class="navbar-dropdown">
                                <a class="navbar-item" @click="logout">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
    import Auth from '../services/AuthService'

    const auth = new Auth();

    export default {
        data() {
            return {
                email: localStorage.getItem('email')
            }
        },
        methods: {
            logout() {
                localStorage.clear();
                auth.clearRequestHeaders();
                this.$store.dispatch('clearUser');
                this.$router.push('/');
            }
        }
    }
</script>
