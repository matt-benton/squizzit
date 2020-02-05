<template>
    <div class="container mx-auto p-2">
        <div class="my-4">
            <h3 class="text-lg my-1">
                {{ quiz.name }}

                <router-link :to="`/quizzes/${quiz.id}`">
                    <button class="float-right py-1 px-2 text-sm text-blue-500 bg-gray-300 rounded" id="share-button">
                        Back to Quiz
                    </button>
                </router-link>
            </h3>
            <h5 class="text-sm text-gray-600">{{ quiz.description }}</h5>
        </div>
        <hr class="my-8">
        <section class="flex flex-col items-center">
            <div class="alert alert-success mb-6" v-show="successMessage">
                <button class="delete" @click="resetSuccessMessage"></button>
                {{ successMessage }}
            </div>
            <div class="form-panel">
                <form id="invite-form">
                    <div class="field has-addons">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input 
                                class="form-control" 
                                type="text"
                                v-model="form.email.value"
                                @keydown.enter.prevent="sendInvite"
                                name="email">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" @click.prevent="sendInvite" id="invite-button">Invite</button>
                        </div>
                    </div>
                    <p class="validation-text">{{ form.email.errorMessage }}</p>
                </form>
            </div>
        </section>
    </div>
</template>

<script>

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
                successMessage: '',
            }
        },
        beforeRouteEnter(to, from, next) {
        next(vm => {
            axios.get(`api/quizzes/${vm.$route.params.id}/user_role`)
                .then(response => {
                    if (response.data.role === 'maker') {
                        next();
                    } else {
                        next('/quizzes');
                    }
                });
        });
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
    }
</script>
