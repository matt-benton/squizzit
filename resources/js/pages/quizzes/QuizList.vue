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
        <section class="flex flex-col items-center p-5" v-else>
            <div class="max-w-xl mt-10">
                <h1 class="mb-5 text-2xl">No quizzes available</h1>
                <h3 class="text-md mb-5">
                    To get started, join a quiz or make one yourself.  Once you
                    you have made or joined a quiz, it will show up here.
                </h3>
                <router-link to="/quizzes/create">
                    <button class="btn btn-dark">
                        Make a Quiz
                    </button>
                </router-link>
                <router-link to="/invites">
                    <button class="btn btn-secondary">
                        Join a Quiz
                    </button>
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
