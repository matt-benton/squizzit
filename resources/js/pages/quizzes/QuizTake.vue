<template>
    <div class="has-background-light">
        <div class="container">
            <section class="section is-small">
                <h1 class="is-size-3 has-text-weight-normal">{{ quiz.name }}</h1>
                <h3 class="is-size-5 has-text-weight-light">{{ quiz.description }}</h3>
            </section>
            <hr class="has-background-primary">
            <section class="section" v-for="(question, index) in quiz.questions" :key="question.id">
                <p class="menu-label">Question {{ index + 1 }}</p>
                <p class="mb-sm">{{ question.text }}</p>
                <div class="control" v-if="question.type === 'multiple_choice'">
                    <label class="radio radio-label mb-sm" v-for="answer in question.answers" :key="answer.id">
                        <input type="radio" :name="`question-${ index + 1 }-answers`">
                        {{ answer.text }}
                    </label>
                </div>
                <!-- still need short answer type -->
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
    created() {
        this.getQuiz();
    },
    methods: {
        getQuiz () {
            axios.get(`/api/quizzes/${this.$route.params.id}`)
                .then(response => {
                    this.quiz = response.data.quiz;
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