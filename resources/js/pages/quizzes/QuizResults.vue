<template>
    <div class="has-background-light">
        <div class="container">
            <section class="section is-small">
                <h1 class="is-size-1">{{ quiz.name }}</h1>
                <h3 class="is-size-3">Your Score: {{ score }}</h3>
                <p>You answered {{ correctAnswers }} out of {{ totalQuestions }} questions correctly.</p>
            </section>
            <hr class="has-background-primary">
            <section class="section" v-for="(question, index) in quiz.questions" :key="question.id">
                <p class="menu-label">
                    <i v-if="questionIsCorrect(question)" class="far fa-check-circle has-text-success"></i>
                    <i v-else class="fas fa-times has-text-danger"></i>
                    Question {{ index + 1 }} 
                </p>
                <p class="mb-sm">{{ question.text }}</p>
                <p v-for="answer in question.answers" :key="answer.id">
                    <span v-bind:class="{ 
                        'has-text-weight-bold has-text-success': userSelectedThisAnswer(question, answer) && questionIsCorrect(question) ,
                        'has-text-weight-bold has-text-danger': userSelectedThisAnswer(question, answer) && questionIsIncorrect(question)
                        }">
                        {{ answer.text }}
                    </span>
                </p>
            </section>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            quiz: '',
            takerAnswers: [],
            score: '',
        }
    },
    created() {
        this.getQuizResults();
    },
    computed: {
        totalQuestions: function () {
            return this.quiz.questions.length;
        },
        correctAnswers: function () {
            return this.takerAnswers.filter(takerAnswer => takerAnswer.is_correct === 1).length;
        },
    },
    methods: {
        getQuizResults() {
            axios.get(`/api/quizzes/${this.$route.params.id}/results`)
                .then(response => {
                    this.quiz = response.data.quiz;
                    this.takerAnswers = response.data.takerAnswers;
                    this.score = response.data.score;
                });
        },
        questionIsCorrect(question) {
            const takerAnswer = this.takerAnswers.find(takerAnswer => {
                return takerAnswer.question_id === question.id;
            });

            return takerAnswer.is_correct === 1;
        },
        questionIsIncorrect(question) {
            const takerAnswer = this.takerAnswers.find(takerAnswer => {
                return takerAnswer.question_id === question.id;
            });

            return takerAnswer.is_correct !== 1;
        },
        userSelectedThisAnswer(question, answer) {
            const takerAnswer = this.takerAnswers.find(takerAnswer => {
                return takerAnswer.question_id === question.id;
            });

            return takerAnswer.answer_id === answer.id;
        },
    }
}

</script>