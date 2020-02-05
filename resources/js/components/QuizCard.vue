<template>
    <div class="h-full bg-gray-100 rounded shadow-md">
        <header class="p-5 text-lg">
            <router-link :to="`/quizzes/${quiz.id}`" class="card-header-title" v-if="quiz.pivot.role === 'maker'">
                {{ quiz.name }}
            </router-link>
            <router-link :to="`/quizzes/${quiz.id}/take`" class="card-header-title" v-else>
                {{ quiz.name }}
            </router-link>
        </header>
        <div class="p-5 text-gray-600">
            <p class="mb-4">{{ quiz.description }}</p>
        </div>
        <div class="p-5 text-gray-600">
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