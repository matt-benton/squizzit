<template>
    <div>
        <navbar></navbar>
        <div class="container">
            <section class="section pb-sm">
                <p class="is-size-4 has-text-grey">My Quizzes</p>
            </section>
            <section class="section quiz-grid">
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
    grid-gap: 20px;
}

</style>
