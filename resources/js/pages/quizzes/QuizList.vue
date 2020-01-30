<template>
    <div class="container mx-auto pb-10">
        <section class="p-5 text-gray-600">
            <p>My Quizzes</p>
        </section>
        <section class="quiz-grid" v-if="quizzes.length > 0">
            <div class="bg-gray-200 p-4 rounded" v-for="quiz in orderedQuizzes" :key="quiz.id">
                <header class="pb-5 text-gray-700">
                    <router-link :to="`/quizzes/${quiz.id}`" class="card-header-title" v-if="quiz.pivot.role === 'maker'">
                        {{ quiz.name }}
                    </router-link>
                    <router-link :to="`/quizzes/${quiz.id}/take`" class="card-header-title" v-else>
                        {{ quiz.name }}
                    </router-link>
                </header>
                <div class="text-gray-600 text-sm">
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

    export default {
        data() {
            return {
                quizzes: []
            }
        },
        computed: {
            orderedQuizzes: function () {
                return this.quizzes.sort((a, b) => a.name.toUpperCase() > b.name.toUpperCase());
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
    }
</script>

<style>

.quiz-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-gap: 30px;
}

</style>
