<template>
    <div>
        <textarea
            class="border p-2 rounded w-full text-sm"
            name="question_text"
            placeholder="Enter Question"
            v-model="question.text"></textarea>

        <div v-for="(answer, index) in question.answers" :key="answer.id" class="my-4">
            <div class="flex flex-col md:flex-row">
                <input 
                    type="text" 
                    class="my-2 md:my-0 px-3 py-2 w-full rounded text-sm" 
                    name="answer_text"
                    v-model="answer.text" 
                    placeholder="Answer Text Here"
                    :dusk="`question-${question.id}-answer-input-${index}`">
                <div class="flex items-center">

                    <!-- correct answer button -->
                    <button 
                        class="rounded p-1 md:ml-2" 
                        :class="[answer.correct === 1 ? 'bg-green-400 text-white' : 'bg-gray-100 text-green-300 hover:text-green-500']"
                        @click="answer.correct = 1"
                        :dusk="`question-${question.id}-answer-${index}-correct-button`">
                        <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M12 20C7.59 20 4 16.41 4 12S7.59 4 12 4 20 7.59 20 12 16.41 20 12 20M16.59 7.58L10 14.17L7.41 11.59L6 13L10 17L18 9L16.59 7.58Z" />
                        </svg>
                    </button>

                    <!-- wrong answer button -->
                    <button 
                        class="rounded bg-gray-100 p-1 ml-2"
                        :class="[answer.correct === 0 ? 'bg-red-400 text-white' : 'bg-gray-100 text-red-300 hover:text-red-500']"
                        @click="answer.correct = 0">
                        <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                            <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                        </svg>
                    </button>

                    <!-- remove answer button -->
                    <button class="rounded text-gray-600 p-1 ml-2" @click="removeAnswer(index)" :dusk="`question-${question.id}-answer-${index}-delete-button`">
                        <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                            <path d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <button class="btn btn-secondary text-sm" 
                @click="addAnswer(question.id)" id="add-answer-button">
                New Answer
            </button>
            <button v-show="question.text && questionHasAnswers(question)" 
                class="btn btn-primary text-sm" 
                @click="saveQuestion(question)"
                id="save-question-button">
                Save Question
            </button>
            <button class="btn text-red-500 text-sm" @click="removeQuestion(question.id)">Delete</button>
            <p class="text-green-500 mt-3" v-show="questionSavedMessageVisible">Save successful!</p>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                questionSavedMessageVisible: false,
            }
        },
        props: ['question'],
        methods: {
            addAnswer() {
                this.question.answers.push({ text: '', correct: 0 });
            },
            removeAnswer(index) {
                this.question.answers.splice(index, 1);
            },
            questionHasAnswers(question) {
                if (question.answers.length > 1) {
                    return true;
                }
            },
            saveQuestion(question) {
                this.$emit('question-saved', question)
                this.flashQuestionSavedMessage()
            },
            removeQuestion(questionId) {
                this.$emit('question-removed', questionId)
            },
            flashQuestionSavedMessage() {
                this.questionSavedMessageVisible = true

                setTimeout(() => {
                    this.questionSavedMessageVisible = false
                }, 2000)
            },
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
