<template>
    <div class="flex justify-center content-center p-2 md:p-10">
        <div class="bg-gray-100 p-4 md:p-8 w-full md:w-3/5 lg:w-2/5 xl:w-1/4">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input class="form-control" type="email" name="email" v-model="form.email" @keydown.enter="login">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input class="form-control" type="password" name="password" v-model="form.password" @keydown.enter="login">
            </div>

            <div class="form-group">
                <button type="button" class="btn" @click="login" dusk="sign-in-button">Sign In</button>
                <router-link to="/"><button class="btn">Go Back</button></router-link>
            </div>
            <router-link to="/auth/send_password_reset" class="text-blue-500">Forgot password?</router-link>

            <p class="text-red-700" v-if="form.error">{{ form.error }}</p>
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
