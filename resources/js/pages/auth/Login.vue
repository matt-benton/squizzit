<template>
    <div class="flex flex-col items-center p-2 md:p-10">
        <div>
            <h1 class="font-cursive text-yellow-500 text-2xl mb-3 mx-3">Squizzit</h1>
            <div class="form-panel">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" v-model="form.email" @keydown.enter="login">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input class="form-control" type="password" name="password" v-model="form.password" @keydown.enter="login">
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-dark" @click="login" dusk="sign-in-button">Sign In</button>
                    <router-link to="/"><button class="btn btn-secondary">Go Back</button></router-link>
                </div>
                <router-link to="/auth/send_password_reset" class="text-blue-500">Forgot password?</router-link>

                <p class="validation-text" v-if="form.error">{{ form.error }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    import Auth from '../../services/AuthService';

    const auth = new Auth();

    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                    error: '',
                }
            }
        },
        methods: {
            login() {
                axios.post('/api/login', this.form)
                .then(response => {
                    this.$store.dispatch('storeUser', response.data);
                    auth.storeUser(response.data.email, response.data.token);
                    this.$router.push('/quizzes');
                })
                .catch(e => {
                    this.form.error = e.response.data.error;
                });
            }
        }
    }
</script>
