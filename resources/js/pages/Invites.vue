<template>
    <div>
        <navbar></navbar>
        <div class="container">
            <section class="section">
                <div class="card mb-md" v-for="inv in quizInvites" :key="inv.id">
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
                        <a class="card-footer-item" @click="joinQuiz(inv)">Take Quiz</a>
                    </footer>
                </div>
            </section>
        </div>
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
        },
        joinQuiz(invite) {
            axios.post(`/api/quizzes/join`, {
                    quizInviteId: invite.id,
                    quizId: invite.quiz_id
                })
                .then(response => {
                    if (response.data.success) {
                        this.$router.push(`/quizzes/${invite.quiz.id}/take`);
                    }
                })
        }
    },
    components: {
        'navbar': Navbar
    }
}
</script>