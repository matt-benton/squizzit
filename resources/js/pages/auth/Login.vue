<template>
    <section class="section">
        <div class="columns">
            <div class="column is-4 is-offset-4">
                <div class="box">
                    <div class="field">
                        <label for="email" class="label">Email</label>
                        <div class="control has-icons-left">
                            <input class="input" type="email" name="email" v-model="form.email">
                            <span class="icon is-small is-left">
                                <i class="far fa-envelope"></i>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <label for="password" class="label">Password</label>
                        <div class="control has-icons-left">
                            <input class="input" type="password" name="password" v-model="form.password">
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button type="button" class="button is-primary is-rounded" @click="login">Sign In</button>
                            <router-link to="/" class="button is-text is-rounded">Go Back</router-link>
                        </div>
                    </div>
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
                    console.log(e);
                });
            }
        }
    }
</script>
