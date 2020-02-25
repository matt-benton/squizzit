<template>
    <div class="container mx-auto p-2 md:px-20">
        <div class="my-4">
            <h3 class="text-2xl my-1 text-gray-800" id="quiz-title">
                {{ quiz.name }}
            </h3>
            <h5 class="text-sm text-gray-700">
                {{ quiz.description }}
            </h5>
        </div>
        <section class="p-6 bg-gray-100 my-5 shadow-md border-t-4 border-blue-900" v-for="(question, index) in quiz.questions" :key="question.id">
            <p class="text-sm text-gray-600">Question {{ index + 1 }}</p>
            <p class="my-2 text-gray-800">{{ question.text }}</p>
            <div class="flex flex-col p-4">
                <label class="my-1 cursor-pointer" v-for="answer in question.answers" :key="answer.id">
                    <input 
                        type="radio" 
                        class="mr-2"
                        :name="`question-${ index + 1 }-answers`" 
                        :value="answer.id" 
                        v-model="question.takerAnswer.answer_id"
                        @change="saveAnswer(question)">
                    {{ answer.text }}
                </label>
            </div>
        </section>
        <section class="">
            <button class="btn btn-dark" id="submit-quiz-button" @click="submitQuiz">Submit Answers</button>
        </section>
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
            // make sure the user accessing this page is a quiz taker
            axios.get(`api/quizzes/${vm.$route.params.id}/user_role`)
                .then(response => {
                    if (response.data.role === 'taker') {
                        next();
                    } else {
                        next('/quizzes');
                    }
                });

            // check to see if this quiz has been submitted
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
                    answer_id: question.takerAnswer.answer_id,
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