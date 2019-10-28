<template>
    <div class="has-background-light">
        <div class="container">
            <section class="section pb-sm">
                <p class="is-size-4 has-text-grey">Invites</p>
            </section>
            <section class="section" v-if="quizInvites.length > 0">
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
                        <a class="card-footer-item" @click="decline(inv)">Decline</a>
                    </footer>
                </div>
            </section>
            <section class="columns" v-else>
                <div class="column is-half is-offset-one-quarter has-text-grey">
                    <h1 class="is-size-3 pb-sm pt-md">No active invites.</h1>
                    <h2 class="is-size-4 pb-md">
                        When someone invites you to a quiz, it will show up here.
                    </h2>
                    <router-link to="/quizzes/create" class="button is-rounded is-primary">
                        <span class="icon is-small">
                            <i class="fas fa-pen"></i>
                        </span>
                        <span>Create Quiz and Invite Friends</span>
                    </router-link>
                    <router-link to="/quizzes" class="button is-rounded is-primary is-outlined ml-sm">
                        <span class="icon is-small">
                            <i class="fas fa-user-plus"></i>
                        </span>
                        <span>Back to My Quizzes</span>
                    </router-link>
                </div>
            </section>
        </div>
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
                    }
                })
        },
        decline(invite) {
            axios.get(`/api/quiz_invites/${invite.id}/decline`)
                .then(response => {
                    this.clear(invite);
                })
        },
        clear(invite) {
            const index = this.quizInvites.findIndex(inv => inv.id === invite.id);

            this.quizInvites.splice(index, 1);
        }
    },
}
</script>