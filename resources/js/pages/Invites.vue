<template>
    <div class="container mx-auto">
        <section class="section pb-sm">
            <p class="p-5 text-gray-600">Your Quiz Invites</p>
        </section>
        <section class="px-2" v-if="quizInvites.length > 0">
            <div class="card mb-4" v-for="inv in quizInvites" :key="inv.id">
                <header class="card-header">
                    <p class="card-header-title">You have been invited to take a quiz!</p>
                </header>
                <div class="card-body">
                    <p>{{ inv.quiz.name }}
                    <p>{{ inv.quiz.description }}</p>
                </div>
                <footer class="card-footer">
                    <a class="card-footer-item" @click="joinQuiz(inv)">
                        <button class="btn btn-dark">Take Quiz</button>
                    </a>
                    <a class="card-footer-item" @click="decline(inv)">
                        <button class="btn btn-secondary">Decline</button>
                    </a>
                </footer>
            </div>
        </section>
        <section class="flex flex-col items-center p-5" v-else>
            <div class="">
                <h1 class="mb-5 text-2xl">No active invites.</h1>
                <h3 class="text-md mb-5">
                    When someone invites you to a quiz, it will show up here.
                </h3>
                <router-link to="/quizzes/create">
                    <button class="btn btn-dark">
                        Create a Quiz
                    </button>
                </router-link>
                <router-link to="/quizzes" class="button is-rounded is-primary is-outlined ml-sm">
                    <button class="btn btn-secondary">
                        My Quizzes
                    </button>
                </router-link>
            </div>
        </section>
    </div>
</template>
<script>

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
                        this.$store.dispatch('decrementNumInvites');
                    }
                })
        },
        decline(invite) {
            axios.get(`/api/quiz_invites/${invite.id}/decline`)
                .then(response => {
                    this.clear(invite);
                    this.$store.dispatch('decrementNumInvites');
                })
        },
        clear(invite) {
            const index = this.quizInvites.findIndex(inv => inv.id === invite.id);

            this.quizInvites.splice(index, 1);
        }
    },
}
</script>