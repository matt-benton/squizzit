<template>
    <div>
        <navbar></navbar>
        <div class="container">
            <section class="section">
                <div class="card" v-for="quiz in quizzes" :key="quiz.id">
                    <header class="card-header">
                        <p class="card-header-title">
                            This is my test
                        </p>
                    </header>
                    <div class="card-content">
                        This is a test description
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
