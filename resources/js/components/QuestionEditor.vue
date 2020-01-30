<template>
    <div id="question-editor-container">
        <div class="field">
            <div class="control">
                <textarea
                        class="textarea"
                        name="question_text"
                        placeholder="Enter Question"
                        v-model="question.text"></textarea>
            </div>
        </div>

        <div>
            <div v-for="(answer, index) in question.answers" :key="answer.id" class="mb-sm">
                    <div class="answer-input-container">
                        <input 
                            type="text" 
                            class="input answer-input is-rounded" 
                            name="answer_text"
                            v-model="answer.text" 
                            placeholder="Answer Text Here"
                            :dusk="`answer-input-${index}`">
                        <div class="answer-menu">
                            <label class="checkbox">
                                <input type="checkbox" :dusk="`answer-checkbox-${index}`" v-model="answer.correct">
                                <span class="ml-sm">Correct</span>
                            </label>
                            <button class="button is-danger is-inverted is-rounded ml-sm" @click="removeAnswer(index)" :dusk="`remove-answer-button-${index}`">
                                <span class="fa fa-times"></span>
                            </button>
                        </div>
                    </div>
            </div>
        </div>

        <button v-show="question.text && questionHasAnswers(question)" 
            class="button is-rounded is-primary is-pulled-right ml-sm" 
            @click="$emit('question-saved', question)"
            id="save-question-button">
            Save Question
        </button>
        <button class="button is-rounded is-pulled-right" 
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
    .answer-input-container {
        display: flex;
    }

    .answer-input {
        flex-grow: 1;
        margin-right: 1rem !important; /* ! you might be able to replace this with a utility class */
    }

    .answer-menu {
        display: flex;
        align-items: center;
    }

    .checkbox {
        display: flex;
    }
</style>
