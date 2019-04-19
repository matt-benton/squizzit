<template>
    <section class="section">
        <div class="columns">
            <div class="column is-4 is-offset-4">

                <div class="notification is-success" v-if="success">{{ success }}</div>
                <div class="notification" v-else>
                    Please enter your email address and we will send you a link to reset your password.
                </div>

                <div class="box">
                    <div class="field">
                        <label for="email" class="label">Email</label>
                        <div class="control has-icons-left">
                            <input class="input" type="text" name="email" v-model.trim="email" @keydown="clearErrors">
                            <span class="icon is-small is-left">
                                <i class="far fa-envelope"></i>
                            </span>
                        </div>
                        <p class="help is-danger" v-for="error in errors">{{ error }}</p>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button type="button" class="button is-primary is-rounded" @click="submit">Send Password Reset Link</button>
                            <router-link to="/" class="button is-text is-rounded">Go Back</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                email: '',
                errors: [],
                success: '',
            }
        },
        methods: {
            submit() {
                axios.post('/password/email', {
                    email: this.email
                })
                .then(response => {
                    this.success = response.data;
                })
                .catch(e => {
                    e.response.data.errors.email.forEach(error => this.errors.push(error));
                });
            },
            clearErrors() {
                this.errors = [];
            }
        }
    }
</script>
