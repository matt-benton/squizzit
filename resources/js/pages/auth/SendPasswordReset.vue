<template>
    <section class="section">
        <div class="columns">
            <div class="column is-4 is-offset-4">
                <div class="notification">
                    Please enter your email address and we will send you a link to reset your password.
                </div>
                <div class="box">
                    <div class="field">
                        <label for="email" class="label">Email</label>
                        <div class="control has-icons-left">
                            <input class="input"
                                    :class="{ 'is-danger': $v.email.$error, 'is-success': $v.email.$invalid === false }"
                                    type="text"
                                    name="email"
                                    v-model.trim.lazy="$v.email.$model">
                            <span class="icon is-small is-left">
                                <i class="far fa-envelope"></i>
                            </span>
                        </div>
                        <p class="help is-danger" v-if="$v.email.$error">Please enter a valid email address.</p>
                    </div>

                    <div class="field is-horizontal">
                        <div class="control">
                            <button type="button" class="button is-primary is-rounded">Send Password Reset Link</button>
                            <router-link to="/" class="button is-text is-rounded">Go Back</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import { required, email } from 'vuelidate/lib/validators';

    export default {
        data() {
            return {
                email: ''
            }
        },
        validations: {
            email: {
                required,
                email,
            }
        }
    }
</script>
