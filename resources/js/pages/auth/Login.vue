<template>
    <section class="section">
        <div class="columns">
            <div class="column is-4 is-offset-4">
                <div class="box">
                    <div class="field">
                        <label for="email" class="label">Email</label>
                        <div class="control has-icons-left">
                            <input class="input" type="email" name="email" v-model="form.email" @keydown.enter="login">
                            <span class="icon is-small is-left">
                                <i class="far fa-envelope"></i>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <label for="password" class="label">Password</label>
                        <div class="control has-icons-left">
                            <input class="input" type="password" name="password" v-model="form.password" @keydown.enter="login">
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                    </div>

                    <div class="field is-horizontal">
                        <div class="column is-two-thirds">
                            <div class="control">
                                <button type="button" class="button is-primary is-rounded" @click="login" dusk="sign-in-button">Sign In</button>
                                <router-link to="/" class="button is-text is-rounded">Go Back</router-link>
                            </div>
                        </div>
                        <div class="column is-one-third">
                            <div class="control">
                                <router-link to="/auth/send_password_reset" class="button is-text is-rounded">Forgot password?</router-link>
                            </div>
                        </div>
                    </div>

                    <p class="help is-danger" v-if="form.error">{{ form.error }}</p>
                </div>
            </div>
        </div>
    </section>
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
                    this.$router.push('/home');
                })
                .catch(e => {
                    this.form.error = e.response.data.error;
                });
            }
        }
    }
</script>
