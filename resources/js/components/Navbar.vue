<template>
    <div>
        <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
            <div id="navbarBasicExample" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item">
                        Home
                    </a>

                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                            Quizzes
                        </a>

                        <div class="navbar-dropdown">
                            <a class="navbar-item">
                                Make A New Quiz
                            </a>
                            <a class="navbar-item">
                                My Quizzes
                            </a>
                        </div>
                    </div>
                </div>

                <div class="navbar-end">
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
        </nav>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                email: localStorage.getItem('email')
            }
        },
        methods: {
            logout() {
                localStorage.clear();
                this.$store.dispatch('clearUser');
                this.$router.push('/');
            }
        }
    }
</script>
