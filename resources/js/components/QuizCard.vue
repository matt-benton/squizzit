<template>
    <div class="card">
        <header class="card-header">
            <router-link :to="`/quizzes/${quiz.id}`" class="card-header-title" v-if="quiz.pivot.role === 'maker'">
                {{ quiz.name }}
            </router-link>
            <router-link :to="`/quizzes/${quiz.id}/take`" class="card-header-title" v-else>
                {{ quiz.name }}
            </router-link>
        </header>
        <div class="card-body">
            <p>{{ quiz.description }}</p>
        </div>
        <div class="card-footer">
            <p>{{ quiz.numQuestions }} questions</p>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {

        }
    },
    props: ['quiz'],
}
</script>