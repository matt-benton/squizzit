<template>
    <div>
        <navbar></navbar>
        <div class="container">
            <section class="section">
                <p>{{ quiz.id }}</p>
                <p>{{ quiz.name }}</p>
                <p>{{ quiz.description }}</p>
            </section>
        </div>
    </div>
</template>

<script>
    import Navbar from '../../components/Navbar.vue'

    export default {
        data() {
            return {
                quiz: ''
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
                })
            }
        },
        components: {
            'navbar': Navbar
        }
    }
</script>
