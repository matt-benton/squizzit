<template>
    <div>
        <navbar></navbar>
        <div class="container">
            <section class="section is-small">
                <h1 class="is-size-3 has-text-weight-normal">{{ quiz.name }}</h1>
                <h3 class="is-size-5 has-text-weight-light">{{ quiz.description }}</h3>
            </section>
            <hr class="has-background-primary">
            <section class="section">
                <div class="notification is-success" v-show="successMessage">
                    <button class="delete" @click="resetSuccessMessage"></button>
                    {{ successMessage }}
                </div>
                <form>
                    <div class="field has-addons">
                        <div class="control">
                            <input 
                                class="input" 
                                type="text" 
                                placeholder="Email of person to invite" 
                                v-model="email"
                                @keydown.enter="sendInvite">
                        </div>
                        <div class="control">
                            <button class="button is-primary" @click="sendInvite">Invite</button>
                        </div>
                    </div>
                        <p class="help">Validation text should go here</p>
                        <p class="help">Keyboard shortcut text</p>
                </form>
            </section>
        </div>
    </div>
</template>

<script>
    import Navbar from '../../components/Navbar.vue'
    import { required } from 'vuelidate/lib/validators'

    export default {
        data() {
            return {
                quiz: '',
                email: '',
                successMessage: ''
            }
        },
        created() {
            this.getQuiz();
        },
        methods: {
            getQuiz() {
                axios.get(`/api/quizzes/${this.$route.params.id}`)
                    .then(response => {
                        this.quiz = response.data.quiz;
                    })
            },
            sendInvite() {
                axios.post(`/api/quizzes/${this.quiz.id}/invite`, {
                        email: this.email
                    })
                    .then(response => {
                        this.setSuccessMessage(response.data.message);
                        this.clearEmail();
                    })
                    .catch(e => {
                        // ! do something with error
                    });
            },
            setSuccessMessage(message) {
                this.successMessage = message;
            },
            resetSuccessMessage() {
                this.successMessage = '';
            },
            clearEmail() {
                this.email = '';
            }
        },
        validations: {
            form: {
                name: {
                    required
                }
            }
        },
        components: {
            'navbar': Navbar
        },
    }
</script>
