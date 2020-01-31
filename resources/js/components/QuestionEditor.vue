<template>
    <div>
        <textarea
            class="border p-3 rounded w-full text-sm"
            name="question_text"
            placeholder="Enter Question"
            v-model="question.text"></textarea>

        <div v-for="(answer, index) in question.answers" :key="answer.id" class="my-4">
            <div class="flex flex-col">
                <input 
                    type="text" 
                    class="my-2 px-3 py-2 w-full rounded text-sm" 
                    name="answer_text"
                    v-model="answer.text" 
                    placeholder="Answer Text Here"
                    :dusk="`answer-input-${index}`">
                <div class="flex items-center">
                    <!-- <label class="checkbox">
                        <input type="checkbox" :dusk="`answer-checkbox-${index}`" v-model="answer.correct">
                        <span class="text-sm">Correct</span>
                    </label> -->
                    <button 
                        class="rounded p-1" 
                        :class="[answer.correct === 1 ? 'bg-green-400 text-white' : 'bg-gray-100 text-green-300']"
                        @click="answer.correct = 1">
                        <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M12 20C7.59 20 4 16.41 4 12S7.59 4 12 4 20 7.59 20 12 16.41 20 12 20M16.59 7.58L10 14.17L7.41 11.59L6 13L10 17L18 9L16.59 7.58Z" />
                        </svg>
                    </button>
                    <button 
                        class="rounded bg-gray-100 p-1 ml-2"
                        :class="[answer.correct === 0 ? 'bg-red-400 text-white' : 'bg-gray-100 text-red-300']"
                        @click="answer.correct = 0">
                        <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                            <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <button v-show="question.text && questionHasAnswers(question)" 
            class="btn text-sm" 
            @click="$emit('question-saved', question)"
            id="save-question-button">
            Save Question
        </button>
        <button class="btn text-sm" 
            @click="addAnswer(question.id)" id="add-answer-button">
            New Answer
        </button>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                
            }
        },
        props: ['question'],
        methods: {
            addAnswer() {
                this.question.answers.push({ text: '' });
            },
            removeAnswer(index) {
                this.question.answers.splice(index, 1);
            },
            questionHasAnswers(question) {
                if (question.answers.length > 1) {
                    return true;
                }
            }
        }
    }
</script>

<style>
    .answer-input {
        flex-grow: 1;
        margin-right: 1rem !important; /* ! you might be able to replace this with a utility class */
    }

    .checkbox {
        display: flex;
    }
</style>
