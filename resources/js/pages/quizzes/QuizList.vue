<template>
    <div class="has-background-light">
        <navbar></navbar>
        <div class="container">
            <section class="section pb-sm">
                <p class="is-size-4 has-text-grey">My Quizzes</p>
            </section>
            <section class="section quiz-grid" v-if="quizzes.length > 0">
                <div class="card" v-for="quiz in orderedQuizzes" :key="quiz.id">
                    <header class="card-header">
                        <router-link :to="`/quizzes/${quiz.id}`" class="card-header-title">
                            {{ quiz.name }}
                        </router-link>
                    </header>
                    <div class="card-content">
                        {{ quiz.description }}
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
                    <a class="button is-rounded is-primary is-outlined ml-sm">
                        <span class="icon is-small">
                            <i class="fas fa-user-plus"></i>
                        </span>
                        <span>Join a Quiz</span>
                    </a>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import Navbar from '../../components/Navbar.vue'

    export default {
        data() {
            return {
                quizzes: []
            }
        },
        computed: {
            orderedQuizzes: function () {
                return this.quizzes.sort((a, b) => a.name.toUpperCase() > b.name.toUpperCase())
            }
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
            'navbar': Navbar
        },
    }
</script>

<style>

.quiz-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-gap: 30px;
}

</style>
