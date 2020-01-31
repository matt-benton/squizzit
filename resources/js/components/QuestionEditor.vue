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
                    <div class="mx-4 flex items-center">
                        <label class="checkbox">
                            <input type="checkbox" :dusk="`answer-checkbox-${index}`" v-model="answer.correct">
                            <span class="text-sm">Correct</span>
                        </label>
                        <button class="rounded bg-gray-100 text-red-500 p-1 ml-2" @click="removeAnswer(index)" :dusk="`remove-answer-button-${index}`">
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
