<template>
    <div class="card border border-gray-300 flex flex-col">
        <header class="card-header border-b border-gray-300 text-gray-700 rounded-t flex justify-between items-center">
            {{ quiz.name }}
            <svg class="h-8 w-8 fill-current" viewBox="0 0 24 24">
                <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" />
            </svg>
        </header>
        <div class="card-body flex-1">
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