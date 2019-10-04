<template>
    <div>
        <navbar></navbar>
        <section class="section">
            <div class="card mb-md" v-for="inv in quizInvites" :key="inv.email">
                <header class="card-header">
                    <p class="card-header-title">You have been invited to take a quiz!</p>
                </header>
                <div class="card-content">
                    <div class="content">
                        <p>{{ inv.quiz.name }}
                        <p>{{ inv.quiz.description }}</p>
                    </div>
                </div>
                <footer class="card-footer">
                    <a href="#" class="card-footer-item">Take Quiz</a>
                </footer>
            </div>
        </section>
    </div>
</template>
<script>
import Navbar from '../components/Navbar.vue'

export default {
    data() {
        return {
            quizInvites: []
        }
    },
    created() {
        this.getQuizInvites();
    },
    methods: {
        getQuizInvites() {
            axios.get('/api/quiz_invites')
                .then(response => {
                    this.quizInvites = response.data.quizInvites;
                })
        }
    },
    components: {
        'navbar': Navbar
    }
}
</script>