<template>
    <div class="container mx-auto p-2">
        <section class="section is-small">
            <h1 class="text-xl text-gray-600 my-6">{{ quiz.name }}</h1>
            <h3 class="text-md">Your Score: {{ score }}</h3>
            <p class="text-md my-2">You answered {{ correctAnswers }} out of {{ totalQuestions }} questions correctly.</p>
        </section>
        <hr class="my-6">
        <section class="my-8" v-for="(question, questionIndex) in quiz.questions" :key="question.id">
            <p class="text-gray-600 flex items-center">
                <svg class="h-5 w-5 fill-current text-green-500" v-if="questionIsCorrect(question)" viewBox="0 0 24 24">
                    <path d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M12 20C7.59 20 4 16.41 4 12S7.59 4 12 4 20 7.59 20 12 16.41 20 12 20M16.59 7.58L10 14.17L7.41 11.59L6 13L10 17L18 9L16.59 7.58Z" />
                </svg>
                <svg class="h-5 w-5 fill-current text-red-500" v-else viewBox="0 0 24 24">
                    <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                </svg>
                <span class="ml-1">Question {{ questionIndex + 1 }}</span>
            </p>
            <p class="my-4">{{ question.text }}</p>
            <p v-for="(answer, answerIndex) in question.answers" :key="answer.id" class="ml-2 my-1">
                <span v-bind:class="{ 
                    'font-medium text-green-500': userSelectedThisAnswer(question, answer) && questionIsCorrect(question) ,
                    'font-medium text-red-500': userSelectedThisAnswer(question, answer) && questionIsIncorrect(question)
                    }">
                    <span class="text-sm text-gray-600 mr-1">{{ answerIndex + 1 }}.</span>
                    {{ answer.text }}
                </span>
            </p>
            <hr class="my-6">
        </section>
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
    beforeRouteEnter(to, from, next) {
        next(vm => {
            axios.get(`api/quizzes/${vm.$route.params.id}/user_role`)
                .then(response => {
                    if (response.data.role === 'taker') {
                        next();
                    } else {
                        next('/quizzes');
                    }
                });
        });
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