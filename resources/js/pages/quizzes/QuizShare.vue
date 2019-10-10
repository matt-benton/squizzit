<template>
    <div>
        <navbar></navbar>
        <div class="container">
            <section class="section is-small">
                <h1 class="is-size-3 has-text-weight-normal">
                    {{ quiz.name }}
                    <router-link :to="`/quizzes/${quiz.id}`">
                        <button class="button is-white is-pulled-right is-rounded">Back to Quiz</button>
                    </router-link>
                </h1>
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
                                v-model="form.email.value"
                                @keydown.enter.prevent="sendInvite">
                        </div>
                        <div class="control">
                            <button class="button is-primary" @click="sendInvite">Invite</button>
                        </div>
                    </div>
                    <p class="help is-danger">{{ form.email.errorMessage }}</p>
                </form>
            </section>
        </div>
    </div>
</template>

<script>
    import Navbar from '../../components/Navbar.vue'

    export default {
        data() {
            return {
                quiz: '',
                form: {
                    email: {
                        value: '',
                        errorMessage: ''
                    }
                },
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
                console.log('sendInvite called');
                axios.post(`/api/quiz_invites`, {
                        email: this.form.email.value,
                        quiz_id: this.quiz.id
                    })
                    .then(response => {
                        this.setSuccessMessage(response.data.message);
                        this.clearEmail();
                        this.resetEmailErrorMessage();
                    })
                    .catch(e => {
                        this.setEmailErrorMessage(e.response.data.errors.email[0]);
                    });
            },
            setSuccessMessage(message) {
                this.successMessage = message;
            },
            resetSuccessMessage() {
                this.successMessage = '';
            },
            setEmailErrorMessage(message) {
                this.form.email.errorMessage = message;
            },
            resetEmailErrorMessage() {
                this.form.email.errorMessage = '';
            },
            clearEmail() {
                this.form.email.value = '';
            }
        },
        components: {
            'navbar': Navbar
        },
    }
</script>
