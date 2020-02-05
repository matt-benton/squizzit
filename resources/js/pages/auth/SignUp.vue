<template>
    <div class="flex flex-col items-center p-2 md:p-10">
        <div>
            <h1 class="font-cursive text-yellow-500 text-2xl mb-3 mx-3">Squizzit</h1>
            <div class="form-panel">
                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                        <input class="form-control"
                                :class="{ 'is-danger': $v.form.email.$error, 'is-success': $v.form.email.$invalid === false }"
                                type="text"
                                name="email"
                                v-model.trim.lazy="$v.form.email.$model"
                                @keydown.enter="register">
                                <p class="validation-text" v-show="$v.form.email.$dirty && $v.form.email.required === false">An email address is required.</p>
                                <p class="validation-text" v-show="$v.form.email.$dirty && $v.form.email.email === false">The email must be a valid email address.</p>
                                <p class="validation-text" v-show="$v.form.email.$error && $v.form.email.isUnique === false">An account with this email address is already registered.</p>
                            </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input class="form-control"
                            :class="{ 'is-danger': $v.form.password.$error, 'is-success': $v.form.password.$invalid === false }"
                            type="password"
                            name="password"
                            id="password-input"
                            v-model.trim.lazy="$v.form.password.$model"
                            @keydown.enter="register">
                    <ul class="validation-text" style="list-style-type:disc; margin-left: 3em" v-if="$v.form.password.$error">
                        <li>Eight character minimum</li>
                        <li>At least one lowercase letter</li>
                        <li>At least one uppercase letter</li>
                        <li>At least one number</li>
                    </ul>
                </div>

                <!-- Password Confirmation -->
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input class="form-control"
                        :class="{ 'is-danger': $v.form.password_confirmation.$error, 'is-success': $v.form.password_confirmation.$invalid === false && $v.form.password_confirmation.$dirty }"
                        type="password"
                        name="password_confirmation"
                        v-model.trim="$v.form.password_confirmation.$model"
                        @keydown.enter="register">
                </div>

                <div class="form-group">
                    <button
                        type="button"
                        class="btn btn-dark"
                        @click="register"
                        :disabled="$v.$invalid"
                        dusk="sign-up-button">
                        Sign Up
                    </button>
                    <router-link to="/">
                        <button class="btn btn-secondary">Go Back</button>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Auth from '../../services/AuthService';
    import { required, email, minLength, sameAs } from 'vuelidate/lib/validators';

    const auth = new Auth();

    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                    password_confirmation: '',
                }
            }
        },
        methods: {
            register() {
                axios.post('/api/register', this.form)
                .then(response => {
                    this.$store.dispatch('storeUser', response.data);
                    auth.storeUser(response.data.email, response.data.token);
                    this.$router.push('/quizzes');
                });
            }
        },
        validations: {
            form: {
                email: {
                    required,
                    email,
                    isUnique(email) {
                        return new Promise((resolve, reject) => {
                            axios.post('/api/email_is_unique', {
                                    email
                                })
                                .then(response => {
                                     resolve(response.data.unique);
                                });
                        });
                    }
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
