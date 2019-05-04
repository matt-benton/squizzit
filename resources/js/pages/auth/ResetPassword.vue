<template>
    <section class="section">
        <div class="columns">
            <div class="column is-4 is-offset-4">
                <div class="box">

                    <div class="notification is-success" v-if="success">{{ success }}</div>

                    <!-- Email -->
                    <div class="field">
                        <label for="email" class="label">Email</label>
                        <div class="control has-icons-left has-icons-right">
                            <input class="input"
                                    :class="{ 'is-danger': $v.form.email.$error, 'is-success': $v.form.email.$invalid === false }"
                                    type="text"
                                    name="email"
                                    v-model.trim.lazy="$v.form.email.$model">
                            <span class="icon is-small is-left">
                                <i class="far fa-envelope"></i>
                            </span>
                            <span class="icon is-small is-right" v-if="$v.form.email.$error">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            <span class="icon is-small is-right" v-else-if="$v.form.email.$invalid === false">
                                <i class="fas fa-check"></i>
                            </span>
                            <p class="help is-danger" v-if="$v.form.email.$error">A valid email address is required.</p>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="field">
                        <label for="password" class="label">New Password</label>
                        <div class="control has-icons-left has-icons-right">
                            <input class="input"
                                    :class="{ 'is-danger': $v.form.password.$error, 'is-success': $v.form.password.$invalid === false }"
                                    type="password"
                                    name="password"
                                    v-model.trim.lazy="$v.form.password.$model">
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                            <span class="icon is-small is-right" v-if="$v.form.password.$error">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            <span class="icon is-small is-right" v-else-if="$v.form.password.$invalid === false">
                                <i class="fas fa-check"></i>
                            </span>
                            <ul class="help is-danger" style="list-style-type:disc; margin-left: 3em" v-if="$v.form.password.$error">
                                <li>Eight character minimum</li>
                                <li>At least one lowercase letter</li>
                                <li>At least one uppercase letter</li>
                                <li>At least one number</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Password Confirmation -->
                    <div class="field">
                        <label class="label" for="password_confirmation">Confirm Password</label>
                        <div class="control has-icons-left has-icons-right">
                            <input class="input"
                            :class="{ 'is-danger': $v.form.password_confirmation.$error, 'is-success': $v.form.password_confirmation.$invalid === false && $v.form.password_confirmation.$dirty }"
                            type="password"
                            name="password_confirmation"
                            v-model.trim="$v.form.password_confirmation.$model">
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                            <span class="icon is-small is-right" v-if="$v.form.password_confirmation.$error">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            <span class="icon is-small is-right" v-else-if="$v.form.password_confirmation.$invalid === false && $v.form.password_confirmation.$dirty">
                                <i class="fas fa-check"></i>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button
                                    type="button"
                                    class="button is-primary is-rounded"
                                    @click="submit"
                                    :disabled="$v.$invalid"
                                    dusk="sign-up-button">Set New Password</button>
                            <router-link to="/" class="button is-text is-rounded">Go Back</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import { required, email, minLength, sameAs } from 'vuelidate/lib/validators';

    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                    password_confirmation: '',
                    token: this.$route.params.token,
                },
                success: '',
            }
        },
        methods: {
            submit() {
                axios.post('/password/reset', this.form)
                .then(response => {
                    this.success = response.data;
                })
                .catch(e => {
                    console.log(e);
                });
            }
        },
        validations: {
            form: {
                email: {
                    required,
                    email,
                },
                password: {
                    required,
                    minLength: minLength(8),
                    hasUpperCase: value => value != value.toLowerCase(),
                    hasLowerCase: value => value != value.toUpperCase(),
                    hasNumber: value => /\d/.test(value),
                },
                password_confirmation: {
                    sameAs: sameAs('password')
                },
            }
        }
    }
</script>
