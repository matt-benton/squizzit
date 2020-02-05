<template>
    <section class="flex flex-col justify-center content-center p-2 md:p-10">
        <div class="mb-5 self-center">
            <!-- DON'T FORGET TOP CHECK THIS SUCCESS NOTIFICATION -->
            <div class="alert alert-success" v-if="success">{{ success }}</div>
            <div class="alert alert-primary" v-else>
                Please enter your email address and we will send you a link to reset your password.
            </div>
        </div>
        <div class="form-panel self-center">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input class="form-control" type="text" name="email" v-model.trim="email" @keydown="clearErrors">
                <p class="validation-text" v-for="error in errors" v-bind:key="error.id">{{ error }}</p>
            </div>

            <div class="form-group">
                <div class="control">
                    <button type="button" class="btn btn-dark" @click="submit">Send Reset Link</button>
                    <router-link to="/"><button class="btn btn-secondary">Go Back</button></router-link>
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
