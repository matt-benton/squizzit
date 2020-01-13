<template>
    <div class="has-background-light">
        <div class="container">
            <section class="section is-small">
                <h1 class="is-size-3 has-text-weight-normal" id="quiz-title">{{ quiz.name }}</h1>
                <h3 class="is-size-5 has-text-weight-light">{{ quiz.description }}</h3>
            </section>
            <hr class="has-background-primary">
            <section class="section" v-for="(question, index) in quiz.questions" :key="question.id">
                <p class="menu-label">Question {{ index + 1 }}</p>
                <p class="mb-sm">{{ question.text }}</p>
                <div class="control">
                    <label class="radio radio-label mb-sm" v-for="answer in question.answers" :key="answer.id">
                        <input 
                            type="radio" 
                            :name="`question-${ index + 1 }-answers`" 
                            :value="answer.text" 
                            v-model="question.takerAnswer.text"
                            @change="saveAnswer(question)">
                        {{ answer.text }}
                    </label>
                </div>
            </section>
            <section class="section is-small">
                <button class="button is-primary is-rounded" id="submit-quiz-button" @click="submitQuiz">Submit Answers</button>
            </section>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            quiz: '',
        }
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            axios.get(`/api/quizzes/${vm.$route.params.id}/is_submitted`)
                .then(response => {
                    if (response.data === 1) {
                        next(`/quizzes/${vm.quiz.id}/results`);
                    }
                });
        });
    },
    created() {
        this.getQuiz();
    },
    methods: {
        getQuiz () {
            axios.get(`/api/quizzes/${this.$route.params.id}/take`)
                .then(response => {
                    this.quiz = response.data.quiz;
                });
        },
        saveAnswer (question) {
            axios.post(`/api/taker_answers/save`, {
                    question_id: question.id,
                    answer_text: question.takerAnswer.text,
                })
        },
        submitQuiz () {
            axios.post(`/api/quizzes/${this.quiz.id}/submit`, {
                    quizId: this.quiz.id,
                })
                .then(response => {
                    if (response.data.message === 'success') {
                        this.$router.push(`/quizzes/${this.quiz.id}/results`);
                    }
                });
        },
    },
}
</script>

<style>

.radio-label {
    display: block;
    margin-left: 0 !important;
}

</style>