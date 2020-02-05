<template>
    <div class="container mx-auto pb-10">
        <section v-if="createdQuizzes.length > 0">
            <h3 class="p-5 text-gray-600">My Created Quizzes</h3>
            <div class="quiz-grid">
                <div class="mx-3 mb-6" v-for="quiz in createdQuizzes" :key="quiz.id">
                    <quiz-card :quiz="quiz"></quiz-card>
                </div>
            </div>
        </section>
        <section v-if="joinedQuizzes.length > 0">
            <h3 class="p-5 text-gray-600">My Joined Quizzes</h3>
            <div class="quiz-grid" v-if="joinedQuizzes.length > 0">
                <div class="mx-3 mb-6" v-for="quiz in joinedQuizzes" :key="quiz.id">
                    <quiz-card :quiz="quiz"></quiz-card>
                </div>
            </div>
        </section>
        <section class="columns" v-else>
            <div class="column is-half is-offset-one-quarter has-text-grey">
                <h1 class="is-size-3 pb-sm pt-md">No quizzes available</h1>
                <h2 class="is-size-4 pb-md">
                    To get started, join a quiz or make one yourself.  Once you
                    you have made or joined a quiz, it will show up here.
                </h2>
                <router-link to="/quizzes/create" class="button is-rounded is-primary">
                    <span class="icon is-small">
                        <i class="fas fa-pen"></i>
                    </span>
                    <span>Make a Quiz</span>
                </router-link>
                <router-link to="/invites" class="button is-rounded is-primary is-outlined ml-sm">
                    <span class="icon is-small">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    <span>Join a Quiz</span>
                </router-link>
            </div>
        </section>
    </div>
</template>

<script>
    import QuizCard from '../../components/QuizCard.vue'

    export default {
        data() {
            return {
                quizzes: []
            }
        },
        computed: {
            joinedQuizzes: function () {
                return this.quizzes.filter(quiz => quiz.pivot.role === 'taker')
            },
            createdQuizzes: function () {
                return this.quizzes.filter(quiz => quiz.pivot.role === 'maker')
            },
        },
        created() {
            this.getQuizzes();
        },
        methods: {
            getQuizzes () {
                axios.get('/api/quizzes')
                .then(response => {
                    this.quizzes = response.data.quizzes;
                })
            }
        },
        components: {
            'quiz-card': QuizCard,
        }
    }
</script>

<style>

.quiz-grid {
    display: grid;
    grid-template-columns: 1fr;
}

@media (min-width: 640px) {
    .quiz-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (min-width: 768px) {
    .quiz-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

</style>
